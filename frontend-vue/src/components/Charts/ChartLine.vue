<script setup>
import { ref, onMounted, onUnmounted,defineProps ,watch} from 'vue';
import { appendRegistrosDeviceChannel } from '../../shared/helpers/channels';
import dayjs from 'dayjs';


const props =  defineProps({
     componente_id: { type: Number, required: true},
     unidad: {type: Object, required: true},
     unidades: { type: Array, required: true}
});

const maxPoints = 20;

const registrosByUnidades  = ref(props.unidades.map(unidad =>{
    const { unidad_id } = unidad;
    return { unidad_id , registros: []}
}));
 
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
        newRegistros.map(newRegistro =>{
        const index =  registrosByUnidades.value.findIndex(registro => registro.unidad_id == newRegistro.unidad.id)
        const { Marca, created_at } = newRegistro;
        registrosByUnidades.value[index].registros.push({created_at, Marca});
        if(registrosByUnidades.value[index].registros.length >  maxPoints){
            registrosByUnidades.value[index].registros.shift();   
        }
        if(props.unidad.unidad_id == newRegistro.unidad_id){
            addDataPoints([{...newRegistro}]);
        }
      }); 
    });
});


watch(() => props.unidad, (newUnidadSelected, oldValue) => {
    const  { unidad_id } = newUnidadSelected;
    const unidad  =  registrosByUnidades.value.find(item => item.unidad_id == unidad_id);
    chartSeries.value[0].data = [];
    unidad.registros.map(item =>{
      const { Marca, created_at } = item;

      const dataPoint  = {
        x: created_at,
        y:Marca
      }
      chartSeries.value[0].data.push(dataPoint);
    })
  });


onUnmounted(() => {
    window.Echo.leave(appendRegistrosDeviceChannel(props.componente_id));

});
</script>


<template>
    <div class="overflow-hidden flex-1 w-full ml-5">
        <div class="p-5">
            <p class="text-primary text-2xl m-0">25 °C</p>
        </div>
        <div id="basicArea-chart w-full">
            <apexchart
                type="line"
                :options="chartOptions"
                :series="chartSeries"
                width="700"
            />
        </div>
    </div>
       
</template>