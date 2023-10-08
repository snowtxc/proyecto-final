<template>
    <div class="w-full h-screen">
        <BaseCard>
            <div class="flex flex-col">
                <div class="flex justify-end">
                    <div class="flex gap-4">
                        <button
                            @click="
                                $router.push({
                                    name: 'editarDispositivo',
                                    params: { id: props.deviceInfo.id },
                                })
                            "
                            class="flex justify-center items-center px-4 py-2 bg-blue-500 text-white rounded"
                        >
                            <i class="fas fa-pencil-alt mr-2"></i>
                        </button>

                        <button
                            class="flex justify-center items-center px-4 py-2 bg-red-500 text-white rounded"
                            @click="onDelete"
                        >
                            <i class="fas fa-trash-alt mr-2"></i>
                        </button>
                    </div>
                </div>
                <div class="flex gap-3 mt-3">
                    <BaseCard class="overflow-hidden flex-2">
                        <div class="p-5">
                            <div class="text-gray-500">
                                {{ props.deviceInfo.Nombre }}
                            </div>
                            <p class="text-primary text-2xl m-0">25 °C</p>
                        </div>
                        <div id="basicArea-chart">
                            <apexchart
                                type="area"
                                height="270"
                                :options="splineAreaWidgetTwo.chartOptions"
                                :series="splineAreaWidgetTwo.series"
                            />
                        </div>
                    </BaseCard>

                    <div class="grid grid-cols-12 w-full gap-1 flex-1">
                        <div class="col-span-6">
                            <BaseCard>
                                <div class="flex align-center">
                                    <i
                                        class="fa-solid fa-temperature-arrow-up text-6xl text-purple-200"
                                    ></i>
                                    <div class="m-auto">
                                        <p class="text-gray-400">
                                            Registro mas alto en las ultimas 24
                                            horas
                                        </p>
                                        <p class="text-xl text-primary">
                                            19 °C
                                        </p>
                                    </div>
                                </div>
                            </BaseCard>
                        </div>
                        <div class="col-span-6">
                            <BaseCard>
                                <div class="flex align-center">
                                    <i
                                        class="fa-solid fa-temperature-arrow-down text-6xl text-purple-200"
                                    ></i>
                                    <div class="m-auto">
                                        <p class="text-gray-400">
                                            Registro mas alto en las ultimas 24
                                            horas
                                        </p>
                                        <p class="text-xl text-primary">
                                            35 °C
                                        </p>
                                    </div>
                                </div>
                            </BaseCard>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <BaseCard>
                        <template v-slot:cardHeader>
                            <div class="card-header flex justify-between">
                                <div class="card-title py-3">Partes</div>
                                <BaseBtn                     
                                                        rounded
                                                        @click="showModalPart = true; actionPart = Action.CREAR"
                                                        class="border border-primary text-primary hover:bg-primary hover:text-white flex items-center"
                                                    >
                                                        Agregar parte
                                                        <i class="fa-solid fa-plus ml-2"></i>
                                                    </BaseBtn>
                            </div>
                        </template>
                        <div
                            class="block w-full overflow-x-auto whitespace-nowrap borderless hover"
                        >
                            <div
                                class="dataTable-wrapper dataTable-loading no-footer fixed-columns"
                            >
                                <div
                                    class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover"
                                >
                                    <div class="flex justify-center w-full" v-if="loadingPartes">
                                            <spinner :show="true"></spinner>
                                    </div>
                                     <div class="w-full flex justify-center"
                                                v-else-if="!loadingPartes && emptyParts">
                                                    <p class="text-gray-600">
                                                        No existe ninguna parte asociada al componente
                                                    </p>
                                     </div>

                                     <div class="max-h-[50vh] overflow-y-auto" v-else>
                                        <table 
                                        class="table-3 dataTable-table max-w-full w-full max-h-[50vh] overflow-y-auto"
                                    >
                                        <thead>
                                            <tr class="">
                                                <th
                                                    class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold"
                                                >
                                                    Fecha
                                                </th>
                                                <th
                                                    class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold"
                                                >
                                                    Nombre de la parte
                                                </th>
                                               
                                                

                                                <th
                                                    class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold "
                                                >
                                                  Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <tr
                                                class="hover:bg-gray-100 cursor-pointer"
                                                v-for="parte in partesFormatted"
                                                :key="parte.id"
                                            >
                                                <td class="text-xs py-5 px-4">
                                                    {{ parte.Fecha}}
                                                </td>

                                                <td class="text-xs">
                                                   {{  parte.Nombre }}
                                                </td>
                                                
                                               

                                              

                                                <td class="py-5">
                                                    <div class="flex  gap-2">
                                                        
                                                        <ModalPartNotas  :parteNombre="parte.Nombre" :componenteId="props.deviceInfo.id" :parteId="parte.id"></ModalPartNotas>
                                                        <button
                                                            @click="editPart(parte)"
                                                            class="flex justify-center items-center px-4 py-2 bg-blue-500 text-white rounded"
                                                        >
                                                            <i
                                                                class="fas fa-pencil-alt mr-2"
                                                            ></i>
                                                        </button>

                                                        <button
                                                            class="flex justify-center items-center px-4 py-2 bg-red-500 text-white rounded"
                                                            @click="deletePart(parte)"
                                                        >
                                                            <i
                                                                class="fas fa-trash-alt mr-2"
                                                            ></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                    </BaseCard>
                </div>
            </div>
        </BaseCard>
    </div>

    <ModalPartForm  v-if="showModalPart" @onProcessed="handleParteModal" :action="actionPart" @onClose="showModalPart = false" :componente_id="props.deviceInfo.id" :partData="partSelected"></ModalPartForm>

    <ConfirmationModal 
    v-if="showModalDeletePart && partSelected" 
    :show="showModalDeletePart" 
    title="Eliminar parte"
     message="Seguro deseas eliminar esta parte?"  
     @cancel="showModalDeletePart = false"
     @confirm="onConfirmDeletePart"></ConfirmationModal>

</template>

<script setup>
import { ref, defineProps, defineEmits  ,onBeforeMount, computed} from 'vue'
import ComponenteController from '../../services/ComponenteController';
import ParteController from "../../services/ParteController";


import ModalPartForm from '../Modals/ModalPartForm.vue';
import ModalPartNotas from '../Modals/ModalPartNotas.vue';


import ConfirmationModal from '../ConfirmationModal.vue';
import { Action } from '../../shared/enums/Action';
import { useNotification } from '@kyvg/vue3-notification';
import spinner from '../../views/components/spinner/spinner.vue';
import * as dayjs from 'dayjs'
import { appStore } from '../../store/app';

import  cutString from "../../shared/helpers/cutString";


const $appStore = appStore();


const props = defineProps({
    deviceInfo: { required: true, type: Object },
})

const { notify } = useNotification()

const emit = defineEmits(['onDelete'])

const deleting = ref(false);

const partes = ref([]);
const loadingPartes = ref(true);
const  showModalPart = ref(false);
const  showModalDeletePart = ref(false);
const  actionPart = ref('');
const partSelected = ref(null);

const splineAreaWidgetTwo = ref({
    series: [
        {
            name: 'series2',
            data: [11, 90, 45, 32, 34, 52, 41],
        },
    ],

    chartOptions: {
        chart: {
            width: '90%',
            height: 100,
            toolbar: {
                show: false,
            },
            sparkline: {
                enabled: true,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
        },
        legend: {
            show: false,
        },

        xaxis: {
            type: 'datetime',
            categories: [
                '2018-09-19T00:00:00',
                '2018-09-19T01:30:00',
                '2018-09-19T02:30:00',
                '2018-09-19T03:30:00',
                '2018-09-19T04:30:00',
                '2018-09-19T05:30:00',
                '2018-09-19T06:30:00',
            ],
            labels: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
        },
        yaxis: {
            show: false,
        },
        grid: {
            show: false,
        },
        tooltip: {
            enabled: true,
            x: {
                format: 'dd/MM/yy HH:mm',
            },
        },
        colors: ['#8b5cf6'],
        stroke: {
            curve: 'smooth',
            width: 1,
        },
    },
});


onBeforeMount(()=>{
     ParteController.list(props.deviceInfo.id).then((partesList) =>{
          partes.value = partesList;
          console.log(partes.value)
          loadingPartes.value = false;
     });
})


const onDelete = async () => {
    const ID = props.deviceInfo.id
    deleting.value = true;
    try {
        const deviceDeleted = await ComponenteController.delete(ID)
        deleting.value = false
        notify({
            title: 'Dispositivo eliminado',
            text: `El dispositivo ${deviceDeleted.Nombre} ha sido eliminado`,
            type: 'success',
        })
        emit('onDelete', ID)
    } catch (e) {
        deleting.value = false
        notify({
            title: 'Error',
            text: `Ocurrio un error al eliminar el dispositivo`,
            type: 'error',
        })
    }
}

const handleParteModal = (parte)=>{
    showModalPart.value = false;
    if(actionPart.value == Action.CREAR){
        partes.value.unshift(parte);
        return;
    }
    const index = partes.value.findIndex(part => part.id == parte.id);
    partes.value[index] = parte;


}
const editPart = (parte) =>{
  actionPart.value = Action.EDITAR;
  partSelected.value = parte;

  showModalPart.value = true;
}

const deletePart = (parte) =>{
    partSelected.value = { ... parte};
    showModalDeletePart.value = true;
}

const onConfirmDeletePart = async()=>{
    if(!partSelected.value){
        return;
    }
    showModalDeletePart.value = false;

    $appStore.setGlobalLoading(true);

    const COMPONENTE_ID = props.deviceInfo.id;
    const PARTE_ID = partSelected.value.id;
    try{
       const partDeleted = await  ParteController.delete(COMPONENTE_ID,PARTE_ID )
       const index = partes.value.findIndex(part => part.id == partDeleted.id);
       partes.value.splice(index,1);
       notify({
        title: 'Parte eliminada',
        text: `La parte ${partDeleted.Nombre} ha sido eliminada`,
        type: 'success'
       });
       $appStore.setGlobalLoading(false);


    }catch(e){
        notify({
            title: 'Error',
            text: `Ocurrio un error al eliminar la parte`,
            type: 'error',
        })
        $appStore.setGlobalLoading(false);

    }
    ParteController.delete
}

const partesFormatted = computed(()=>{
    return partes.value.map(parte =>{
        const { created_at ,Descripcion, ...data} = parte;

        return {
            ...data,
            Fecha: dayjs(created_at).format("DD/MM/YYYY"),
            Descripcion:  Descripcion ? cutString(Descripcion, 50): 'Sin descripcion'
        }
    })
})

const emptyParts = computed(()=>{
    return partes.value.length <= 0;
})

</script>
