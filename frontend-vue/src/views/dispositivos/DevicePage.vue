<template>
    <BaseCard>
        <Breadcrumbs parentTitle="Nuevo Dispositivo" />
        <div class="grid grid-cols-12 w-full gap-2 mb-5">
            <div class="col-span-5">
                <div class="flex gap-5">
                    <div class="mb-3 flex flex-col">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Nombre *</label
                            >
                            <span
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                                v-if="
                                    submit && $v.$invalid && $v.Nombre.$invalid
                                "
                            >
                                Por favor ingresa el nombre del dispositivo
                            </span>
                        </div>

                        <input
                            v-model="dataDevice.Nombre"
                            class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full"
                            type="text"
                        />
                    </div>
                    <div class="mb-3 flex flex-col">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Direccion IP *</label
                            >
                            <span
                                v-if="
                                    submit &&
                                    $v.$invalid &&
                                    $v.DireccionIp.$invalid
                                "
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                            >
                                {{
                                    $v.DireccionIp.required.$invalid
                                        ? 'Por favor ingresa la direccion IP'
                                        : $v.DireccionIp.ipAddress.$invalid
                                        ? 'Direccion ip invalida'
                                        : ''
                                }}</span
                            >
                        </div>
                        <input
                            v-model="dataDevice.DireccionIp"
                            class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full"
                            type="text"
                        />
                    </div>
                </div>

                <div class="flex flex-col">
                    <div>
                        <label class="text-xs text-gray-600" for="nombre"
                        >Descripcion</label
                             >
                        <span
                                v-if="
                                    submit &&
                                    $v.$invalid &&
                                    $v.Descripcion.$invalid
                                "
                                class="ml-5 mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                            >Descripcion es requerida</span
                            >
                    </div>
                    
                    <textarea
                        v-model="dataDevice.Descripcion"
                        rows="4"
                        class="w-full block p-2.5 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400"
                    ></textarea>
                </div>

                <div class="grid grid-cols-12 w-full gap-2">
                    <div class="col-span-6 mb-3 flex flex-col col-">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Tipo de Componente *</label
                            >
                            <span 
                                v-if="submit && $v.tipo_componente_id.$invalid"
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                            >
                                Es requerido
                            </span>
                        </div>
                        <select
                            v-model="dataDevice.tipo_componente_id"
                            id="small"
                            @change="onChangeTipoComponente"

                            class="w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        >
                            <option
                                v-for="tipoComponente in tiposComponentes"
                                :key="tipoComponente.id"
                                :value="tipoComponente.id"
                            >
                                {{ tipoComponente.Nombre }}
                                
                              
                            </option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <img
                            v-if="tipoComponenteSelected"
                            :src="tipoComponenteSelected.Imagen"
                            alt="Imagen del tipo de componente"
                            class="w-12 h-12 ml-2"
                        />
                    </div>
                </div>
                

                <div class="grid grid-cols-12 w-full gap-2">
                    <div class="col-span-6 mb-3 flex flex-col col-">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Proceso *</label
                            >
                            <span
                                v-if="submit && $v.procesoId.$invalid"
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                            >
                                Es requerido
                            </span>
                        </div>
                        <select
                            v-model="dataDevice.procesoId"
                            id="small"
                            class="w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                            @change="onChangeProceso"
                        >
                            <option
                                v-for="proceso in procesos"
                                :key="proceso.id"
                                :value="proceso.id"
                            >
                                {{ proceso.Nombre }}
                            </option>
                        </select>
                    </div>
                    <div class="col-span-6 mb-3 flex flex-col">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Etapa *</label
                            >
                            <span
                                v-if="submit && $v.etapa_id.$invalid"
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                            >
                                Es requerido
                            </span>
                        </div>
                        <div
                            v-if="loadingEtapas"
                            role="status"
                            class="flex justify-center"
                        >
                            <svg
                                aria-hidden="true"
                                class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-primary"
                                viewBox="0 0 100 101"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor"
                                />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill"
                                />
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                        <select
                            v-else
                            v-model="dataDevice.etapa_id"
                            id="small"
                            class="w-full block w p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option
                                v-for="etapa in etapas"
                                :key="etapa.id"
                                :value="etapa.id"
                            >
                                {{ etapa.Nombre }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="ml-4 col-span-7">
                <BaseCard>
                    <Breadcrumbs parentTitle="Fotos" />

                    <div
                        class="grid grid-cols-12  gap-4 mt-4"
                    >
                        <div
                            v-for="image in images"
                            :key="image.index"
                            @click="onSelectImage(image.index)"
                            class="col-span-4  h-48 flex justify-center items-center bg-white rounded-lg shadow-md overflow-hidden cursor-pointer transition duration-300 ease-in-out transform hover:scale-105 hover:brightness-75"
                        >
                            <img
                                v-if="image.src"
                                class="w-full h-auto"
                                :src="image.src"
                            />
                            <i class="fa-solid fa-image" v-else></i>
                        </div>
                    </div>

                    <input
                        type="file"
                        class="hidden"
                        ref="inputFile"
                        @change="onFileSelected"
                    />
                </BaseCard>
            </div>
        </div>
        <div class="flex justify-end mt-5">
            <BaseBtn @click="onSubmit">CONFIRMAR </BaseBtn>
        </div>
    </BaseCard>
</template>

<script setup>
import { defineProps, ref, onBeforeMount } from 'vue'
import { appStore } from '../../store/app'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import BaseBtn from '../../components/Base/BaseBtn.vue'

import useVuelidate from '@vuelidate/core'
import { required, ipAddress } from '@vuelidate/validators'

import ProcesoController from '@/services/ProcesoController.js'
import TipoComponenteController from '@/services/TipoComponenteController.js'
import ComponenteController from '@/services/ComponenteController.js'

import { useNotification } from '@kyvg/vue3-notification'
import { useRoute } from 'vue-router'
const { notify } = useNotification()

const $route = useRoute();

const props = defineProps({
    show: Boolean,
    selectedUser: Number,
})

const $appStore = appStore()

const rules = {
    Nombre: { required },
    DireccionIp: { required, ipAddress },
    Descripcion: { required },
    procesoId: { required },
    etapa_id: { required },
    tipo_componente_id: { required },
}

const inputFile = ref(null)
const indexImageSelected = ref(null)
const submit = ref(false)
const tipoComponenteSelected = ref(null);


const dataDevice = ref({
    Nombre: '',
    DireccionIp: '',
    Descripcion: '',
    procesoId: null,
    etapa_id: null,
    tipo_componente_id: null,
})

const procesos = ref([])
const etapas = ref([])
const tiposComponentes = ref([])
const loadingEtapas = ref(false)

const $v = useVuelidate(rules, dataDevice)

const images = ref([
    {
        index: 0,
        file: null,
        src: null,
    },
    {
        index: 1,
        file: null,
        src: null,
    },
    {
        index: 2,
        file: null,
        src: null,
    },
    {
        index: 3,
        file: null,
        src: null,
    },
    {
        index: 4,
        file: null,
        src: null,
    },
    {
        index: 5,
        file: null,
        src: null,
    },
])

onBeforeMount(async () => {
    $appStore.setGlobalLoading(true)
    const [procesosData, tiposComponentesData] = await Promise.all([
        ProcesoController.getAll(),
        TipoComponenteController.getAll(),
    ])
    procesos.value = procesosData
    tiposComponentes.value = tiposComponentesData;

    if($route.name == 'editarDispositivo'){
        const { id } = $route.params;
        const componenteData = await ComponenteController.getById(id);
        const { imagenes } = componenteData;
        console.log(imagenes);
        loadImages(imagenes);
        dataDevice.value = componenteData;
        tipoComponenteSelected.value = tiposComponentes.value.find((tipoComponente) => tipoComponente.id == componenteData.tipo_componente_id);
        dataDevice.value.procesoId = componenteData.proceso_id;
        loadEtapasByProceso(componenteData.proceso_id);
        $appStore.setGlobalLoading(false);
    }else{
        $appStore.setGlobalLoading(false);
    }
})

const onSelectImage = (index) => {
    indexImageSelected.value = index
    inputFile.value.click()
}

const loadImages = (imagenes) =>{
    imagenes.map((path ,index) => {
        images.value[index].src = path;
    });
}

const onFileSelected = ($event) => {
    const file = $event.target.files[0]
    const reader = new FileReader()
    reader.readAsDataURL(file)

    images.value[indexImageSelected.value].file = file
    reader.onload = () => {
        images.value[indexImageSelected.value].src = reader.result
    }
}

const resetForm = () => {
    $v.value.$reset()
    dataDevice.value.Nombre = ''
    dataDevice.value.DireccionIp = ''
    dataDevice.value.Descripcion = ''
    dataDevice.value.procesoId = null
    dataDevice.value.etapa_id = null
    dataDevice.value.tipo_componente_id = null
}

const onSubmit = async () => {
    submit.value = true
    if ($v.value.$invalid) {
        return
    }

    submit.value = false

    $appStore.setGlobalLoading(true)

    const { etapa_id, Nombre, DireccionIp, Descripcion } = dataDevice.value;

    const formData = new FormData();
    formData.append('etapa_id', etapa_id);
    formData.append('Nombre', Nombre);
    formData.append('DireccionIp', DireccionIp);
    formData.append('Descripcion', Descripcion);
    formData.append('tipo_componente_id', dataDevice.value.tipo_componente_id);
    formData.append('Unidad', "Celsius");


    const imagenes =  images.value.filter((image) => image.file !== null);
    imagenes.map((image) => formData.append('imagenes[]', image.file));

    try {
        const componentCreated = await ComponenteController.create(formData);
        notify({
            title: 'Dispositivo creado',
            text: 'El dispositivo se ha creado correctamente',
            type: 'success',
        })
        $appStore.setGlobalLoading(false)
        resetForm();
        clearImages();
    } catch (e) {
        $appStore.setGlobalLoading(false)
        notify({
            title: 'Error',
            text: 'Ha ocurrido un error al crear el dispositivo',
            type: 'error',
        })
    }
}

const loadEtapasByProceso = (idProceso) => {
    loadingEtapas.value = true
    ProcesoController.getProcesosByEtapa(idProceso).then((data) => {
        etapas.value = data
        loadingEtapas.value = false
    })
}

const onChangeProceso = ($event) => {
    const id = $event.target.value
    dataDevice.value.etapaId = null
    loadEtapasByProceso(id)
}

const onChangeTipoComponente = ($event) =>{
    const id = $event.target.value;
    tipoComponenteSelected.value = tiposComponentes.value.find((tipoComponente) => tipoComponente.id == id);
}

const clearImages = ()=>{
    images.value = images.value.map((image)=>({
        ...image,
        file: null,
        src: null
    }))
}
</script>
