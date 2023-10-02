<template>
    <div class="w-full h-screen">
        <BaseCard> 
            <div class="flex flex-col">
                <div class="flex justify-end">
                    <div class="flex gap-4">
                        <button
                            @click="$router.push({name: 'editarDispositivo', params: {id: props.deviceInfo.id}})"
                            class="flex justify-center items-center px-4 py-2 bg-blue-500 text-white rounded"
                        >
                            <i class="fas fa-pencil-alt mr-2"></i> 
                        </button>

                        <button
                            class="flex justify-center items-center px-4 py-2 bg-red-500 text-white rounded"
                            @click="onDelete">
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
            </div>
        </BaseCard>
    </div>
</template>

<script setup>
import { ref, defineProps ,defineEmits} from 'vue'
import ComponenteController from '../../services/ComponenteController';
const props = defineProps({
    deviceInfo: { required: true, type: Object },
})

import { useNotification } from '@kyvg/vue3-notification';
const { notify } = useNotification()

const emit = defineEmits(['onDelete']);

const deleting = ref(false);

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
})


const onDelete = async()=>{
       const ID = props.deviceInfo.id;
       deleting.value = true;
       try{
              const deviceDeleted  = await ComponenteController.delete(ID);
              deleting.value = false
              notify({
                title: 'Dispositivo eliminado',
                text: `El dispositivo ${deviceDeleted.Nombre} ha sido eliminado`,
                type: 'success'
              })
              emit("onDelete", ID);
       }catch(e){
            deleting.value = false
            notify({
                title: 'Error',
                text: `Ocurrio un error al eliminar el dispositivo`,
                type: 'error'
            })
       }
}
</script>
