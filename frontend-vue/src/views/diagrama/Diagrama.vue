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
      <Card class=" h-[640px] w-full">
        <div v-if="listaEtapas && listaEtapas.length > 0" class="flex flex-row h-[600px] overflow-x-scroll">
          <div v-for="etapa in listaEtapas" :key="etapa.id" class="h-[550px] w-[600px] ml-2">
            <p class=""> {{ etapa.Nombre }}</p>
            <div class="h-full w-[600px] border border-gray-300 rounded-xl">
              <Diagrama :etapa-id="etapa.id" :proceso-id="etapa.procesoId" :readOnly="true"></Diagrama>
            </div>
          </div>
        </div>
      </Card>
      <Card class=" h-[200px] w-full  mt-2">
        <!-- <BaseBtn id="agregarNodoButton">add</BaseBtn>
-->
        <BaseBtn @click="Print">print</BaseBtn>
      </Card>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import go from 'gojs';
import { appStore } from "@/store/app.js";
import ProcesoController from '@/services/ProcesoController.js';
import EtapaController from '@/services/EtapaController.js';
import Diagrama from '../../components/Diagrama/Diagrama.vue';
import ComponenteController from '@/services/ComponenteController.js';
import Card from '@/components/Card/Card.vue';


const $appstore = appStore();
const userData = $appstore.getUserData
const listaProcesosPromise = ProcesoController.getProcesosByUser(userData.id);

const listaProcesos = ref([]);
const selectedProcess = ref("");
const listaEtapas = ref([]);


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
      const response = await EtapaController.listaEtapas(newVal)
        .then((response) => {
          listaEtapas.value = response.data;
        })
        .catch((error) => {
          console.error('Error al obtener la lista de procesos:', error);
          $appstore.setGlobalLoading(false)
        });
    } catch (error) {
      console.error("Error al obtener las etapas:", error);
    }
  } else {
    listaEtapas.value = [];
  }
});

const Print = () => {
  console.log(listaProcesos.value)
  console.log(listaEtapas.value)
  if (listaEtapas.value && listaEtapas.value.length > 0) {
    console.log("Esto esta bien y tiene cosas")
  }
  else {
    console.log("Esto esta bien tambien, pero no tiene cosas")
  }
}
</script>

<style>
.drop-shadow {
  filter: drop-shadow(3px 3px 5px rgba(0, 0, 0, 0.5));
}
</style>
