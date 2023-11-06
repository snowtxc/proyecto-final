<template>
    <div>
      <p>
        Ultimo registro:
      </p>
      <p class="text-primary text-2xl m-0">{{ lastMarca }} </p>
      <apexchart :options="chartOptions" :series="chartSeries" type="bar" height="350" ref="chart" />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted ,onUnmounted, watch,computed} from 'vue';
  import  { defineProps } from "vue";
  import { appendRegistrosDeviceChannel } from "@/shared/helpers/channels";
  import dayjs from 'dayjs';
  
  
  const props =  defineProps({
     componente_id: { type: Number, required: true},
     unidad: {type: Object, required: true},
     unidades: { type: Array, required : true}
  });

  const maxPoints = 20;

  const registrosByUnidades  = ref(props.unidades.map(unidad =>{
    const { unidad_id } = unidad;
    return { unidad_id , registros: []}
  }));

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
      chartOptions.value.xaxis.categories.push(dayjs(created_at).format("DD/MM/YYYY hh:mm a"));
      chartSeries.value[0].data.push(Marca);
      const maxDataPoints = 20;

      if (chartSeries.value[0].data.length > maxDataPoints) {
        chartOptions.value.xaxis.categories.shift();
        chartSeries.value[0].data.shift();
      }
    })  
    
  };


  onMounted(() => {
    window.Echo.channel(appendRegistrosDeviceChannel(props.componente_id)).listen('appendRegistrosDevice', (newRegistros)=>{
      newRegistros.map(newRegistro =>{
        const index =  registrosByUnidades.value.findIndex(registro => registro.unidad_id == newRegistro.unidad.id)
        const { Marca, created_at } = newRegistro;
        registrosByUnidades.value[index].registros.push({created_at, Marca});
        if(registrosByUnidades.value[index].registros.length >  maxPoints){
            registrosByUnidades.value[index].registros.shift();   
        }
        if(props.unidad.unidad_id == newRegistro.unidad_id){
          updateChart([{...newRegistro}]);
        }
      });  
    });

  });

  watch(() => props.unidad, (newUnidadSelected, oldValue) => {
    const  { unidad_id } = newUnidadSelected;
    const unidad  =  registrosByUnidades.value.find(item => item.unidad_id == unidad_id);
    chartOptions.value.xaxis.categories = [];
    chartSeries.value[0].data = [];
    unidad.registros.map(item =>{
      const { Marca, created_at } = item;
      chartOptions.value.xaxis.categories.push(dayjs(created_at).format("DD/MM/YYYY hh:mm a"));
      chartSeries.value[0].data.push(Marca);
    })
  });


  const lastMarca = computed(()=>{
    const unidad  =  registrosByUnidades.value.find(item => item.unidad_id == props.unidad.unidad_id);
    if(unidad.registros.length <= 0 ){
      return "S/A";
    }
    return unidad.registros[unidad.registros.length -1].Marca + " " + unidadSymbol.value;
  });

  const unidadSymbol = computed(()=>{
    return props.unidad.unidad;
  })

  onUnmounted(()=>{
    window.Echo.leave(appendRegistrosDeviceChannel(props.componente_id));
  })

  
  </script> 