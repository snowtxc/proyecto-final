<template>
    <div class="w-full h-screen">
        <div class="flex justify-center" v-if="loading">
            <spinner :show="true"></spinner>
        </div>
        <BaseCard v-else>
            <div class="card-header flex justify-between items-center">           
                <div class="card-title ">
                    <p class="text-xl font-semibold mr-2"> {{ title }} </p>
                </div>
                <div class="flex gap-4">
                        <font-awesome-icon :icon="['far', 'pen-to-square']" class="w-5 h-5 m-4 hover:text-primary" @click="
                            $router.push({
                                name: 'editarDispositivo',
                                params: { id: props.deviceInfo.id },
                            })
                            " />

                        <font-awesome-icon :icon="['far', 'trash-can']" @click="showModalDeleteComponent = true"
                            class="w-5 h-5 m-4 hover:text-primary" />
                    </div>
            </div>
            <div class="flex flex-col">
                <div
                    class="bg-white p-4 rounded-lg shadow-md flex justify-between items-start"
                >
                    <div>
                        
                        <div class="mb-2">
                            Estado:
                            <BaseBadge
                                :bgColor="
                                    deviceIsActive
                                        ? 'bg-green-100'
                                        : 'bg-red-100'
                                "
                                :text="
                                    deviceIsActive ? 'Operativo' : 'Inactivo'
                                "
                            ></BaseBadge>
                        </div>
                        <div class="mb-2 flex">
                            Proceso:
                            <a :class="deviceIsActive ?  'text-blue-500 hover:underline  cursor-pointer' : ''" class="ml-2">
                                {{
                                    deviceIsActive
                                        ? props.deviceInfo.nodoInfo.proceso
                                        : 'Sin Informacion'
                                }}
                            </a>
                        </div>
                        
                        <div class="mb-2">
                            Etapa:
                            <a :class="deviceIsActive ?  'text-blue-500 hover:underline  cursor-pointer' : ''" class="ml-2">
                                {{
                                deviceIsActive
                                    ? props.deviceInfo.nodoInfo.etapa
                                    : 'Sin Informacion'
                            }}
                            </a>
                            
                        </div>
                        
                        <div>
                            Desde:
                            {{
                                deviceIsActive
                                    ? dayjs(
                                          props.deviceInfo.nodoInfo
                                              .fechaDeIngreso
                                      ).format('DD/MM/YYYY hh:mm A')
                                    : 'Sin Informacion'
                            }}
                        </div>
                    </div>
                    <BaseBtn
                        @click="handleViewHistoricos"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex-none"
                    >
                        <i class="fas fa-history mr-2"></i> 
                        Ver Histórico
                    </BaseBtn>
                </div>

                <div class="mt-3">
                    <BaseCard>
                        <template v-slot:cardHeader>
                            <div class="card-header flex justify-between items-center">
                                <div class="card-title">
                                    <p class="text-xl font-semibold mr-2"> Partes </p>
                                </div>
                                <BaseBtn
                                    @click=";(showModalPart = true), (actionPart = Action.CREAR)"
                                >
                                    <i class="fa-solid fa-plus mr-2"></i>
                                    Nueva Parte
                                </BaseBtn>
                            </div>
                        </template>
                        <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                            <div class="dataTable-wrapper dataTable-loading no-footer fixed-columns">
                                <div
                                    class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover">
                                    <div class="flex justify-center w-full" v-if="loadingPartes">
                                        <spinner :show="true"></spinner>
                                    </div>
                                    <div class="w-full flex justify-center" v-else-if="!loadingPartes && emptyParts">
                                        <p class="text-gray-600">
                                            No existe ninguna parte asociada al
                                            componente
                                        </p>
                                    </div>

                                    <div class="max-h-[50vh] overflow-y-auto" v-else>
                                        <table
                                            class="table-3 dataTable-table max-w-full w-full max-h-[50vh] overflow-y-auto">
                                            <thead >
                                                <tr >
                                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold">
                                                        Fecha
                                                    </th>
                                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold">
                                                        Nombre de la parte
                                                    </th>

                                                    <th class=" border-b pb-3 mb-3 text-gray-500 font-semibold">
                                                        
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr class="hover:bg-gray-100 cursor-pointer"
                                                    v-for="parte in partesFormatted" :key="parte.id">
                                                    <td class="text-xs py-2 px-4">
                                                        {{ parte.Fecha }}
                                                    </td>

                                                    <td class="text-xs">
                                                        {{ parte.Nombre }}
                                                    </td>

                                                    <td class="py-2 flex justify-end">
                                                        <div>
                                                            <ModalPartNotas 
                                                                :parteNombre="parte.Nombre" 
                                                                :componenteId="props.deviceInfo.id"
                                                                :parteId="parte.id">
                                                            </ModalPartNotas>
                                                            <font-awesome-icon :icon="[
                                                                'far',
                                                                'pen-to-square',
                                                            ]" class="w-5 h-5 m-4 hover:text-primary"
                                                                @click="editPart(parte)" />

                                                            <font-awesome-icon :icon="['far', 'trash-can',]"
                                                                @click="deletePart(parte)"
                                                                class="w-5 h-5 m-4 hover:text-primary" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </BaseCard>
                </div>
                <div class="w-full mt-5 max-h-[70vh] overflow-y-auto p-5" v-if="deviceIsActive">
                    <div class="w-full flex justify-end">
                        <div>
                            <p>Unidad de medida seleccionada</p>
                            <select
                                id="small"
                                class="p-2 mb-6 text-sm text-gray-900 brounded-lg border border-gray-400 min-w-[400px]"
                                @change="onSelectUnidadDeMedida"
                            >
                                <option
                                    v-for="unidad in componenteUnidades"
                                    :key="unidad.unidad_id"
                                    :value="unidad.unidad_id"
                                >
                                    <div class="p-5">
                                        {{ unidad.nombre }}
                                    </div>
                                </option>
                            </select>
                        </div>
                    </div>
                    <BaseCard>
                        <Breadcrumbs
                            :parentTitle="unidadSelected.nombre"
                        ></Breadcrumbs>
                        
                        <div class="flex mt-3 gap-10">
                            <ChartBar
                                class="flex-1"
                                :componente_id="props.deviceInfo.id"
                                :unidad="unidadSelected"
                                :unidades="componenteUnidades" 
                            ></ChartBar>
                            <ChartLine
                                class="flex-1"
                                :componente_id="props.deviceInfo.id"
                                :unidad="unidadSelected"
                                :unidades="componenteUnidades"
                            ></ChartLine>
                        </div>
                    </BaseCard>
                </div>
                <div class="bg-gray-100   rounded-md text-center mt-5 py-5" v-else>
                    El dispositivo no se encuentra operativo ya que no está asociado a una etapa de un Proceso
                </div>
            </div>
        </BaseCard>
    </div>

    <ModalPartForm v-if="showModalPart" @onProcessed="handleParteModal" :action="actionPart"
        @onClose="showModalPart = false" :componente_id="props.deviceInfo.id" :partData="partSelected"></ModalPartForm>

    <ConfirmationModal v-if="showModalDeleteComponent" :show="showModalDeleteComponent" title="Eliminar componente"
        message="Seguro deseas eliminar este componente?" @cancel="showModalDeleteComponent = false" @confirm="onDelete">
    </ConfirmationModal>

    <ConfirmationModal v-if="showModalDeletePart && partSelected" :show="showModalDeletePart" title="Eliminar parte"
        message="Seguro deseas eliminar esta parte?" @cancel="showModalDeletePart = false" @confirm="onConfirmDeletePart">
    </ConfirmationModal>
</template>

<script setup>
import { ref, defineProps, defineEmits, onBeforeMount, computed } from 'vue'
import ComponenteController from '../../services/ComponenteController'
import { useRouter } from 'vue-router'
import ParteController from '../../services/ParteController'

import ModalPartForm from '../Modals/ModalPartForm.vue'
import ModalPartNotas from '../Modals/ModalPartNotas.vue'
import BaseBadge from '../Base/BaseBadge.vue'
import MinMaxMarcaDevice from '../MinMaxMarcaDevice.vue'

import ConfirmationModal from '../ConfirmationModal.vue'
import { Action } from '../../shared/enums/Action'
import { useNotification } from '@kyvg/vue3-notification'
import spinner from '../../views/components/spinner/spinner.vue'
import * as dayjs from 'dayjs'
import { appStore } from '../../store/app'

import cutString from '../../shared/helpers/cutString'

import Breadcrumbs from '../Breadcrumbs.vue'
import ChartLine from '../Charts/ChartLine.vue'
import ChartBar from '../Charts/ChartBar.vue'

const $appStore = appStore()
const $router = useRouter()

const props = defineProps({
    deviceInfo: { required: true, type: Object },
})

const { notify } = useNotification()

const emit = defineEmits(['onDelete'])

const showModalDeleteComponent = ref(false)
const deleting = ref(false)
const loading = ref(true)

const partes = ref([])
const loadingPartes = ref(true)
const showModalPart = ref(false)
const showModalDeletePart = ref(false)
const actionPart = ref('')
const componenteInfo = ref(null)
const partSelected = ref(null)
const componenteUnidades = ref([])
const unidadSelected = ref(null); 

onBeforeMount(async () => {
    const [componente, partesData] = await Promise.all([
        ComponenteController.getById(props.deviceInfo.id),
        ParteController.list(props.deviceInfo.id),
    ])
    componenteInfo.value = componente
    componenteUnidades.value = componente.unidades
    unidadSelected.value = componenteUnidades.value[0]
    partes.value = partesData
    loadingPartes.value = false
    loading.value = false
})

const onDelete = async () => {
    const ID = props.deviceInfo.id
    deleting.value = true
    try {
        const deviceDeleted = await ComponenteController.delete(ID)
        deleting.value = false
        notify({
            title: 'Dispositivo eliminado',
            text: `El dispositivo ha sido eliminado`,
            type: 'success',
        })
        emit('onDelete', ID)
    } catch (e) {
        deleting.value = false
        notify({
            title: 'Error',
            text: `Ocurrio un error al eliminar el dispositivo`,
            type: 'error',
        })
    }
}

const handleParteModal = (parte) => {
    showModalPart.value = false
    if (actionPart.value == Action.CREAR) {
        partes.value.unshift(parte)
        return
    }
    const index = partes.value.findIndex((part) => part.id == parte.id)
    partes.value[index] = parte
}
const editPart = (parte) => {
    actionPart.value = Action.EDITAR
    partSelected.value = parte

    showModalPart.value = true
}

const deletePart = (parte) => {
    partSelected.value = { ...parte }
    showModalDeletePart.value = true
}

const handleViewHistoricos = () => {
    const componentID = props.deviceInfo.id
    $router.push({ name: 'VerHistoricos', params: { id: componentID } })
}

const onConfirmDeletePart = async () => {
    if (!partSelected.value) {
        return
    }
    showModalDeletePart.value = false

    $appStore.setGlobalLoading(true)

    const COMPONENTE_ID = props.deviceInfo.id
    const PARTE_ID = partSelected.value.id
    try {
        const partDeleted = await ParteController.delete(
            COMPONENTE_ID,
            PARTE_ID
        )
        const index = partes.value.findIndex(
            (part) => part.id == partDeleted.id
        )
        partes.value.splice(index, 1)
        notify({
            title: 'Parte eliminada',
            text: `La parte ${partDeleted.Nombre} ha sido eliminada`,
            type: 'success',
        })
        $appStore.setGlobalLoading(false)
    } catch (e) {
        notify({
            title: 'Error',
            text: `Ocurrio un error al eliminar la parte`,
            type: 'error',
        })
        $appStore.setGlobalLoading(false)
    }
    ParteController.delete
}

const partesFormatted = computed(() => {
    return partes.value.map((parte) => {
        const { created_at, Descripcion, ...data } = parte

        return {
            ...data,
            Fecha: dayjs(created_at).format('DD/MM/YYYY'),
            Descripcion: Descripcion
                ? cutString(Descripcion, 50)
                : 'Sin descripcion',
        }
    })
})

const emptyParts = computed(() => {
    return partes.value.length <= 0
})

const title = computed(() => {
    return `Información del dispositivo:  "${props.deviceInfo.Nombre}" `
})

const deviceIsActive = computed(() => {
    return props.deviceInfo.nodoInfo ? true : false
})

const onSelectUnidadDeMedida = (e) => {
    const value = e.target.value

    unidadSelected.value = componenteUnidades.value.find(
        (unidad) => unidad.unidad_id == value
    )
}
</script>
