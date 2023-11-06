<script setup>
      import { onMounted ,defineProps,onBeforeMount, ref, watch} from 'vue';
      import  { updateMarcaLast24HourChannel }  from "@/shared/helpers/channels";
      import spinner from '../views/components/spinner/spinner.vue';
       import ComponenteController from '../services/ComponenteController';

      const loading = ref(true);
      const props =  defineProps({
        componenteId: { type: Number, required: true},
        unidad: { type: Number, required: true},
        unidades: { type :Array,  required: true}
      })
      const min = ref(0);
      const max = ref(0);

      const lastMarcas = ref([]);

      const updateMarca = (marcas)=>{
        const marca = marcas.find(item => item.unidad_id == props.unidad.unidad_id);
        min.value = marca.min;
        max.value = marca.max;
      }

      onBeforeMount(()=>{
            ComponenteController.marcaLast24Hours(props.componenteId).then(marcas =>{
                updateMarca(marcas);
                lastMarcas.value = marcas;
                loading.value = false;
            })
      })

      onMounted(()=>{
        window.Echo.channel(updateMarcaLast24HourChannel(props.componenteId)).listen('updateMarcaLast24Hour', (data) =>{
            console.log(data);
        })
      })

      watch(()=>props.unidad ,(newValue)=>{
         updateMarca();
      })
</script>


<template>
    <div class="flex gap-3 w-full">
        <BaseCard class="flex-1">
        <div class="flex align-center">
            <i
                class="fa-solid fa-temperature-arrow-up text-6xl text-purple-200"
            ></i>
            <div class="m-auto">
                <p class="text-gray-400">
                    Registro mas alto en las ultimas
                    24 horas
                </p>
                <p class="text-xl text-primary">
                    {{ max }}°C
                </p>
            </div>
        </div>
        <div class="flex justify-center">
            <spinner :show="loading" ></spinner>
        </div>

    </BaseCard>

    <BaseCard class="flex-1">

        <div class="flex align-center">
            <i
                class="fa-solid fa-temperature-arrow-up text-6xl text-purple-200"
            ></i>
            <div class="m-auto">
                <p class="text-gray-400">
                    Registro mas bajo en las ultimas
                    24 horas
                </p>
                <p class="text-xl text-primary">
                    {{ min }} °C
                </p>
            </div>
        </div>
        <div class="flex justify-center">
            <spinner :show="loading"></spinner>
        </div>

    </BaseCard>
    </div>
    
</template>