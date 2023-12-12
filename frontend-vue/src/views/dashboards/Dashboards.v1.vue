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
        title: 'Dispositivos',
        count: 0,
        icon: 'fa-solid fa-plug-circle-minus'
    },
    {
        title: 'Tipos de dispositivos',
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
                <ChartPie class="flex-1"  title="Cantidad de dispositivos por Proceso" :options="chartOptionsComponenteProcesos"></ChartPie>
                <ChartPie class="flex-1" title="Cantidad de Etapas por Proceso" :options="chartOptionsEtapaProcesos"></ChartPie>
            </div>

            <div class="col-span-12">
                <ChartBarActividadProcesos></ChartBarActividadProcesos>
            </div>
            
           
            
        </div>
    </div>
</template>
