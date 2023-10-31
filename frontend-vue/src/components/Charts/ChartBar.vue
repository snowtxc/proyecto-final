<template>
    <div>
      <apexchart :options="chartOptions" :series="chartSeries" type="bar" height="350" ref="chart" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const chartOptions = ref({
    chart: {
      id: 'realtime',
      type: 'bar',
      height: 350,
    },
    xaxis: {
      categories: [],
    },
  });
  
  const chartSeries = ref([
    {
      name: 'Series 1',
      data: [],
    },
  ]);
  
  const updateChart = () => {
    const newData = Math.floor(Math.random() * 100) + 1;
  
    chartOptions.value.xaxis.categories.push(new Date().toLocaleTimeString());
    chartSeries.value[0].data.push(newData);
  
    const maxDataPoints = 10;
    if (chartOptions.value.xaxis.categories.length > maxDataPoints) {
      chartOptions.value.xaxis.categories.shift();
      chartSeries.value[0].data.shift();
    }
  
    // Actualiza el componente del gráfico.
    $refs.chart.updateOptions(chartOptions.value);
  };
  
  onMounted(() => {
    // Actualiza el gráfico cada segundo (1000 ms).
    setInterval(updateChart, 1000);
  });
  </script>