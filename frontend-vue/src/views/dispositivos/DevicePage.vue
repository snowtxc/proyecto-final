<template>
    <BaseCard>
        <Breadcrumbs :parentTitle="title" />
        <div class="grid grid-cols-12 w-full gap-2 mb-5">
            <div class="col-span-6">
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
                            class="h-11 px-4 w-72 px-4 py-3 text-sm font-normal rounded-2xl block border-2 focus:ring focus:ring-primary-200 focus:ring-opacity-50 bg-white dark:focus:ring-primary-6000 dark:focus:ring-opacity-25 dark:bg-neutral-900 disabled:bg-neutral-200 dark:disabled:bg-neutral-800"
                            v-model="dataDevice.Nombre"
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
                            class="h-11 px-4 w-72 px-4 py-3 text-sm font-normal rounded-2xl block border-2 focus:ring focus:ring-primary-200 focus:ring-opacity-50 bg-white dark:focus:ring-primary-6000 dark:focus:ring-opacity-25 dark:bg-neutral-900 disabled:bg-neutral-200 dark:disabled:bg-neutral-800"
                            v-model="dataDevice.DireccionIp"
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
                                submit && $v.$invalid && $v.Descripcion.$invalid
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

                <div
                    class="grid grid-cols-12 w-full gap-2 flex items-center mt-2"
                >
                    <div class="col-span-6 mb-3 flex flex-col">
                        <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Tipo de Dispositivo *</label
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
                    <div class="col-span-3 flex items-center mb-3">
                        <img
                            v-if="tipoComponenteSelected"
                            :src="tipoComponenteSelected.Imagen"
                            alt="Imagen del tipo de dispositivo"
                            class="w-12 h-12 ml-2"
                        />
                    </div>
                    <div class="col-span-3 flex items-center ml-2 mb-3">
                        <BaseBtn
                            @click="showModalNewTipoComponente = true"
                        >
                            <i class="mr-2 fa-solid fa-plus"></i>
                            Nuevo
                        </BaseBtn>
                    </div>
                </div>

                <BaseCard>
                    <div class="flex gap-2">
                        <p>Seleccionar unidades que utiliza el dispositivo</p>
                        <span
                            class="text-red-500 q-ml-sm"
                            v-if="submit && $v.unidades.$invalid"
                            >Hay campos invalidos *</span
                        >
                    </div>
                    <div class="flex flex-col gap-2">
                        <span
                            v-if="submit && dataDevice.unidades.length <= 0"
                            class="mt-2 w-full text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                        >
                            Debes ingresar al menos una unidad al dispositivo
                        </span>
                        <Multiselect
                            v-model="unidadesSelected"
                            :options="unidadesOptions"
                            mode="tags"
                            @select="onSelect"
                            @deselect="deselect"
                            @clear="onClearSelect"
                        />
                    </div>

                    <div
                        class="w-full gap-2 flex flex-col items-center mt-2 max-h-[300px] overflow-y-auto"
                    >
                        <div
                            v-for="(unidad, index) in $v.unidades.$each
                                .$response.$data"
                            :key="index"
                        >
                            <p>{{ dataDevice.unidades[index].nombre }}</p>
                            <BaseCard class="w-full">
                                <div
                                    class="w-full grid grid-cols-12 flex gap-2"
                                >
                                    <div class="col-span-6 mb-3 flex flex-col">
                                        <div class="w-full">
                                            <label
                                                class="text-xs text-gray-600"
                                                for="nombre"
                                                >Minimo
                                            </label>
                                            <span
                                                v-if="
                                                    submit &&
                                                    unidad.min.$invalid
                                                "
                                                class="mt-2 w-full text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                                            >
                                                Es invalido
                                            </span>
                                        </div>
                                        <input
                                            class="h-11 px-4 w-72 px-4 py-3 text-sm font-normal rounded-2xl block border-2 focus:ring focus:ring-primary-200 focus:ring-opacity-50 bg-white dark:focus:ring-primary-6000 dark:focus:ring-opacity-25 dark:bg-neutral-900 disabled:bg-neutral-200 dark:disabled:bg-neutral-800"
                                            v-model="
                                                dataDevice.unidades[index].min
                                            "
                                            type="text"
                                        />
                                    </div>
                                    <div
                                        class="col-span-6 mb-3 flex flex-col col-"
                                    >
                                        <div>
                                            <label
                                                class="text-xs text-gray-600"
                                                for="nombre"
                                                >Maximo</label
                                            >
                                            <span
                                                v-if="
                                                    submit &&
                                                    unidad.max.$invalid
                                                "
                                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                                            >
                                                Es invalido
                                            </span>
                                        </div>
                                        <input
                                            class="h-11 px-4 w-72 px-4 py-3 text-sm font-normal rounded-2xl block border-2 focus:ring focus:ring-primary-200 focus:ring-opacity-50 bg-white dark:focus:ring-primary-6000 dark:focus:ring-opacity-25 dark:bg-neutral-900 disabled:bg-neutral-200 dark:disabled:bg-neutral-800"
                                            v-model="
                                                dataDevice.unidades[index].max
                                            "
                                            type="text"
                                        />
                                    </div>
                                </div>
                            </BaseCard>
                        </div>
                    </div>
                </BaseCard>
            </div>
            <div class="ml-4 col-span-6">
                <BaseCard>
                    <Breadcrumbs parentTitle="Fotos" />

                    <div class="grid grid-cols-12 gap-4 mt-4">
                        <div
                            v-for="image in images"
                            :key="image.index"
                            class="col-span-4 h-48 flex justify-center items-center bg-white rounded-lg shadow-md overflow-hidden cursor-pointer transition duration-300 ease-in-out transform hover:scale-105 hover:brightness-75"
                        >
                            <button
                                class="absolute top-2 left-2 text-red-500 cursor-pointer"
                                @click="onDeleteImage(image)"
                                v-if="image.src"
                            >
                                <i class="fa-solid fa-times"></i>
                            </button>

                            <div
                                @click="onSelectImage(image.index)"
                                class="w-full h-screen flex justify-center items-center"
                            >
                                <img
                                    v-if="image.src"
                                    class="w-full"
                                    :src="image.src"
                                />
                                <i class="fa-solid fa-image" v-else></i>
                            </div>
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
            <BaseBtn @click="onSubmit">
<!--{{ action == Action.CREAR ? 'CREAR' : 'EDITAR' }} -->
                Guardar
            </BaseBtn>
        </div>
    </BaseCard>

    <ModalTipoComponente
        :action="Action.CREAR"
        @onTipoComponente="handleNewTipoComponente"
        :show="showModalNewTipoComponente"
        v-if="showModalNewTipoComponente"
        @onClose="showModalNewTipoComponente = false"
    ></ModalTipoComponente>
    <ConfirmationModal
        :show="showModalDelete"
        message="Seguro deseas eliminar este componente?"
        title="Eliminar Componente"
        @cancel="showModalDelete = false"
        @confirm="submitDeleteImage"
    ></ConfirmationModal>
</template>

<script setup>
import { defineProps, ref, onBeforeMount } from 'vue'
import { appStore } from '../../store/app'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import BaseBtn from '../../components/Base/BaseBtn.vue'
import { Action } from '../../shared/enums/Action'

import useVuelidate from '@vuelidate/core'
import { required, ipAddress, helpers, numeric } from '@vuelidate/validators'

import ProcesoController from '@/services/ProcesoController.js'
import TipoComponenteController from '@/services/TipoComponenteController.js'
import ComponenteController from '@/services/ComponenteController.js'
import UnidadController from '@/services/UnidadController.js'

import { useNotification } from '@kyvg/vue3-notification'
import { useRoute } from 'vue-router'

import ConfirmationModal from '../../components/ConfirmationModal.vue'
import ModalTipoComponente from '../../components/Modals/ModalTipoComponente.vue'
import Multiselect from '@vueform/multiselect'

const { notify } = useNotification()

const $route = useRoute()

const props = defineProps({
    show: Boolean,
    selectedUser: Number,
})

const $appStore = appStore()

const rules = {
    Nombre: { required },
    DireccionIp: { required, ipAddress },
    Descripcion: { required },
    tipo_componente_id: { required },
    unidades: {
        $each: helpers.forEach({
            min: {
                required,
                numeric,
            },
            max: {
                required,
                numeric,
            },
        }),
    },
}

const inputFile = ref(null)
const indexImageSelected = ref(null)
const submit = ref(false)
const tipoComponenteSelected = ref(null)
const imageSelected = ref(null)
const showModalNewTipoComponente = ref(false)

const dataDevice = ref({
    Nombre: '',
    DireccionIp: '',
    Descripcion: '',
    procesoId: null,
    tipo_componente_id: null,
    unidades: [],
})

const procesos = ref([])
const etapas = ref([])
const tiposComponentes = ref([])
const loadingEtapas = ref(false)
const action = ref(null)
const title = ref('')
const showModalDelete = ref(false)

const $v = useVuelidate(rules, dataDevice)

const images = ref([
    {
        id: null,
        index: 0,
        file: null,
        src: null,
    },
    {
        id: null,
        index: 1,
        file: null,
        src: null,
    },
    {
        id: null,
        index: 2,
        file: null,
        src: null,
    },
    {
        id: null,
        index: 3,
        file: null,
        src: null,
    },
    {
        id: null,
        index: 4,
        file: null,
        src: null,
    },
    {
        id: null,
        index: 5,
        file: null,
        src: null,
    },
])
const unidades = ref([])
const unidadesSelected = ref([])
const unidadesOptions = ref([])

onBeforeMount(async () => {
    $route.name == 'editarDispositivo'
        ? (action.value = Action.EDITAR)
        : (action.value = Action.CREAR)

    $appStore.setGlobalLoading(true)
    const [procesosData, tiposComponentesData, unidadesData] =
        await Promise.all([
            ProcesoController.getAll(),
            TipoComponenteController.getAll(),
            UnidadController.list(),
        ])

    procesos.value = procesosData
    tiposComponentes.value = tiposComponentesData
    unidades.value = unidadesData
    formatUnidadesOptions(unidades.value)

    if ($route.name == 'editarDispositivo') {
        const { id } = $route.params
        const componenteData = await ComponenteController.getById(id)
        const { imagenes } = componenteData
        loadImages(imagenes)
        loadUnidades(componenteData.unidades)
        dataDevice.value = componenteData
        tipoComponenteSelected.value = tiposComponentes.value.find(
            (tipoComponente) =>
                tipoComponente.id == componenteData.tipo_componente_id
        )
        dataDevice.value.procesoId = componenteData.proceso_id
        loadEtapasByProceso(componenteData.proceso_id)
        $appStore.setGlobalLoading(false)
        title.value = `Editar Dispositivo "${componenteData.Nombre}"`
    } else {
        $appStore.setGlobalLoading(false)
        title.value = `Nuevo Dispositivo`
    }
})

const formatUnidadesOptions = (unidades) => {
    unidadesOptions.value = unidades.map((item) => item.nombre)
}

const onSelectImage = (index) => {
    indexImageSelected.value = index
    inputFile.value.click()
}

const loadImages = (imagenes) => {
    imagenes.map((image, index) => {
        images.value[index].src = image.Path
        images.value[index].id = image.id
    })
}

const loadUnidades = (unities) => {
    unities.map((unidad) => {
        unidadesSelected.value.push(unidad.nombre)
        const { min, max, nombre } = unidad
        dataDevice.value.unidades.push({
            unidad_id: unidad.id,
            min,
            max,
            nombre,
        })
    })
}

const uploadImage = async (file) => {
    $appStore.setGlobalLoading(true)
    const componenteID = dataDevice.value.id
    const body = new FormData()
    body.append('Imagen', file)
    const newImage = await ComponenteController.addImage(componenteID, body)
    $appStore.setGlobalLoading(false)
    return newImage
}

const onFileSelected = async ($event) => {
    const file = $event.target.files[0]
    if (action.value == Action.EDITAR) {
        const image = await uploadImage(file)
        images.value[indexImageSelected.value].file = file
        images.value[indexImageSelected.value].src = image.Path
        images.value[indexImageSelected.value].id = image.id

        console.log(images.value[indexImageSelected.value])
    } else {
        const reader = new FileReader()
        reader.readAsDataURL(file)

        images.value[indexImageSelected.value].file = file
        reader.onload = () => {
            images.value[indexImageSelected.value].src = reader.result
        }
    }
}

const resetForm = () => {
    $v.value.$reset()
    dataDevice.value.Nombre = ''
    dataDevice.value.DireccionIp = ''
    dataDevice.value.Descripcion = ''
    dataDevice.value.tipo_componente_id = null
    dataDevice.value.unidades = []
    unidadesSelected.value = []
}

const onSubmit = async () => {
    submit.value = true
    if ($v.value.$invalid) {
        return
    }
    if (dataDevice.value.unidades.length <= 0) {
        return
    }
    submit.value = false
    $appStore.setGlobalLoading(true)

    const { Nombre, DireccionIp, Descripcion, tipo_componente_id, unidades } =
        dataDevice.value
    const formData = new FormData()
    formData.append('Nombre', Nombre)
    formData.append('DireccionIp', DireccionIp)
    formData.append('Descripcion', Descripcion)
    formData.append('tipo_componente_id', tipo_componente_id)
    formData.append('Unidad', 'Celsius')

    unidades.map((unidad, idx) => {
        formData.append('unidades[' + idx + '][unidad_id]', unidad.unidad_id)
        formData.append('unidades[' + idx + '][min]', unidad.min)
        formData.append('unidades[' + idx + '][max]', unidad.max)
    })

    if (action.value == Action.CREAR) {
        const imagenes = images.value.filter((image) => image.file !== null)
        imagenes.map((image) => formData.append('imagenes[]', image.file))
    }

    try {
        const componente =
            action.value == Action.CREAR
                ? await ComponenteController.create(formData)
                : await ComponenteController.edit(dataDevice.value.id, formData)
        notify({
            title:
                action.value == Action.CREAR
                    ? 'Componente creado'
                    : 'Dispositivo editado',
            text:
                action.value == Action.CREAR
                    ? 'El componente se ha creado correctamente'
                    : 'El componente se ha editado correctamente',
            type: 'success',
        })

        action.value == Action.EDITAR
            ? changeTitulo(`Editar Componente "${componente.Nombre}"`)
            : null
        $appStore.setGlobalLoading(false)
        if (action.value == Action.CREAR) {
            tipoComponenteSelected.value = null
            resetForm()
            clearImages()
        }
        inputFile.value.value = ''
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

const onChangeTipoComponente = ($event) => {
    const id = $event.target.value
    tipoComponenteSelected.value = tiposComponentes.value.find(
        (tipoComponente) => tipoComponente.id == id
    )
}

const clearImages = () => {
    images.value = images.value.map((image) => ({
        ...image,
        file: null,
        src: null,
    }))
}

const onDeleteImage = (image) => {
    imageSelected.value = image
    showModalDelete.value = true
}

const submitDeleteImage = async () => {
    showModalDelete.value = false
    if (imageSelected.value) {
        const componenteID = dataDevice.value.id
        const imageID = imageSelected.value.id
        if (!imageID) {
            images.value[imageSelected.value.index].file = null
            images.value[imageSelected.value.index].src = null
            images.value[imageSelected.value.index].id = null
            imageSelected.value = null
            inputFile.value.value = ''
            return
        }
        $appStore.setGlobalLoading(true)
        try {
            const imageDeleted = await ComponenteController.deleteImage(
                componenteID,
                imageID
            )
            images.value[imageSelected.value.index].file = null
            images.value[imageSelected.value.index].src = null
            images.value[imageSelected.value.index].id = null
            imageSelected.value = null
            inputFile.value.value = ''
            $appStore.setGlobalLoading(false)
            notify({
                title: 'Imagen removida',
                text: `La imagen ha sido removida correctamente`,
                type: 'success',
            })
        } catch (e) {
            notify({
                title: 'Error',
                text: `Ocurrio un error al remover la imagen`,
                type: 'error',
            })
            $appStore.setGlobalLoading(false)
        }
    }
}

const onSelect = (value) => {
    const unidad = unidades.value.find((unidad) => unidad.nombre == value)
    const item = {
        unidad_id: unidad.id,
        min: null,
        max: null,
        nombre: unidad.nombre,
    }
    dataDevice.value.unidades.push(item)
}

const deselect = (value) => {
    const unidad = unidades.value.find((unidad) => unidad.nombre == value)
    const index = dataDevice.value.unidades.findIndex(
        (item) => item.unidad_id == unidad.id
    )
    dataDevice.value.unidades.splice(index, 1)
}

const changeTitulo = (value) => {
    title.value = value
}

const handleNewTipoComponente = (newTipoComponente) => {
    tipoComponenteSelected.value = newTipoComponente
    tiposComponentes.value.unshift(newTipoComponente)
    dataDevice.value.tipo_componente_id = newTipoComponente.id
    showModalNewTipoComponente.value = false
}

const onClearSelect = ()=>{
    unidadesSelected.value = [];
    dataDevice.value.unidades = [];
}
</script>

<style src="@vueform/multiselect/themes/default.css">
.multiselect__input {
    background-color: red;
}
</style>
