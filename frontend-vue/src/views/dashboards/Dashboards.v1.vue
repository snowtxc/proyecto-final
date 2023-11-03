<script setup>


import { onBeforeMount ,onMounted,onUnmounted,ref } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import EstadisticaController from "@/services/EstadisticaController";
import { appStore } from '@/store/app';
import CardItemCount from '../../components/Cards/CardItemCount.vue';
import { randomColor } from "@/shared/helpers/randomColor";  
import ChartPie from '@/components/Charts/ChartPie.vue';
import ChartBarActividadProcesos from '@/components/Charts/ChartBarActividadProcesos.vue';

import { userAddedChannel , procesoAddedChannel, tipoComponenteAddedChannel, componenteAddedChannel , userDeletedChannel , procesoDeletedChannel, tipoComponenteDeletedChannel, componenteDeletedChannel}  from "@/shared/helpers/channels";

const $appStore = appStore();

const rowsCounts = ref([
    {
        title: 'Usuarios',
        count: 0,
        icon: 'fa-solid fa-users'
    },
    {
        title: 'Procesos',
        count: 0,
        icon: 'fa-solid fa-industry'
    },
    {
        title: 'Componentes',
        count: 0,
        icon: 'fa-solid fa-plug-circle-minus'
    },
    {
        title: 'Tipos de Componentes',
        count: 0,
        icon: 'fa-solid fa-plug-circle-minus '
    }
]);

const chartOptionsComponenteProcesos = ref({
    series: [],
    chartOptions: {
        labels: [],
        fill: {
            colors: [],
        },
        chart: {
            height: 350,
            type: 'pie',
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        responsive: [
         {
            breakpoint: 480,
             options: {
               chart: {
                width: 200
              },
              legend: {
                show: false,
              },
        }} ],
    },
});

const chartOptionsEtapaProcesos = ref({
    series: [],
    chartOptions: {
        labels: [],
        fill: {
            colors: [],
        },
        chart: {
            height: 350,
            type: 'pie',
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        responsive: [
         {
            breakpoint: 480,
             options: {
               chart: {
                width: 200
              },
              legend: {
                show: false,
              },
        }} ],
    },
});



const formatChartOptionsComponentesProceso = (procesos)=>{
    procesos.map(proceso  =>{
        chartOptionsComponenteProcesos.value.series.push(proceso.CantidadNodos);
        chartOptionsComponenteProcesos.value.chartOptions.labels.push(proceso.Nombre);
        chartOptionsComponenteProcesos.value.chartOptions.fill.colors.push(randomColor())

    })

}

const formatChartOptionsEtapasProceso = (procesos)=>{
    procesos.map(proceso  =>{
        console.log(proceso)
        chartOptionsEtapaProcesos.value.series.push(proceso.CantidadEtapas);
        chartOptionsEtapaProcesos.value.chartOptions.labels.push(proceso.Nombre);
        chartOptionsEtapaProcesos.value.chartOptions.fill.colors.push(randomColor())
    })

    console.log(chartOptionsEtapaProcesos.value)

}


onBeforeMount(async()=>{
    $appStore.setGlobalLoading(true);
    const [counts, result ]  = 
        await Promise.all([
             Promise.all([EstadisticaController.countUsers(), EstadisticaController.countProcesos() , EstadisticaController.countComponentes(), EstadisticaController.countTipoComponentes()]),
             Promise.all([EstadisticaController.countComponentePorProceso(), EstadisticaController.countEtapasPorProceso()])
    ]);

    formatChartOptionsComponentesProceso(result[0]);
    formatChartOptionsEtapasProceso(result[1]);

    rowsCounts.value =  rowsCounts.value.map((rowCount,index)=>{
        return{
            ...rowCount,
            count: counts[index]
        }
    })

    $appStore.setGlobalLoading(false);

})

onMounted(()=>{
      window.Echo.channel(userAddedChannel()).listen('userAdded', ()=>{
            rowsCounts.value[0].count ++;
      });

      window.Echo.channel(procesoAddedChannel()).listen('procesoAdded', ()=>{
            rowsCounts.value[1].count ++;
      });

      window.Echo.channel(componenteAddedChannel()).listen('componenteAdded', ()=>{
            console.log('hola como andas!!!');
            rowsCounts.value[2].count ++;
      });

      window.Echo.channel(tipoComponenteAddedChannel()).listen('tipoComponenteAdded', ()=>{
            rowsCounts.value[3].count ++;
      });

      ///

      window.Echo.channel(userDeletedChannel()).listen('userDeleted', ()=>{
            rowsCounts.value[0].count --;
      });

      window.Echo.channel(procesoDeletedChannel()).listen('procesoDeleted', ()=>{
            rowsCounts.value[1].count --;
      });

      window.Echo.channel(componenteDeletedChannel()).listen('componenteDeleted', ()=>{
            rowsCounts.value[2].count --;
      });

      window.Echo.channel(tipoComponenteDeletedChannel()).listen('tipoComponenteDeleted', ()=>{
            rowsCounts.value[3].count --;
      });


})

onUnmounted(()=>{
      window.Echo.leave(userAddedChannel());
      window.Echo.leave(procesoAddedChannel());
      window.Echo.leave(componenteAddedChannel());
      window.Echo.leave(tipoComponenteAddedChannel());
});


</script>

<template>
    
    <div class="container mx-auto">
        <Breadcrumbs parentTitle="Dashboard" subParentTitle="Dashboard v1" />
        <div class="grid grid-cols-12 gap-5">
            <div v-for="(itemCount,index) in rowsCounts" :key="index"
                class="
                    col-span-12
                    xl:col-span-3
                    lg:col-span-6
                    md:col-span-6
                    sm:col-span-6
                "
            >
                <CardItemCount :title="itemCount.title" :count="itemCount.count" :icon="itemCount.icon"></CardItemCount>
                
            </div>
            
            <div class="col-span-12 flex gap-3 flex-wrap">
                <ChartPie class="flex-1"  title="Cantidad de Componentes por Proceso" :options="chartOptionsComponenteProcesos"></ChartPie>
                <ChartPie class="flex-1" title="Cantidad de Etapas por Proceso" :options="chartOptionsEtapaProcesos"></ChartPie>
            </div>

            <div class="col-span-12">
                <ChartBarActividadProcesos></ChartBarActividadProcesos>
            </div>
            
            <div class="col-span-12">
                <BaseCard>
                    <template v-slot:cardHeader>
                        <div class="card-header">
                            <div class="card-title py-3">Ultimos registros de componentes</div>
                        </div>
                    </template>
                    <div
                        class="
                            block
                            w-full
                            overflow-x-auto
                            whitespace-nowrap
                            borderless
                            hover
                        "
                    >
                        <div
                            class="
                                dataTable-wrapper dataTable-loading
                                no-footer
                                fixed-columns
                            "
                        >
                            <div
                                class="
                                    dataTable-container
                                    block
                                    w-full
                                    overflow-x-auto
                                    whitespace-nowrap
                                    borderless
                                    hover
                                "
                            >
                                <table
                                    class="
                                        table-3
                                        dataTable-table
                                        max-w-full
                                        w-full
                                    "
                                >
                                    <thead>
                                        <tr class="">
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Order Id
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Buyer Name
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Product
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Status
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Shipping Cost
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Date
                                            </th>
                                            <th
                                                class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                "
                                            >
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            class="
                                                hover:bg-gray-100
                                                cursor-pointer
                                            "
                                            v-for="(n, index) in 8"
                                            :key="index"
                                        >
                                            <td class="text-xs py-5 px-4">
                                                {{ index + 1 }}
                                            </td>

                                            <td class="text-xs">
                                                Jhon {{ index + 1 }}
                                            </td>
                                            <td class="py-5">
                                                <div class="flex">
                                                    <img
                                                        class="
                                                            w-9
                                                            h-9
                                                            rounded-full
                                                            mr-2
                                                        "
                                                        src="/images/products/speaker-1.jpg"
                                                        alt=""
                                                    />
                                                    <img
                                                        class="
                                                            w-9
                                                            h-9
                                                            rounded-full
                                                            mr-2
                                                        "
                                                        src="/images/products/headphone-1.jpg"
                                                        alt=""
                                                    />
                                                </div>
                                            </td>
                                            <td class="py-5">
                                                <span
                                                    class="
                                                        px-3
                                                        py-1
                                                        rounded-full
                                                        text-primary
                                                        border border-primary
                                                        mr-3
                                                        text-xs
                                                    "
                                                    >Delivered</span
                                                >
                                            </td>
                                            <td class="py-5">
                                                {{ 3.34 * index + 1 }}%
                                            </td>
                                            <td class="py-5">12-02-20</td>
                                            <td class="py-5">
                                                <BaseBtn
                                                    rounded
                                                    class="
                                                        border border-primary
                                                        text-primary
                                                        hover:bg-primary
                                                        hover:text-white
                                                    "
                                                >
                                                    View
                                                </BaseBtn>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="dataTable-bottom">
                                <div class="dataTable-info">
                                    Showing 1 to 8 of 8 entries
                                </div>
                                <nav class="dataTable-pagination">
                                    <ul class="dataTable-pagination-list"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </BaseCard>
            </div>
            
        </div>
    </div>
</template>
