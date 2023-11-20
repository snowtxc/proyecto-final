<template>
  <div class="h-[750px] w-full ">
    <Breadcrumb :parentTitle=titulo />
    <div class="h-full w-full flex flex-col md:flex-row justify-between ">
      <div class="h-full md:w-1/4 p-4 space-y-4">
        <p class="font-bold text-xl mb-4">
          Dispositivos
        </p>
        <div class="flex flex-col items-center w-full">
          <spinner :show="showSpinnerComponentes"></spinner>
        </div>
        <div id="scrollContainer" v-if="listaComponentes.length > 0" class="space-y-4 max-h-72 md:max-h-[670px] overflow-y-auto w-full" :style="{ 'pointer-events': disableInteractions ? 'none' : 'auto' }">
          <CardDevice v-for="item in listaComponentes" :key="item.id" :nombre="item.Nombre" :ipAddress="item.DireccionIp"
            :value="25" :image="item.tipoComponenteImage" id="agregarNodoButton" @click="cardClicked(item)" :selected="false">
          </CardDevice>
        </div>
        <div v-else>
          <p class="w-full text-center mt-4">No hay dispositivos disponibles.</p>
        </div>
      </div>
      <Card class="h-[1000px] md:h-full md:w-3/4 mb-4">
        <Diagrama :proceso-id="parseInt(procesoId)" :etapa-id="parseInt(etapaId)" :read-only="false" :reload="reloadDiagram" @nodo-borrado="cargarListaComponentes" @info-nodo="setNodeData" @Loading="showSpinner = true"></Diagrama>
      </Card>

    </div>
    <MiniPanelDevice v-if="dispositivoData != null && !showSpinner" :dispositivo-data="dispositivoData"/>
    <div class="w-full" v-else-if="dispositivoData == null && !showSpinner">
      <p class="text-center mt-4">Selecciona un dispositivo para ver su informacion.</p>
    </div>
    <div class="w-full flex justify-center" v-if="showSpinner != false">
      <spinner :show="showSpinner"></spinner>
    </div>
  </div>
</template>
<script setup>
import Breadcrumb from '@/components/Breadcrumbs.vue';
import { ref, onBeforeMount, watch } from 'vue';
import { useRoute } from 'vue-router';
import Card from '@/components/Card/Card.vue';
import ComponenteController from '../../services/ComponenteController';
import NodoController from '../../services/NodoController';
import { appStore } from "@/store/app.js";
import CardDevice from '../../components/Cards/CardDevice.vue'
import spinner from '../components/spinner/spinner.vue';
import Diagrama from '../../components/Diagrama/Diagrama.vue';
import MiniPanelDevice from '../../components/Panel/MiniPanelDevice.vue';
import EtapaController from '../../services/EtapaController';
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

const $appstore = appStore();

const procesoId = ref(null);
const etapaId = ref(null);
const route = useRoute();
const listaComponentes = ref([]);
const showSpinnerComponentes = ref(false);
const reloadDiagram = ref(0)
const listaComponentesPromise = ComponenteController.listDispositivosSinNodo();
const disableInteractions = ref(false);
const nodeData = ref()
const dispositivoData = ref(null)
const titulo = ref("");

const showSpinner = ref(false)
const setNodeData = (data) => {
  nodeData.value = data;
};

watch(nodeData, () => {
  //showSpinner.value = true;
  const data = ComponenteController.getById(nodeData.value.mensaje.componente_id)
    .then((response) => {
      dispositivoData.value = response
      showSpinner.value = false;
    })
    .catch((error) => {
      console.error('Error al crear el enlace:', error);
    });
});

$appstore.setGlobalLoading(true)

listaComponentesPromise
  .then((response) => {
    listaComponentes.value = response
    $appstore.setGlobalLoading(false)
    setTimeout(() => {
        const container = document.getElementById('scrollContainer');
        new PerfectScrollbar(container);
    }, 0);
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
  EtapaController.buscarEtapa(route.params.etapaId).then((response) =>{
    console.log(response);
    if(response.status == 200){
      titulo.value = 'Etapa ' + response.data.Nombre + ' del proceso ' + response.data.proceso;
    }
    else{
      titulo.value = 'Etapa';
    }
  })
  window.Echo.channel('update-nodo-position.' + etapaId.value + '.' + 'procesoId.value').listen('Hello', (e) => {
    console.log(e);
  });
  
})

</script>