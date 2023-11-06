<template>
  <div class="h-[750px] w-full ">
    <Breadcrumb parentTitle='Procesos' subParentTitle="Etapa" />
    <div class="h-full w-full flex flex-col md:flex-row justify-between ">
      <div class="h-full md:w-1/5 p-4 space-y-4">
        <p class="font-bold text-xl mb-4">
          Dispositivos
        </p>
        <div class="flex flex-col items-center w-full ">
          <spinner :show="showSpinnerComponentes"></spinner>
        </div>
        <div v-if="listaComponentes.length > 0" class="space-y-4 max-h-72 md:max-h-full overflow-y-auto w-full" :style="{ 'pointer-events': disableInteractions ? 'none' : 'auto' }">
          <CardDevice v-for="item in listaComponentes" :key="item.id" :nombre="item.Nombre" :ipAddress="item.DireccionIp"
            :value="25" :image="item.tipoComponenteImage" id="agregarNodoButton" @click="cardClicked(item)" :selected="false">
          </CardDevice>
        </div>
        <div v-else>
          <p class="w-full text-center mt-4">No hay dispositivos disponibles.</p>
        </div>
      </div>
      <Card class="h-[1000px] md:h-full md:w-3/4 mb-4">
        <Diagrama :proceso-id="parseInt(procesoId)" :etapa-id="parseInt(etapaId)" :read-only="false" :reload="reloadDiagram" @nodo-borrado="cargarListaComponentes"></Diagrama>
      </Card>

    </div>
  </div>
</template>
<script setup>
import Breadcrumb from '@/components/Breadcrumbs.vue';
import { ref, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router';
import Card from '@/components/Card/Card.vue';
import ComponenteController from '../../services/ComponenteController';
import NodoController from '../../services/NodoController';
import { appStore } from "@/store/app.js";
import CardDevice from '../../components/Cards/CardDevice.vue'
import spinner from '../components/spinner/spinner.vue';
import Diagrama from '../../components/Diagrama/Diagrama.vue';

const $appstore = appStore();

const procesoId = ref(null);
const etapaId = ref(null);
const route = useRoute();
const listaComponentes = ref([]);
const showSpinnerComponentes = ref(false);
const reloadDiagram = ref(0)
const listaComponentesPromise = ComponenteController.listDispositivosSinNodo();
const disableInteractions = ref(false);


$appstore.setGlobalLoading(true)

listaComponentesPromise
  .then((response) => {
    listaComponentes.value = response
    $appstore.setGlobalLoading(false)
  })
  .catch((error) => {
    console.error('Error al obtener la lista de componentes:', error);
    $appstore.setGlobalLoading(false)
  });

const cargarListaComponentes = () => {
  showSpinnerComponentes.value = true;
  ComponenteController.listDispositivosSinNodo()
    .then((response) => {
      listaComponentes.value = response
      showSpinnerComponentes.value = false;
    })
    .catch((error) => {
      console.error('Error al obtener la lista de dispositivos:', error);
      showSpinnerComponentes.value = false;
    });
};

const createNode = async (id, pos) => {
  const data = {
    Posicion: pos,
    componente_id: id,
    etapa_id: etapaId.value
  }
  try {
    await NodoController.create(data).then(() => {
      reloadDiagram.value++;
      disableInteractions.value = false;
      showSpinnerComponentes.value= false;
    })

    const index = listaComponentes.value.findIndex(componente => componente.id === id);
    if (index !== -1) {
      listaComponentes.value.splice(index, 1);
    }
  } catch (e) {
    console.log(e);
  }
}

const cardClicked = (dataComponente) => {
  showSpinnerComponentes.value= true;
  disableInteractions.value = true;
  createNode(dataComponente.id, "-200 -200")
}

onBeforeMount(() => {
  procesoId.value = route.params.procesoId;
  etapaId.value = route.params.etapaId;
  window.Echo.channel('update-nodo-position.' + etapaId.value + '.' + 'procesoId.value').listen('Hello', (e) => {
    console.log(e);
  })
})

</script>