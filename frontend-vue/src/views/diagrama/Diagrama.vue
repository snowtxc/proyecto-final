<template>
  <div class="flex flex-row w-auto ">
    <div class="flex flex-col w-full mr-2">
      <select id="small"
        class="w-80 p-2 mb-2 -mt-8 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none"
        v-model="selectedProcess">
        <option value="" disabled selected>
          Selecciona un proceso
        </option>
        <option v-for="proceso in listaProcesos" :key="proceso.id" :value="proceso.id">
          {{ proceso.Nombre }}
        </option>
      </select>
      <Card class="h-[640px] w-full">
        <div v-if="listaEtapas && listaEtapas.length > 0"
          class="flex items-center md:items-start flex-col h-[600px] w-full md:w-auto overflow-y-scroll md:flex md:flex-row space-y-6 md:space-y-0 ">
          <div v-for="etapa in listaEtapas" :key="etapa.id" class="h-[2000px] md:h-[550px] w-5/6 md:w-[600px] md:ml-2">
            <p class="">{{ etapa.Nombre }}</p>
            <div class="h-[250px] md:h-full w-full md:w-[600px] border border-gray-300 rounded-xl">
              <Diagrama :etapa-id="etapa.id" :proceso-id="etapa.proceso_id" :readOnly="true" @info-nodo="setNodeData">
              </Diagrama>
            </div>
          </div>
        </div>
        <p v-else class="text-center mt-4">Selecciona un proceso para ver sus etapas.</p>
      </Card>
      <Card class=" h-auto w-full  mt-2 mb-2" :style="{ 'pointer-events': 'none' }">
        <p class="text-xl font-semibold mr-2">Informacion del dispositivo </p>
        <div v-if="showSpinner == false" class="h-auto w-full flex flex-row space-x-4" :style="{ 'pointer-events': 'none' }">
          <div class="w-2/6" v-if="dispositivoData.length != 0">
            <CardDevice :nombre="dispositivoData.Nombre" :value="25" :image="dispositivoData.tipoComponenteImage"
              :selected="false" :ipAddress="dispositivoData.DireccionIp" />
          </div>
          <div class="w-2/6" v-if="dispositivoData.length != 0" :unidad="unidad">
            <ChartLine />
          </div>
          <div class="w-2/6" v-if="dispositivoData.length != 0">
            <ChartBar :componente_id="dispositivoData.id" :unidad="unidad" />
          </div>
          <div class="w-full" v-else v-if="showSpinner == false">
            <p class="text-center mt-4">Selecciona un dispositivo para ver su informacion.</p>
          </div>
        </div>
          <div class="w-full" v-if="showSpinner != false">
            <spinner :show="showSpinner"></spinner>
          </div>
        
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, defineEmits } from 'vue';
import { appStore } from "@/store/app.js";
import ProcesoController from '@/services/ProcesoController.js';
import EtapaController from '@/services/EtapaController.js';
import ComponenteController from '../../services/ComponenteController';
import Diagrama from '../../components/Diagrama/Diagrama.vue';
import Card from '@/components/Card/Card.vue';
import CardDevice from '../../components/Cards/CardDevice.vue';
import ChartLine from '@/components/Charts/ChartLine.vue';
import ChartBar from '@/components/Charts/ChartBar.vue';
import spinner from '../components/spinner/spinner.vue';

const $appstore = appStore();
const userData = $appstore.getUserData
const listaProcesosPromise = ProcesoController.getProcesosByUser(userData.id);

const listaProcesos = ref([]);
const selectedProcess = ref("");
const listaEtapas = ref([]);
const nodeData = ref()
const dispositivoData = ref([])
const showSpinner = ref(false)
const emits = defineEmits();

const setNodeData = (data) => {
  nodeData.value = data;
};

const unidad = {
  unidad_id: 1,
  nombre: dispositivoData.Unidad
}

watch(nodeData, () => {
  showSpinner.value = true;
  const data = ComponenteController.getById(nodeData.value.mensaje.componente_id)
    .then((response) => {
      dispositivoData.value = response
      unidad.nombre = response.Unidad
      showSpinner.value = false;
    })
    .catch((error) => {
      console.error('Error al crear el enlace:', error);
    });
});

$appstore.setGlobalLoading(true)
listaProcesosPromise
  .then((response) => {
    listaProcesos.value = response.reverse()
    $appstore.setGlobalLoading(false)
  })
  .catch((error) => {
    console.error('Error al obtener la lista de procesos:', error);
    $appstore.setGlobalLoading(false)
  });

watch(selectedProcess, async (newVal) => {
  if (newVal) {
    try {
      EtapaController.listaEtapas(newVal)
        .then((response) => {
          listaEtapas.value = response.data;
        })
        .catch((error) => {
          $appstore.setGlobalLoading(false)
        });
    } catch (error) {
      console.error("Error al obtener las etapas:", error);
    }
  } else {
    listaEtapas.value = [];
  }
});
</script>

<style>
.drop-shadow {
  filter: drop-shadow(3px 3px 5px rgba(0, 0, 0, 0.5));
}
</style>
