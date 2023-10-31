<script setup>
  import Breadcrumb from '@/components/Breadcrumbs.vue'
  import { ref ,computed} from 'vue'
  import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
  import { appStore } from '@/store/app';
  import Card from '@/components/Card/Card.vue';
  import ProcesoController from '../../services/ProcesoController';
  import InfiniteLoading from 'v3-infinite-loading'
  import 'v3-infinite-loading/lib/style.css';
  import AlarmaController from '../../services/AlarmaController';
  import dayjs from "dayjs";

  const $appStore = appStore();
  const userData = $appStore.userdata;

  const userProfileImage = computed(()=>{
    console.log($appStore.getUserData);
    return $appStore.getUserData?.profileImage;
  })

  const userProfileDefault = computed(()=>{
      return $appStore.getImageProfileDefault;
  })

  const listaProcesos = ref([]);
  const showSpinnerProcesos = ref(true);
  const hasMoreProcesos = ref(true)
  const pageProcesos = ref(1)
  const listaProcesosPromise = ProcesoController.getByUser(userData.id, pageProcesos.value);

  const listaAlarmas = ref([]);
  const showSpinnerAlarmas = ref(true);
  const hasMoreAlarmas = ref(true)
  const pageAlarmas = ref(1)
  const listaAlarmasPromise = AlarmaController.getByUser(userData.id, pageAlarmas.value);
  
  listaProcesosPromise
    .then((response) => {
        listaProcesos.value = response.data.data;
        hasMoreProcesos.value = response.data.last_page > response.data.current_page;
        console.log(hasMoreProcesos.value);
        showSpinnerProcesos.value = false;
    })
    .catch((error) => {
        console.error('Error al obtener la lista de procesos:', error);
        showSpinnerProcesos.value = false;
    });

  listaAlarmasPromise
    .then((response) => {
        listaAlarmas.value = response.data.data;
        hasMoreAlarmas.value = response.data.last_page > response.data.current_page;
        console.log(hasMoreAlarmas.value);
        showSpinnerAlarmas.value = false;
    })
    .catch((error) => {
        console.error('Error al obtener la lista de alarmas:', error);
        showSpinnerAlarmas.value = false;
    });

  const formattedDate = (fechaHora) => {
    return dayjs(fechaHora).format('DD/MM/YYYY HH:mm A');
  }

  const selectCard = (index) => {
    //redirect
  };

</script>

<template>
  <div class="container mx-auto">
    <Breadcrumb parentTitle='Perfil'/>
    <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
        <BaseCard noPadding class="flex items-center w-full">
          <div class="flex flex-col items-center justify-center pb-8">
              <div class="text-center"><img class="relative z-1 w-24 h-24 m-auto rounded-full border-2 border-white object-fill" :src="userProfileImage ? userProfileImage : userProfileDefault" /></div>
              <p class="text-2xl">{{ userData.name }}</p>
              <p class="text-gray-600">{{ userData.rol }}</p>
              <p class="text-gray-600">{{ userData.email }}</p>
              <BaseBtn block class="bg-[#25CEDE] mt-8 text-white rounded-full" >Editar</BaseBtn>
          </div>
        </BaseCard>
      </div>

      <div class="col-span-4">
        <BaseCard class="h-auto">
          <Breadcrumb parentTitle='Procesos' />
          <div class="h-auto max-h- flex flex-col items-center space-y-2 overflow-y-auto p-3 ">
            
            <spinner :show="showSpinnerProcesos"></spinner>

            <Card v-if="listaProcesos.length == 0 && !showSpinnerProcesos"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                    <p class="font-bold text-xl">No tienes procesos asociados
                    </p>
                </div>
            </Card>

            <Card v-for="(proceso, index) in listaProcesos" :key="proceso.id"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full"
                :class="{ 'selected-card': index === selectedCardIndex }" @click="selectCard(index)">
                <div class="flex flex-row items-center justify-between">
                    <p :class="{ 'white-text': index === selectedCardIndex }" class="font-bold text-xl">{{ proceso.Nombre }}
                    </p>
                </div>
            </Card>
            <div class="flex justify-center mt-2">
              <infinite-loading
                @infinite="loadMoreProcesos"
                v-if="hasMoreProcesos"
              ></infinite-loading>
            </div>
          </div>
        </BaseCard>
      </div>

      <div class="col-span-8">
        <BaseCard class="h-auto">
          <Breadcrumb parentTitle='Alarmas' />
          <div class="h-auto max-h- flex flex-col items-center space-y-2 overflow-y-auto p-3 ">
            
            <spinner :show="showSpinnerAlarmas"></spinner>

            <Card v-if="listaAlarmas.length == 0 && !showSpinnerAlarmas"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                    <p class="font-bold text-xl">No has recibido alarmas
                    </p>
                </div>
            </Card>

            <Card v-for="(alarma, index) in listaAlarmas" :key="alarma.id"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                  <div class="flex">
                    <img class="w-10 object-fill" :src="alarma.tipoComponenteImagen" alt="" />
                  </div>
                  <p>{{alarma.componenteNombre}}</p>   
                  <p>{{alarma.tipoComponente}}</p>
                  <p>{{alarma.procesoNombre}}</p>
                  <p>{{ formattedDate(alarma.fechaHora) }}</p>
                </div>
            </Card>
            <div class="flex justify-center mt-2">
              <infinite-loading
                @infinite="loadMoreAlarmas"
                v-if="hasMoreAlarmas"
              ></infinite-loading>
            </div>
          </div>
        </BaseCard>
      </div>

    </div>

</div>
</template>
