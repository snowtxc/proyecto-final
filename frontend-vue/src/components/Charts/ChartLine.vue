<script setup>
import { ref, onMounted, onUnmounted,defineProps } from 'vue';
import { appendRegistrosDeviceChannel } from '../../shared/helpers/channels';


const props =  defineProps({
     componente_id: { type: Number, required: true},
     unidad: {type: Object, required: true}
  });



const chartOptions = ref({
    chart: {
        id: 'real-time-chart',
        animations: {
            enabled: true,
            easing: 'linear',
            dynamicAnimation: {
                speed: 1000,
            },
        },
    },
    xaxis: {
        type: 'datetime',
    },
});

const chartSeries = ref([
    {
        name: 'Load Average',
        data: [],
    },
]);

const addDataPoints = (registros) => {
    registros.map(registro =>{
      const { Marca,created_at } = registro;
      const newDataPoint = {
        x: created_at,
        y: Marca 
        };
       chartSeries.value[0].data.push(newDataPoint);

    });
    
};


onMounted(()=>{
    window.Echo.channel(appendRegistrosDeviceChannel(props.componente_id)).listen('appendRegistrosDevice', (newRegistros)=>{
      const registrosByUnidad = newRegistros.filter(registro => registro.unidad.id == 1);
      addDataPoints(registrosByUnidad);
    });
});


onUnmounted(() => {
    clearInterval(updateInterval);
});
</script>


<template>
    <BaseCard class="overflow-hidden flex-1">
        <div class="p-5">
            <p class="text-primary text-2xl m-0">25 °C</p>
        </div>
        <div id="basicArea-chart">
            <apexchart
        type="line"
        :options="chartOptions"
        :series="chartSeries"
        width="500"
    />
        </div>
    </BaseCard>
       
</template>