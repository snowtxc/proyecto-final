<script setup>
import { ref, onBeforeMount, computed, onMounted } from "vue";
import TipoComponenteController from "../../services/TipoComponenteController.js";
import { appStore } from "../../store/app";
import { Action } from "../../shared/enums/Action";
import BaseBtn from "../../components/Base/BaseBtn.vue";
import ModalTipoComponente from "../../components/Modals/ModalTipoComponente.vue";
import ConfirmationModal from "../../components/ConfirmationModal.vue";
import { useNotification } from "@kyvg/vue3-notification";

import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

onMounted(async () => {
    const container = document.getElementById('scrollContainer');
    new PerfectScrollbar(container);
    
})

const { notify } = useNotification();
const $appStore = appStore();

const tiposComponentes = ref([]);
const action = ref(null);
const showModal = ref(false);
const showModalDelete = ref(false);
const tipoComponenteSelected = ref(null);
const debounceSearch = ref(null);
const loading = ref(true);

const tiposComponentesEmpty = computed(() => {
    return tiposComponentes.value.length <= 0;
})


onBeforeMount(() => {
    getTiposComponentes();
});

const getTiposComponentes = async (search = null) => {
    $appStore.setGlobalLoading(true);
    tiposComponentes.value = await TipoComponenteController.getAll(search);
    $appStore.setGlobalLoading(false);
    loading.value = false;
}

const addNewTypeComponent = () => {
    action.value = Action.CREAR;
    showModal.value = true;
}

const handleTipoComponenteModal = (typeComponent) => {
    console.log(typeComponent)
    showModal.value = false;
    getTiposComponentes();
    /*if (action.value == Action.CREAR) {
        showModal.value = false;
        tiposComponentes.value.unshift(typeComponent);
        return;
    }
    const index = tiposComponentes.value.findIndex(item => item.id == typeComponent.id);
    tiposComponentes.value[index] = typeComponent;*/


}

const onShowModalDelete = (tipoComponente) => {
    tipoComponenteSelected.value = tipoComponente;
    showModalDelete.value = true;
}

const onDelete = async () => {
    if (!tipoComponenteSelected.value) {
        return;
    }
    showModalDelete.value = false;
    const { id } = tipoComponenteSelected.value;
    $appStore.setGlobalLoading(true);
    try {
        const tipoComponentDeleted = await TipoComponenteController.delete(id);
        const index = tiposComponentes.value.findIndex(item => item.id == tipoComponentDeleted.id);
        tiposComponentes.value.splice(index, 1);
        $appStore.setGlobalLoading(false);
        notify({
            title: 'Tipo de Componente removido',
            text: 'El tipo de componente se ha removido correctamente',
            type: 'success'
        });

    } catch (e) {
        console.log(e)
        $appStore.setGlobalLoading(false);

        notify({
            title: 'Error',
            text: `Ocurrio un error al eliminar tipo componente`,
            type: 'error'
        })
    }
}

const handleSearch = ($event) => {
    const value = $event.target.value;
    if (debounceSearch.value) {
        clearTimeout(debounceSearch.value)
    }
    debounceSearch.value = setTimeout(() => {
        getTiposComponentes(value)
    }, 500)
}

const edit = (tipoComponente) => {
    action.value = Action.EDITAR;
    tipoComponenteSelected.value = tipoComponente;
    showModal.value = true;
}




</script>


<template>
    <div class="card-header flex justify-between items-center">         
        <div class="card-title">
            <p class="text-xl font-semibold "> Tipos de Dispositivo </p>
        </div>
        <BaseBtn
            @click="addNewTypeComponent">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo Tipo
        </BaseBtn>
    </div>    

    <div>
        <input
            class="min-w-[50vh] p-2 m-4 text-sm text-gray-900 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
            type="text"
            placeholder="Buscar"
            @input="handleSearch"
                        
        />

        <div class="max-h-[70vh] overflow-y-auto" id="scrollContainer">
            <div class="w-full bg-white p-8 rounded-md shadow-md" v-if="tiposComponentesEmpty && !loading">

                <p class="text-gray-600 text-center">
                    No se encontro ningun tipo de componente
                </p>
            </div>
            <div v-else>
                <div v-for="tipoComponente in tiposComponentes" :key="tipoComponente.id"
                    class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl px-5 h-24">
                    <div class="flex">
                        <img class="w-16 h-16 object-fill" :src="tipoComponente.Imagen" alt="" />
                    </div>
                    <div class="flex pl-2 flex-1">
                        <div class="flex flex-grow flex-row self-center justify-between lg:items-center lg:flex-row">
                            <p class="mx-4"> {{ tipoComponente.Nombre }} </p>
                            <div class="w-24 flex flex-row">
                                <font-awesome-icon :icon="[
                                    'far',
                                    'pen-to-square',
                                ]" class="w-5 h-5 m-4 hover:text-primary" @click="edit(tipoComponente)" />
                                <font-awesome-icon :icon="['far', 'trash-can']" @click="onShowModalDelete(tipoComponente)"
                                    class="w-5 h-5 m-4 hover:text-primary" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <ModalTipoComponente v-if="showModal" :show="showModal" :action="action" @onTipoComponente="handleTipoComponenteModal"
        @onClose="showModal = false" :tipoComponenteData="tipoComponenteSelected"></ModalTipoComponente>

    <ConfirmationModal v-if="showModalDelete" :show="showModalDelete" title="Seguro deseas eliminar este tipo de componente"
        warning="Atencion.Al eliminar el tipo de componente, se eliminar todos los componentes asociados con su datos relacionados"
        @cancel="showModalDelete = false" @confirm="onDelete"></ConfirmationModal>
</template>

