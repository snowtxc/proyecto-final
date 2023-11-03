<template>
    <div>
      {{ props.unidad.nombre }}
      <apexchart :options="chartOptions" :series="chartSeries" type="bar" height="350" ref="chart" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted ,onUnmounted} from 'vue';
  import  { defineProps } from "vue";
  import { appendRegistrosDeviceChannel } from "@/shared/helpers/channels";
  
  
  const props =  defineProps({
     componente_id: { type: Number, required: true},
     unidad: {type: Object, required: true}
  });


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
  
  const updateChart = (registros) => {
    registros.map(registro =>{
      const { Marca,created_at } = registro;
      chartOptions.value.xaxis.categories.push(created_at);
      chartSeries.value[0].data.push(Marca);
      const maxDataPoints = 10;

      if (chartOptions.value.xaxis.categories.length > maxDataPoints) {
        chartOptions.value.xaxis.categories.shift();
        chartSeries.value[0].data.shift();
      }
    })  
    
  };


  onMounted(() => {
    window.Echo.channel(appendRegistrosDeviceChannel(props.componente_id)).listen('appendRegistrosDevice', (newRegistros)=>{
      const registrosByUnidad = newRegistros.filter(registro => registro.unidad.id == props.unidad.unidad_id);
      updateChart(registrosByUnidad);
    });

  });

  onUnmounted(()=>{
    Window.Echo.leave(appendRegistrosDeviceChannel(props.componente_id));
  })
  </script> 