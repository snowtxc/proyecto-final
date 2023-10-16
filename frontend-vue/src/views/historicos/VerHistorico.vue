<script setup>
     import { computed, ref ,onBeforeMount }  from "vue";
     import dayjs from "dayjs";
     import { useRoute } from "vue-router";
     import   RegistroController  from "../../services/RegistroController";
     import { appStore } from "../../store/app";
     import InfiniteLoading from 'v3-infinite-loading'
     import 'v3-infinite-loading/lib/style.css';
     import Breadcrumbs from "../../components/Breadcrumbs.vue";
     import ComponenteController from "../../services/ComponenteController";

     

     const route = useRoute();
     const $appStore = appStore();
     const { id } = route.params;
     const  historicos = ref([]);

     const filters = ref({
         startDate: null,
         endDate: null
     });

     const page = ref(1);
     const maxRows = 10;
     const loading = ref(true);
     const hasMoreData = ref(true);
     const title = ref("");

     const historicosFormatted = computed(()=>{
            return historicos.value.map(historico => {
                const { fechaHora } = historico;
                return {
                    ...historico,
                    fechaHora: dayjs(fechaHora).format('DD/MM/YYYY HH:mm A'),
                }
            })
     })
        onBeforeMount(async()=>{
            $appStore.setGlobalLoading(true);
            const  [ componenteData ]  =  await Promise.all([ComponenteController.getById(id) ,getHistoricos()]);
            title.value  = `Históricos del componente "${componenteData.Nombre}""`;
            $appStore.setGlobalLoading(false);
        });

     const getHistoricos = async()=>{
         loading.value = true;
         const historicosRes = await   RegistroController.list(id, page.value, maxRows, filters.value); 
         const { data , countRows} = historicosRes;
         historicos.value = [...historicos.value, ...data]
         hasMoreData.value = historicos.value.length < countRows;
         loading.value = false;
        
     }
     
    
     const applyFilters = ()=>{
        $appStore.setGlobalLoading(true);
        loading.value = true;
        page.value = 1;
        historicos.value = [];
        getHistoricos().then(()=>{
            $appStore.setGlobalLoading(false);
            loading.value = false;
        });
     }

     const historicosEmpty = computed(()=>{
        return historicos.value.length <= 0;
     })

     const loadMoreData  = ()=>{
        page.value++
        getHistoricos();
     }

     const onExportExcel = async()=>{
        const data = await RegistroController.exportExcel(id,filters.value);
        const url = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'historicos.xlsx');
        document.body.appendChild(link);
        link.click();
     }
</script> 

<template>
            <Breadcrumbs :parentTitle="title"></Breadcrumbs>
            <div class="flex justify-end">
                <BaseBtn
                     @click="onExportExcel"
                    class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    
                >
                    <i class="fas fa-file-excel mr-2"></i> Exportar a Excel
                </BaseBtn>
            </div>
            <div class="flex justify-end items-center mt-3">
                    <label class="text-gray-600 mr-2">Desde:</label>
                    <input type="datetime-local" v-model="filters.startDate" class="border rounded px-2 py-1">
                    <label class="text-gray-600 ml-2 mr-2">Hasta:</label>
                    <input type="datetime-local" v-model="filters.endDate" class="border rounded px-2 py-1">
                    <BaseBtn @click="applyFilters" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Aplicar Filtros
                    </BaseBtn>
             </div>

            <div class="w-full mt-5 h-screen max-h-[70vh]	overflow-y-auto px-5	 ">
                <div
                    class="w-full bg-white p-8 rounded-md shadow-md"
                    v-if="historicosEmpty && !loading"
                >
            
                    <p class="text-gray-600 text-center">
                        No se encontro ningun historico asociado al componente
                    </p>
                </div>
                <div v-else>
                    <div v-for="(item, index) in historicosFormatted" :key="index" class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl py-5">
                    <div class="flex">
                    </div>
                    <div class="flex pl-2 flex-1">
                        <div class="flex flex-grow flex-col self-center justify-between lg:items-center lg:flex-row">
                            <a class="hover:text-purple-500" href="">
                                {{item.fechaHora}}</a>
                            <p>{{item.marca}} {{ item.unidad }}</p>
                            <p class="mr-2 text-gray-500">{{item.proceso}} </p>
                            <p class="mr-2 text-gray-500">{{item.etapa}} </p>

                            
                        </div>
                    </div>
                     </div>
                     <div class="flex justify-center mt-2">
                            <infinite-loading
                                @infinite="loadMoreData"
                                v-if="hasMoreData"
                            ></infinite-loading>
                      </div>
                </div>
               
            </div>
             
</template>