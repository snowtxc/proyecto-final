<template>
    <div>
        <div class="card-header flex justify-between items-center">           
            <div class="card-title ">
                <p class="text-xl font-semibold mr-2"> Dispositivos </p>
            </div>
            <BaseBtn 
                @click="$router.push({name: 'nuevoDispositivo'})"
                >
                <i class="mr-2 fa-solid fa-plus"></i>
                Nuevo Dispositivo
            </BaseBtn>
        </div>
        <div class="flex flex-col gap-10 mt-4 md:flex-row">
            <div class="min-w-[350px]">
                <BaseCard>
                    
                    <label class="text-sm text-gray-600" for="tipo">Tipo:</label>
                    <select
                            v-model="filters.tipo"
                            id="small"
                            @change="handleFilter"
                            class="w-full p-2 mb-6 text-sm text-gray-900 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        >
                        <option :value="''"> Todos </option>
                        <option
                            v-for="tipoComponente in tiposComponentes"
                            :key="tipoComponente.id"
                            :value="tipoComponente.id"
                        >
                            {{ tipoComponente.Nombre }}                                
                        </option>
                    </select>
                    <input
                        class="w-full p-2 mb-2 text-sm text-gray-900 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        type="text"
                        placeholder="Buscar"
                        v-model="filters.search"
                        @input="handleSearch"
                        
                    />
                    <div id="scrollContainer"
                        class="flex flex-col overflow-y-auto h-auto max-h-[65vh]"
                    >
                        <div class="bg-white p-8 rounded-md shadow-md max-w-md"
                        v-if="componentesIsEmpty && !loading"
>
                            <h2 class="text-2xl font-semibold mb-4">
                                No se encontraron dispositivos
                            </h2>
                            <p class="text-gray-600">
                                Lo sentimos, no hemos encontrado ningún
                                dispositivo.

                            </p>
                        </div>
                        <div v-else class="mt-2" v-for="item in componentes" :key="item.id">
                            <CardDevice :nombre="item.Nombre" :ipAddress="item.DireccionIp" 
                                :image="item.tipoComponenteImage"  :unidades="item.unidades" :selected="deviceSelected &&
                                    deviceSelected.id == item.id
                                    " @onSelect="handleSelectedDevice(item)"></CardDevice>
                        </div>
                        <div class="flex justify-center mt-2">
                            <infinite-loading @infinite="loadMoreData" v-if="hasMoreData || loading"></infinite-loading>
                        </div>
                    </div>
                </BaseCard>
            </div>
            <div class="w-full">
                <div v-if="!deviceSelected && !loadingData" class="w-full bg-white p-8 rounded-md shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">
                        No hay dispositivo seleccionado
                    </h2>
                    <p class="text-gray-600">
                        Por favor, selecciona un dispositivo de la lista.
                    </p>
                 </div>
                <paneldeviceinfo v-if="deviceSelected && !loadingData" :deviceInfo="deviceSelected" @onDelete="handleDeleteDevice"></paneldeviceinfo>
            </div>
        </div>
    </div>
</template>

<script setup>
import { appStore } from '@/store/app'
import { useRouter } from 'vue-router';
import { computed, onBeforeMount, ref, onMounted } from 'vue';
import CardDevice from '../../components/Cards/CardDevice.vue'
import paneldeviceinfo from '../../components/Panel/PanelDeviceInfo.vue'
import ComponenteController from '@/services/ComponenteController'
import TipoComponenteController from '../../services/TipoComponenteController';
import InfiniteLoading from 'v3-infinite-loading'
import 'v3-infinite-loading/lib/style.css';
import { useNotification } from '@kyvg/vue3-notification'
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

import spinner from '../../views/components/spinner/spinner.vue';

onMounted(async () => {
    const container = document.getElementById('scrollContainer');
    new PerfectScrollbar(container);
    
})

const { notify } = useNotification();


const $appStore = appStore();
const $router = useRouter();

const debounceSearch = ref(null)

const deviceSelected = ref(null)
const hasMoreData = ref(true)
const page = ref(0)
const maxRows = ref(10)
const componentes = ref([]);
const loading = ref(true);
const loadingData = ref(false);
const tiposComponentes = ref([]);

const filters = ref({
    search: '',
    tipo: ''
})

onBeforeMount(async () => {
    const tiposComponentesData = await TipoComponenteController.getAll();
    tiposComponentes.value = tiposComponentesData;
})

const handleSelectedDevice = async(value) => {
    const { id}  = value;
    try{
        $appStore.setGlobalLoading(true);
        loadingData.value = true;
        const compontenteData =  await ComponenteController.getById(id);
        $appStore.setGlobalLoading(false);
        deviceSelected.value = compontenteData;
        loadingData.value = false;

    } catch (e) {
        notify({
            type: 'error',
            title: 'Error',
            text: 'Ocurrio un error al obtener el dispositivo'
        })
    }
}

const loadMoreData = async ($state) => {
    page.value++
    await getComponentes()
}

const getComponentes = async () => {
    loading.value = true;
    const result = await ComponenteController.list(
        page.value,
        maxRows.value,
        filters.value
    )
    const { data, countRows } = result
    componentes.value = [...componentes.value, ...data];
    hasMoreData.value = componentes.value.length < countRows
    loading.value = false;

}

const handleSearch = ($event) => {
    loading.value = true;
    const value = $event.target.value;
    if (debounceSearch.value) {
        clearTimeout(debounceSearch.value)
    }
    debounceSearch.value = setTimeout(() => {
        filters.value.search = value
        componentes.value = []
        page.value = 1
        getComponentes()
    }, 500)
}

const handleFilter = () => {
    componentes.value = []
    page.value = 1
    getComponentes()
    
}

const componentesIsEmpty = computed(() => {
    return componentes.value.length == 0
})

const handleDeleteDevice = (deviceId) => {
    const index = componentes.value.findIndex(componente => componente.id == deviceId);
    componentes.value.splice(index, 1);
    deviceSelected.value = null;
}
</script>
