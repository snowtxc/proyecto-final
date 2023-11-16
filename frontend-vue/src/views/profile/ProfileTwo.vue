<script setup>
  import Breadcrumb from '@/components/Breadcrumbs.vue'
  import { ref ,computed, reactive} from 'vue'
  import { useRouter } from 'vue-router';
  import { appStore } from '@/store/app';
  import Card from '@/components/Card/Card.vue';
  import ProcesoController from '../../services/ProcesoController';
  import InfiniteLoading from 'v3-infinite-loading'
  import 'v3-infinite-loading/lib/style.css';
  import AlarmaController from '../../services/AlarmaController';
  import dayjs from "dayjs";
  import FilePicker from "@/components/FilePicker/FilePicker.vue";
  import spinner from '../../views/components/spinner/spinner.vue';
  import UsuarioController from '../../services/UsuarioController';

  const $appStore = appStore();
  const userData = $appStore.userdata;
  const $router = useRouter();

  const userProfileImage = computed(()=>{
    console.log($appStore.getUserData);
    return $appStore.getUserData?.profileImage;
  })

  const userProfileDefault = computed(()=>{
      return $appStore.getImageProfileDefault;
  })

  const listaProcesos = ref([]);
  const showSpinnerProcesos = ref(true);
  const hasMoreProcesos = ref(true);
  const pageProcesos = ref(1);
  const listaProcesosPromise = ProcesoController.getByUser(userData.id, pageProcesos.value);

  const listaAlarmas = ref([]);
  const showSpinnerAlarmas = ref(true);
  const hasMoreAlarmas = ref(true);
  const pageAlarmas = ref(1);
  const listaAlarmasPromise = AlarmaController.getByUser(userData.id, pageAlarmas.value);

  const showEdit = ref(false);
  const newData = reactive({ name: userData.name, profileImage: ''});
  const errorName = ref(false);

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
      console.log(response);
        listaAlarmas.value = response.data.data;
        hasMoreAlarmas.value = response.data.last_page > response.data.current_page;
        console.log(hasMoreAlarmas.value);
        showSpinnerAlarmas.value = false;
    })
    .catch((error) => {
        console.error('Error al obtener la lista de alarmas:', error);
        showSpinnerAlarmas.value = false;
    });

  const loadMoreAlarmas = async () => {
    try {
      pageAlarmas.value++;
      const response = await AlarmaController.getByUser(userData.id, pageAlarmas.value);
      const newAlarmas = response.data.data;
      listaAlarmas.value = [...listaAlarmas.value, ...newAlarmas];

      hasMoreAlarmas.value = response.data.last_page > response.data.current_page;
    } catch (error) {
      console.error('Error al cargar más alarmas:', error);
    }
  };

  const loadMoreProcesos = async () => {
    try {
      pageProcesos.value++;
      const response = await ProcesoController.getByUser(userData.id, pageProcesos.value);
      const newProcesos = response.data.data;
      listaProcesos.value = [...listaProcesos.value, ...newProcesos];

      hasMoreProcesos.value = response.data.last_page > response.data.current_page;
    } catch (error) {
      console.error('Error al cargar más procesos:', error);
    }
  };

  const formattedDate = (fechaHora) => {
    return dayjs(fechaHora).format('DD/MM/YYYY HH:mm A');
  }



  const editProfile = ()=>{
    showEdit.value = true;
  }

  const changeFilePicker = (file)=>{
    newData.profileImage = file;
    console.log(newData.profileImage);
  }

  const guardarCambios = async () => {
    if (newData.name.trim() === '') {
      errorName.value = true;
    } else {
      $appStore.setGlobalLoading(true);
      try {
        if(newData.profileImage != ''){
          const body = new FormData();
          body.append('profileImage', newData.profileImage);
          console.log(body);
          const newProfileImageUrl =  await UsuarioController.changeMeProfileImage(body);
          console.log(newProfileImageUrl)
          $appStore.setProfileImage(newProfileImageUrl);
        }
        const response = await UsuarioController.editarUsuario(
          userData.id, newData.name, userData.email, userData.rol
        );

        if(response){
          $appStore.setUserName(newData.name);
          userData.name = newData.name;
        }
        $appStore.setGlobalLoading(false);
        errorName.value = false;
        showEdit.value = false;
      } catch (error) {
        console.error('Error al guardar los cambios:', error);
      }
    }
  };

  const redirectToProcess = ({id})=>{
      $router.push({name:"Diagrama", query: { procesoId: id }});
  }
  


</script>

<template>
  <div class="container mx-auto">
    <Breadcrumb parentTitle='Perfil'/>
    <div class="grid grid-cols-12 gap-5">
      <div class="col-span-12">
        <BaseCard noPadding class="flex items-center w-full">
          <div class="flex flex-col items-center justify-center pb-8" v-if="showEdit">
            <div class="space-4 mb-8 max-w-md">
              <FilePicker 
                  @onChangeFile="changeFilePicker" 
                  @onClearFile="newData.profileImage = ''" 
                  :defaultImgUrl="userProfileImage">
              </FilePicker>
            </div>
              <input v-model="newData.name" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="text" placeholder="Nombre">
                <span v-if="errorName" class="px-2 py-2 text-red-500 text-xs">Por favor ingrese el nombre del usuario</span>
              <p class="text-gray-600">{{ userData.rol }}</p>
              <p class="text-gray-600">{{ userData.email }}</p>
              <BaseBtn block class="bg-[#25CEDE] mt-8 text-white rounded-full" @click="guardarCambios" >Guardar cambios</BaseBtn>
          </div>

          <div class="flex flex-col items-center justify-center pb-8" v-if="!showEdit">
              <div class="text-center"><img class="relative z-1 w-24 h-24 m-auto rounded-full border-2 border-white object-fill" :src="userProfileImage ? userProfileImage : userProfileDefault" /></div>
              <p class="text-2xl">{{ userData.name }}</p>
              <p class="text-gray-600">{{ userData.rol }}</p>
              <p class="text-gray-600">{{ userData.email }}</p>
              <BaseBtn block class="bg-[#25CEDE] mt-8 text-white rounded-full" @click="editProfile" >Editar</BaseBtn>
          </div>
        </BaseCard>
      </div>

      <div class="col-span-12">
        <BaseCard class="h-auto">
          <Breadcrumb parentTitle='Procesos' />
          <div class="h-auto flex flex-col items-center space-y-2 p-3 max-h-[70vh] overflow-y-auto">
            
            <!--spinner :show="showSpinnerProcesos"></spinner-->

            <Card v-if="listaProcesos.length == 0 && !showSpinnerProcesos"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                    <p class="font-bold text-xl">No tienes procesos asociados
                    </p>
                </div>
            </Card>

            <Card v-for="(proceso, index) in listaProcesos" :key="proceso.id"
               @click="redirectToProcess(proceso)"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full"
                :class="{ 'selected-card': index === selectedCardIndex }">
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

      <div class="col-span-12">
        <BaseCard class="h-auto">
          <Breadcrumb parentTitle='Historial de notificaciones de alarmas' />
          <div class="h-auto max-h- flex flex-col items-center space-y-2  max-h-[70vh] overflow-y-auto p-3 ">

            <!--spinner :show="showSpinnerAlarmas"></spinner-->

            

            <Card v-if="listaAlarmas.length == 0 && !showSpinnerAlarmas"
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                    <p class="font-bold text-xl">No has recibido alarmas
                    </p>
                </div>
            </Card>

            <Card v-for="(item, index) in listaAlarmas" :key="item.id"
                   
                class="hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white w-full">
                <div class="flex flex-row items-center justify-between">
                  <div class="flex">
                                <img class="w-20 object-fill" :src="item.tipoComponenteImagen" alt="" />
                                <div class="ml-4 flex flex-col justify-center items-start">
                                    <p>{{ item.componenteNombre }}</p>
                                    <p>{{ item.tipoComponente }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col justify-between items-start">
                                <p class="text-sm text-gray-400">Proceso</p>
                                <p>{{ item.procesoNombre }}</p>
                            </div>
                            <div class="flex flex-col justify-between items-start">
                                <p class="text-sm text-gray-400">Motivo</p>
                                <p>{{ item.motivo }}</p>
                            </div>
                            <div class="flex flex-col justify-between items-start">
                                <p class="text-sm text-gray-400">Fecha</p>
                                <p>{{ formattedDate(item.fechaHora) }}</p>
                            </div>
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

<!--EditProfile
    v-if="showEdit" 
    :show="showEdit"
    @onClose="showEdit = false" >
</EditProfile-->

</template>
