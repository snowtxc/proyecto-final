<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

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

// Función para agregar datos al array de series
const addDataPoint = () => {
    const currentTime = new Date().getTime();
    const newDataPoint = {
        x: currentTime,
        y: Math.floor(Math.random() * 100), // Ejemplo: datos aleatorios
    };
    chartSeries.value[0].data.push(newDataPoint);
};

// Configuración del temporizador para actualizar los datos cada segundo
const updateInterval = setInterval(addDataPoint, 1000);

// Detener el temporizador cuando el componente se desmonta
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