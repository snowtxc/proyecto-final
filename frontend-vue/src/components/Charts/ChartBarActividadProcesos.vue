<script setup>
    import { ref, onBeforeMount , onMounted , onUnmounted} from "vue";
    import spinner from "../../views/components/spinner/spinner.vue";
    import EstadisticaController from "@/services/EstadisticaController";
    import { updateActivityProcessesChannel } from "@/shared/helpers/channels";
    const  series =  ref([{
            name: 'Procesos',
            data: []
    }]);

    const chartOptions =  ref({
            annotations: {
              points: [{
                x: 'Bananas',
                seriesIndex: 0,
                label: {
                  borderColor: '#775DD0',
                  offsetY: 0,
                  style: {
                    color: '#fff',
                    background: '#775DD0',
                  },
                  text: 'Bananas are good',
                }
              }]
            },
            chart: {
              height: 350,
              type: 'bar',
            },
            plotOptions: {
              bar: {
                borderRadius: 10,
                columnWidth: '50%',
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              width: 2
            },
            
            grid: {
              row: {
                colors: ['#fff', '#f2f2f2']
              }
            },
            xaxis: {
              labels: {
                rotate: -45
              },
              categories: [
              ],
              tickPlacement: 'on'
            },
            yaxis: {
              title: {
                text: 'Actividad',
              },
            },
            fill: {
              type: 'gradient',
              gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.85,
                opacityTo: 0.85,
                stops: [50, 0, 100]
              },
            }
          },
    );

    const loading = ref(true);

    onMounted(()=>{
      window.Echo.channel(updateActivityProcessesChannel()).listen('UpdateProcessesActivity', (data)=>{
         formatOptions(data)
      });
    })

    
    onBeforeMount(()=>{
        EstadisticaController.getActivityForProcessLastHour().then(data =>{
            formatOptions(data);
            loading.value = false;
        })
    })

    onUnmounted(()=>{
      window.Echo.leave(updateActivityProcessesChannel()); 
    })

    const formatOptions = (procesos)=>{
           series.value[0].data = [];
           chartOptions.value.xaxis.categories = [];
           procesos.map(proceso =>{
              series.value[0].data.push(proceso.CantidadRegistros);
              chartOptions.value.xaxis.categories.push(proceso.Nombre);
           });
    }


</script>

<template>
    
    <BaseCard>
       <p>Actividad de procesos en la ultima hora</p>
        <div class="w-full">
        <div class="flex justify-center" v-if="loading">
            <spinner :show="loading"></spinner>
        </div>
        <apexchart type="bar" height="350" :options="chartOptions" :series="series" v-else></apexchart>
       </div>
    </BaseCard>
   
</template>