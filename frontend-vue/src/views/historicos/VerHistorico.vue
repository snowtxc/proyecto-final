<script setup>
     import { computed, ref ,onBeforeMount, onMounted ,onBeforeUnmount}  from "vue";
     import ModalLoading from "@/components/Modals/ModalLoading.vue";
     import dayjs from "dayjs";
     import { useRoute } from "vue-router";
     import   RegistroController  from "../../services/RegistroController";
     import { appStore } from "../../store/app";
     import InfiniteLoading from 'v3-infinite-loading'
     import 'v3-infinite-loading/lib/style.css';
     import Breadcrumbs from "../../components/Breadcrumbs.vue";
     import ComponenteController from "../../services/ComponenteController";
     import MiniPanelDevice from "@/components/Panel/MiniPanelDevice.vue";
    
     import { useNotification } from '@kyvg/vue3-notification'
     import spinner from "../components/spinner/spinner.vue";

    import PerfectScrollbar from 'perfect-scrollbar';
    import 'perfect-scrollbar/css/perfect-scrollbar.css';

     const { notify } = useNotification()

     const route = useRoute();
     const $appStore = appStore();
     const { id } = route.params;
     const  historicos = ref([]);

     const filters = ref({
         startDate: null,
         endDate: null,
         unidadId: null
     });

     const page = ref(1);
     const maxRows = 10;
     const loading = ref(true);
     const hasMoreData = ref(true);
     const title = ref("");
     const filterActive = ref(false);
     const countRegistros = ref(0);
     const componenteInfo = ref(null);
     const generatingExcel = ref(false);   

     const channel = `componente.${id}.update-registros`;


     onMounted(()=>{
        window.Echo.channel(channel).listen('appendRegistrosDevice', (nuevosRegistros) => {   
            appendNewRows(nuevosRegistros)
        });

        const container = document.getElementById('scrollContainer');
        new PerfectScrollbar(container);

     })
     
     onBeforeUnmount(()=>{
        window.Echo.leave(channel);
     })


     const clearFilters = ()=>{
        filters.value.startDate = null;
        filters.value.endDate = null;
        filters.value.unidadId = null;
     }

     const appendNewRows  = (nuevosRegistros)=>{
            countRegistros.value +=  nuevosRegistros.length;
            if(filterActive.value){
                    notify({
                        title: '',
                        text:  `Se ha agregado nuevos ${countRegistros.value} registros al historico , desactiva el filtro para visualizarlo` ,
                        type: 'warn',
                    })
                    return;
            }
            notify({
                title: 'Nuevos Registros',
                text:  `Se ha agregado nuevos registros al historico del dispositivo` ,
                type: 'warn',
            })

            setTimeout(()=>{
               
                nuevosRegistros.map(row =>{
                    console.log(row);
                    const { Marca, created_at,unidad,etapa} = row;
                    const  newRow = {
                        "fechaHora" : dayjs(created_at).format("DD/MM/YYYY hh:mm a"),
                        "marca" : Marca,
                        "unidad": unidad.unidad,    
                        "unidadNombre" : unidad.nombre,
                        
                        "proceso" : etapa.proceso.Nombre,
                        "etapa" : etapa.Nombre
                    }
                    historicos.value.unshift(newRow);
                    })
                $appStore.setGlobalLoading(false)

            }, 500)
     }

     const historicosFormatted = computed(()=>{
            return historicos.value.map(historico => {
                const { fechaHora ,unidad ,unidadNombre} = historico;
                return {
                    ...historico,
                    fechaHora: dayjs(fechaHora).format('DD/MM/YYYY HH:mm A'),
                    unidad: `${unidad} (${unidadNombre})`,

                }
            })
     })
    onBeforeMount(async()=>{
        $appStore.setGlobalLoading(true);
        const  [ componenteData ]  =  await Promise.all([ComponenteController.getById(id) ,getHistoricos()]);
        componenteInfo.value = componenteData;
        title.value  = `Histórico del componente "${componenteData.Nombre}""`;
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
            filterActive.value = true;
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
        generatingExcel.value = true;
        const data = await RegistroController.exportExcel(id,filters.value);
        const url = window.URL.createObjectURL(new Blob([data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'historicos.xlsx');
        document.body.appendChild(link);
        link.click();
        generatingExcel.value = false;

     }

     const removeFilter = ()=>{
        historicos.value = [];
        filterActive.value = false;
        countRegistros.value = 0;
        page.value = 1;
        clearFilters();
        $appStore.setGlobalLoading(true);
        getHistoricos().then(()=>{
            $appStore.setGlobalLoading(false);

        })
     }

     const onSelectUnidadDeMedida = (e)=>{
            const value = e.target.value;
            filters.value.unidadId = value;
     }
</script> 

<template>
            <Breadcrumbs :parentTitle="title"></Breadcrumbs>
            <div class="flex justify-center">
                    <spinner v-if="loading"></spinner>
                    <MiniPanelDevice v-if="!loading && componenteInfo" :dispositivoData="componenteInfo" ></MiniPanelDevice>
            </div>

            <BaseCard class="w-full">
                <div class="flex justify-end">
                <BaseBtn
                     @click="onExportExcel"
                    class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    
                >
                    <i class="fas fa-file-excel mr-2"></i> Exportar a Excel
                </BaseBtn>
            </div>
            <div class="flex justify-end gap-2 items-center mt-3">
                     <div class="w-full flex justify-end">
                        <div>
                            <p>Unidad de medida seleccionada</p>
                            <select
                                v-if="componenteInfo"
                                id="small"
                                class="p-2 mb-6 text-sm text-gray-900 brounded-lg border border-gray-400 min-w-[400px]"
                                @change="onSelectUnidadDeMedida"
                            >
                                <option
                                    :value="0"
                                >
                                    <div class="p-5">  
                                        Todas
                                    </div>
                                </option>

                                <option
                                    v-for="unidad in componenteInfo.unidades"
                                    :key="unidad.unidad_id"
                                    :value="unidad.unidad_id"
                                >
                                    <div class="p-5">
                                        {{ unidad.nombre }} 
                                    </div>
                                </option>
                            </select>
                        </div>
                    </div>
                    <label class="text-gray-600 mr-2">Desde:</label>
                    <input type="datetime-local" v-model="filters.startDate" class="border rounded px-2 py-1">
                    <label class="text-gray-600 ml-2 mr-2">Hasta:</label>
                    <input type="datetime-local" v-model="filters.endDate" class="border rounded px-2 py-1">
                    <BaseBtn @click="applyFilters" class="bg-blue-500 text-white px-3 py-1 rounded">
                        Aplicar Filtros
                        <i class="fa-solid fa-filter"></i>
                    </BaseBtn>
                    <BaseBtn bgColor="bg-red-600" v-if="filterActive" @click="removeFilter">
                        Quitar filtro
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                    </BaseBtn>
             </div>
             <BaseCard class="mt-5">
                <div class="w-full mt-5 h-screen max-h-[70vh] overflow-y-auto px-5 transition-all animate-fade-in"
                    id="scrollContainer">
                <div
                    class="w-full bg-white p-8 rounded-md shadow-md"
                    v-if="historicosEmpty && !loading"
                >
                
                    <h2 class="text-2xl font-semibold mb-4">
                        No se encontró histórico
                    </h2>
                    <p class="text-gray-600">
                        No hay ningún registro asociado a este dispositivo
                    </p>
            
                </div>
                <div v-else>
                    <div v-for="(item, index) in historicosFormatted" :key="index" 
                        class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl py-5">
                    
                    <div class="historicoItem flex pl-2 flex-1 transition-all animate-fade-in">
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
            </BaseCard>
            </BaseCard>

            <ModalLoading text="Generando excel de historicos..." v-if="generatingExcel"></ModalLoading>
            
            
            
             
</template>

<style scoped>
        @keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-out;
}

</style>