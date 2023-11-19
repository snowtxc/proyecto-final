<template>
    <div class="card-header flex justify-between items-center">
        <div class="card-title">
            <p class="text-xl font-semibold mr-2"> Procesos </p>
        </div>
        <BaseBtn v-if="rol == 'Administrador'" @click="showModalProceso">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo Proceso
        </BaseBtn>
    </div>

    <div class="flex gap-10 mt-4">
        <div class="min-w-[350px]">
            <BaseCard>
                <input
                    class="w-full p-2 mb-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                    type="text" placeholder="Buscar" v-model="searchTerm" @input="filterProcesos" />


                <div class="h-full w-full flex flex-col md:flex-row space-y-2 ">
                    
                    <div class="h-auto max-h-96 md:max-h-[700px] flex flex-col items-center space-y-2 overflow-y-auto p-3"
                        id="scrollContainer">
                        <div v-if="loading == false && listaProcesos.length == 0" 
                            class="w-full">
                                <div class="w-full bg-white p-8 rounded-md shadow-md">
                                    <h2 class="text-2xl font-semibold mb-4">
                                        No se encontraron procesos
                                    </h2>
                                </div>
                            </div>

                        <spinner :show="showSpinnerProcesos"></spinner>
                        <Card v-for="(proceso, index) in listaProcesos" :key="proceso.id"
                            class="w-full h-17 hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white"
                            :class="{ 'selected-card': index === selectedCardIndex }" @click="selectCard(index)">
                            <div class="flex flex-row items-center justify-between">
                                <p :class="{ 'white-text': index === selectedCardIndex }" class="font-bold text-xl">{{
                                    proceso.Nombre }}
                                </p>
                                <div class="ml-2 w-20 flex justify-end" v-if="rol == 'Administrador'">
                                    <font-awesome-icon :icon="['far', 'pen-to-square']"
                                        :class="{ 'white-icon': index === selectedCardIndex }"
                                        class="h-5 mr-2 hover:text-primary"
                                        @click.stop="showModalEditar = true, procesoId = proceso.id, nombre = proceso.Nombre, descripcion = proceso.Descripcion" />
                                    <font-awesome-icon :icon="['far', 'trash-can']"
                                        :class="{ 'white-icon': index === selectedCardIndex }"
                                        class="h-5 hover:text-primary" @click.stop="openModalProcesosConfirm(proceso.id)" />
                                </div>
                            </div>
                        </Card>
                    </div>
                </div>
            </BaseCard>
        </div>

        <div class="w-full flex flex-col">

            <div class="md:ml-5 h-20 md:border-l-[1px] md:border-gray-300">
                <p class="font-bold text-xl ml-5" v-if="dataDescripcion !== ''">
                    Descripcion:
                </p>
                <p class="text-sm ml-5" v-if="dataDescripcion !== ''">
                    {{ dataDescripcion }}
                </p>
            </div>

            <div class="w-full flex justify-center h-full ">
                <p v-if="selectedCardIndex === null" class="text-center">Seleccione un proceso para ver su información.</p>

                <div class="w-full h-full flex flex-col md:flex-row " v-else>
                    <div
                        class="w-full h-auto max-h-96 md:max-h-[730px] md:ml-5 md:border-l-[1px] md:border-gray-300 overflow-y-auto flex justify-center">
                        <div v-if="selectedCardIndex !== null && listaEtapas.length === 0"
                            class="w-full flex flex-col items-center p-5 space-y-5">
                            <BaseBtn @click="showModalEtapa" :block="true" v-if="showSpinnerEtapas == false">
                                <i class="mr-2 fa-solid fa-plus"></i>
                                Nueva Etapa
                            </BaseBtn>
                            <spinner :show="showSpinnerEtapas"></spinner>
                            
                            <div v-if="showSpinnerEtapas == false" class="w-full">
                                <div class="w-full bg-white p-8 rounded-md shadow-md">
                                    <h2 class="text-2xl font-semibold mb-4">
                                        No se encontraron etapas
                                    </h2>
                                    <p class="text-gray-600">
                                        No hay etapas en este proceso
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-else id="scrollContainer2"
                            class="w-full h-auto max-h-[730px] space-y-4 overflow-y-auto p-5 flex flex-col items-center ">
                            <BaseBtn @click="showModalEtapa" :block="true" v-if="showSpinnerEtapas == false">
                                <i class="mr-2 fa-solid fa-plus"></i>
                                Nueva Etapa
                            </BaseBtn>
                            <spinner :show="showSpinnerEtapas"></spinner>
                            <Card v-if="showSpinnerEtapas == false" v-for="etapa in listaEtapas" :key="etapa.id"
                                @click="navigateToEtapas(etapa.id);"
                                class=" w-full hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white">
                                <div class="flex flex-row items-center justify-between">
                                    <p class="font-bold text-xl">{{ etapa.Nombre }}</p>
                                    <div class="ml-2 w-20 flex justify-end">
                                        <font-awesome-icon :icon="['far', 'pen-to-square']"
                                            class="w-5 h-5 mr-2 hover:text-primary"
                                            @click.stop="showModalEditarEtapas = true, etapaId = etapa.id, nombreEtapa = etapa.Nombre, descripcionEtapa = etapa.Descripcion" />
                                        <font-awesome-icon v-if="rol == 'Administrador'" :icon="['far', 'trash-can']"
                                            class="w-5 h-5 hover:text-primary" @click.stop="openModalConfirm(etapa.id)" />
                                    </div>
                                </div>
                            </Card>
                        </div>
                    </div>
                    <div class="w-full flex justify-center">
                        <div class="p-5 space-y-5"> 
                            <spinner :show="loadingUsers"></spinner>    
                        </div>
                        
                        <ListUsuarioProcesos v-if="!loadingUsers" :procesoId="procesoIdSelected" :usuarios="usuariosArr"
                            @onRemoveUser="removeUserFromProcess" @onAddUsers="addUsers"></ListUsuarioProcesos>
                    </div>
                </div>
            </div>

        </div>
        <Modal :title="'Nuevo Proceso'" :show="showModal" @closeModal="showModal = false">
            <div class="space-y-4 mb-8">
                <input v-model="nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcion" class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded"
                    placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn @click="validateFields(0)">
                    Guardar
                </BaseBtn>
            </div>
        </Modal>
        <Modal :title="'Editar Proceso'" :show="showModalEditar" @closeModal="showModalEditar = false">
            <div class="space-y-4 mb-8">
                <input v-model="nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcion" class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded"
                    placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn @click="validateFields(1)">
                    Guardar
                </BaseBtn>
            </div>
        </Modal>
        <Modal :title="'Nueva Etapa'" :show="showModalEtapas" @closeModal="showModalEtapas = false">
            <div class="space-y-4 mb-8">
                <input v-model="nombreEtapa" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcionEtapa"
                    class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded" placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn @click="validateFieldsEtapa(0)">
                    Guardar
                </BaseBtn>
            </div>
        </Modal>
        <Modal :title="'Editar Etapa'" :show="showModalEditarEtapas" @closeModal="showModalEditarEtapas = false">
            <div class="space-y-4 mb-8">
                <input v-model="nombreEtapa" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcionEtapa"
                    class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded" placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn @click="validateFieldsEtapa(1)">
                    Guardar
                </BaseBtn>
            </div>
        </Modal>

        <ConfirmationModal v-if="showModalConfirm" :show="showModalConfirm" title="Eliminar etapa"
            message="Seguro deseas eliminar esta etapa?" @cancel="showModalConfirm = false" @confirm="confirmDelete">
        </ConfirmationModal>

        <ConfirmationModal v-if="showModalProcesosConfirm" :show="showModalProcesosConfirm" title="Eliminar proceso"
            message="Seguro deseas eliminar este proceso?, se eliminarian las etapas asociadas" @cancel="showModalProcesosConfirm = false" @confirm="confirmProcesosDelete">
        </ConfirmationModal>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { appStore } from "@/store/app.js";
import Card from '@/components/Card/Card.vue';
import ProcesoController from '@/services/ProcesoController.js';
import EtapaController from '@/services/EtapaController.js';
import Modal from '../components/Modals/Modal.vue';
import spinner from '../components/spinner/spinner.vue';
import ConfirmationModal from '../../components/ConfirmationModal.vue';
import { useRouter } from 'vue-router';
import { useNotification } from '@kyvg/vue3-notification'
import ListUsuarioProcesos from '@/components/List/ListUsuarioProcesos.vue';

import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

onMounted(async () => {
    const container = document.getElementById('scrollContainer');
    new PerfectScrollbar(container);
})

const { notify } = useNotification()

const router = useRouter();

const showModal = ref(false);
const showModalEtapas = ref(false);
const showModalConfirm = ref(false);
const showModalProcesosConfirm = ref(false);
const showModalEditar = ref(false);
const showModalEditarEtapas = ref(false);
const showSpinnerProcesos = ref(false);
const showSpinnerEtapas = ref(false);
const nombre = ref('');
const descripcion = ref('');
const etapaId = ref('');
const procesoId = ref('');
const procesoIdSelected = ref(null);
const nombreEtapa = ref('');
const descripcionEtapa = ref('');
const dataDescripcion = ref('');
const selectedCardIndex = ref(null);
const errors = ref({
    nombre: '',
    descripcion: '',
});
const listaProcesos = ref([]);
const listaEtapas = ref([]);
const listaProcesosBackup = ref([]);
const searchTerm = ref('');

const usuariosArr = ref([])
const loadingUsers = ref(false);

const loading = ref(true);

const $appstore = appStore();

const rol = $appstore.getUserData?.rol;

const filterProcesos = () => {
    if (searchTerm.value === '') {
        cargarListaProcesos(null);
    } else {
        listaProcesos.value = listaProcesosBackup.value.filter((proceso) =>
            proceso.Nombre.toLowerCase().includes(searchTerm.value.toLowerCase())
        );
        selectedCardIndex.value = null
        dataDescripcion.value = ''
    }
};

const selectCard = (index) => {
    selectedCardIndex.value = index;
    const procesoId = listaProcesos.value[index].id;
    procesoIdSelected.value = procesoId;
    cargarEtapas(procesoId);
    loadUsuariosByProceso(procesoId);
};

const openModalConfirm = (Id) => {
    etapaId.value = Id;
    showModalConfirm.value = true;
};

const openModalProcesosConfirm = (Id) => {
    procesoId.value = Id;
    showModalProcesosConfirm.value = true;
};

const showModalProceso = () => {
    nombre.value = '';
    descripcion.value = '';
    showModal.value = true;
};

const showModalEtapa = () => {
    nombreEtapa.value = '';
    descripcionEtapa.value = '';
    showModalEtapas.value = true;
};

const loadUsuariosByProceso = async (procesoId) => {
    loadingUsers.value = true;
    try {
        const usuarios = await ProcesoController.getUsuariosByProceso(procesoId);
        usuariosArr.value = usuarios;
        loadingUsers.value = false;

    } catch (e) {
        loadingUsers.value = false;
        console.log(e);
    }

}

const confirmDelete = () => {
    eliminarEtapa(etapaId.value);
    showModalConfirm.value = false;
};

const confirmProcesosDelete = async () => {
    eliminarProceso(procesoId)
    showModalProcesosConfirm.value = false;
};
const listaProcesosPromise = ProcesoController.listaProcesos();


$appstore.setGlobalLoading(true)
listaProcesosPromise
    .then((response) => {
        listaProcesos.value = response.data
        listaProcesosBackup.value = response.data.reverse();
        $appstore.setGlobalLoading(false)
        loading.value = false;
    })
    .catch((error) => {
        console.error('Error al obtener la lista de procesos:', error);
        $appstore.setGlobalLoading(false)
        loading.value = false;
    });

const validateFields = (editar) => {
    errors.value.nombre = nombre.value ? '' : 'Ingrese un Nombre';

    if (descripcion.value.trim().length < 10 || descripcion.value == '') {
        errors.value.descripcion = 'La descripción debe tener al menos 10 caracteres';
    } else {
        errors.value.descripcion = '';
    }

    if (!errors.value.nombre && !errors.value.descripcion) {
        if (editar == 0) {
            crearNuevoProceso();
            errors.value.nombre = false;
            errors.value.descripcion = false;
        }
        if (editar == 1) {
            procesoId.value = listaProcesos.value[selectedCardIndex.value].id;
            console.log(procesoId)
            EditarProceso(procesoId.value);
            errors.value.nombre = false;
            errors.value.descripcion = false;
        }
    }

};

const validateFieldsEtapa = (editar) => {
    errors.value.nombre = nombreEtapa.value ? '' : 'Ingrese un Nombre';

    if (!descripcionEtapa.value) {
        errors.value.descripcion = 'La descripción debe tener al menos 10 caracteres';
    } else {
        if (descripcionEtapa.value.trim().length < 10 || descripcionEtapa.value == '') {
            errors.value.descripcion = 'La descripción debe tener al menos 10 caracteres';
        } else {
            errors.value.descripcion = '';
        }
    }

    if (!errors.value.nombre && !errors.value.descripcion) {
        if (editar == 0) {
            crearNuevaEtapa();
            errors.value.nombre = false;
            errors.value.descripcion = false;
        }
        if (editar == 1) {
            EditarEtapa(etapaId.value);
            errors.value.nombre = false;
            errors.value.descripcion = false;
        }

    }
};

const cargarEtapas = async (procesoId) => {
    showSpinnerEtapas.value = true;
    try {
        dataDescripcion.value = listaProcesos.value[selectedCardIndex.value].Descripcion;
        const response = await EtapaController.listaEtapas(procesoId);
        listaEtapas.value = response.data.reverse();
        showSpinnerEtapas.value = false;

        setTimeout(() => {
            const container2 = document.getElementById('scrollContainer2');
            new PerfectScrollbar(container2);
        }, 0);
    } catch (error) {
        console.error('Error al cargar las etapas:', error);
        showSpinnerEtapas.value = false;
    }
};

const crearNuevoProceso = () => {
    ProcesoController.nuevoProceso(nombre.value, descripcion.value)
        .then((response) => {
            cargarListaProcesos(0);
            cargarEtapas(selectedCardIndex.value)
            showModal.value = false;
            loadUsuariosByProceso(response.data.id)
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
    nombre.value = '';
    descripcion.value = '';
};

const EditarProceso = (id) => {
    ProcesoController.editarProceso(id, nombre.value, descripcion.value)
        .then(() => {
            cargarListaProcesos(0);
            showModalEditar.value = false;
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
    nombre.value = '';
    descripcion.value = '';
    errors = false;
};

const EditarEtapa = (id) => {
    const idProcesos = listaProcesos.value[selectedCardIndex.value].id;
    EtapaController.editarEtapa(id, nombreEtapa.value, descripcionEtapa.value, idProcesos)
        .then(() => {
            cargarEtapas(idProcesos);
            showModalEditarEtapas.value = false;
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
    nombreEtapa.value = '';
    descripcionEtapa.value = '';
};

const crearNuevaEtapa = () => {
    const procesoId = listaProcesos.value[selectedCardIndex.value].id;
    EtapaController.nuevaEtapa(nombreEtapa.value, descripcionEtapa.value, procesoId)
        .then(() => {

            cargarEtapas(procesoId);
            showModalEtapas.value = false;
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
    nombreEtapa.value = '';
    descripcionEtapa.value = '';
};

const cargarListaProcesos = (cardIndex) => {
    showSpinnerProcesos.value = true;
    ProcesoController.listaProcesos()
        .then((response) => {
            listaProcesos.value = response.data.reverse();
            selectedCardIndex.value = cardIndex;
            if (selectedCardIndex.value !== null) {
                dataDescripcion.value = listaProcesos.value[selectedCardIndex.value].Descripcion;
            }
            showSpinnerProcesos.value = false;

        })
        .catch((error) => {
            console.error('Error al obtener la lista de procesos:', error);
            showSpinnerProcesos.value = false;
        });
};

const eliminarEtapa = async (etapaId) => {
    try {
        await EtapaController.eliminarEtapa(etapaId);
        const procesoId = listaProcesos.value[selectedCardIndex.value].id;
        cargarEtapas(procesoId);
    } catch (error) {
        console.error('Error al eliminar la etapa:', error);
    }
};

const eliminarProceso = async () => {
    try {
        await ProcesoController.eliminarProceso(procesoId.value);
        cargarListaProcesos(selectedCardIndex.value)
        cargarEtapas(selectedCardIndex.value)
        console.log(selectedCardIndex.value)
        const idProcesos = listaProcesos.value[selectedCardIndex.value + 1].id;
        procesoIdSelected.value = idProcesos
        loadUsuariosByProceso(idProcesos)
    } catch (error) {
        console.error('Error al eliminar la etapa:', error);
    }
};

const navigateToEtapas = (etapaId) => {
    const procesoId = listaProcesos.value[selectedCardIndex.value].id;
    router.push({ name: 'editarEtapa', params: { procesoId, etapaId } });
};


const removeUserFromProcess = async (userId) => {
    const procesoId = listaProcesos.value[selectedCardIndex.value].id;
    $appstore.setGlobalLoading(true);
    try {
        const userRemoved = await ProcesoController.removeUserFromProcess(procesoId, userId);
        const index = usuariosArr.value.findIndex(user => user.id === userId);
        usuariosArr.value.splice(index, 1);
        $appstore.setGlobalLoading(false);
        notify({
            title: 'Success',
            text: 'Usuario removido del proceso',
            type: 'success'
        })
    } catch (e) {
        $appstore.setGlobalLoading(false);
        notify({
            title: 'Error',
            text: 'No se pudo remover el usuario del proceso',
            type: 'error'
        })

    }
}


const addUsers = async (users) => {
    usuariosArr.value = [...usuariosArr.value, ...users];
}


</script>


<style scoped>
.selected-card {
    background-color: #25CEDE;
    /* Color de fondo seleccionado */
}

.white-text {
    color: white;
    /* Cambia el color del texto a blanco */
}

.white-icon {
    color: white;
    /* Cambia el color de los iconos a blanco */
}
</style>
