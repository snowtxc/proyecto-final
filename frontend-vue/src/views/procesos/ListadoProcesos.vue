<template>
    <Breadcrumb parentTitle='Procesos' />
    <div class="flex justify-between ">
        <input class=" bg-gray-100 h-10 px-5 rounded-full text-sm focus:outline-none mb-4 ml-3" type="search" name="search"
            placeholder="Search">
    </div>
    <div class="h-full w-auto flex flex-row space-y-2">
        <div class="w-1/4 h-auto max-h-[740px] flex flex-col space-y-2 overflow-y-auto p-3 ">
            <div>
                <Card
                    class="w-72 h-17 text-white bg-primary hover:text-dark hover:bg-white hover:border hover:border-primary transition-colors duration-150"
                    @click="openModal()">
                    <div class="flex flex-row items-center justify-center ">
                        <p class="font-bold text-xl">
                            Agregar
                        </p>
                    </div>
                </Card>
            </div>
            <Card v-for="(proceso, index) in listaProcesos" :key="proceso.id"
                class="w-72 h-17 hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white"
                :class="{ 'selected-card': index === selectedCardIndex }" @click="selectCard(index)">
                <div class="flex flex-row items-center justify-between">
                    <p :class="{ 'white-text': index === selectedCardIndex }" class="font-bold text-xl">{{ proceso.Nombre }}
                    </p>
                    <div class="space-x-3">
                        <font-awesome-icon :icon="['far', 'pen-to-square']"
                            :class="{ 'white-icon': index === selectedCardIndex }" class="edit" />
                        <font-awesome-icon :icon="['far', 'trash-can']"
                            :class="{ 'white-icon': index === selectedCardIndex }" class="delete" />
                    </div>
                </div>
            </Card>
        </div>
        <div class="w-full flex flex-col">
            <div class="ml-8 h-20">
                <p class="font-bold text-xl" v-if="dataDescripcion !== ''">
                    Descripcion:
                </p>
                <p class="text-sm" v-if="dataDescripcion !== ''">
                    {{ dataDescripcion }}
                </p>
            </div>
            <div class="w-full h-full flex ">
                <div
                    class="w-full h-auto max-h-[730px] ml-5 border-l-[1px] border-gray-300 overflow-y-auto flex justify-center">
                    <p v-if="selectedCardIndex === null">Seleccione un proceso para ver su información.</p>
                    <!-- Mensaje cuando hay proceso seleccionado y lista de etapas vacía -->
                    <div v-else-if="selectedCardIndex !== null && listaEtapas.length === 0"
                        class="w-full flex flex-col items-center p-5 space-y-5">
                        <p>No hay etapas para este proceso.</p>
                        <Card
                            class="w-full h-14 text-white bg-primary hover:text-dark hover:bg-white hover:border hover:border-primary transition-colors duration-150"
                            @click="openModalEtapas()">
                            <div class="flex flex-row items-center justify-center ">
                                <p class="font-bold text-xl">
                                    Agregar
                                </p>
                            </div>
                        </Card>
                    </div>
                    <!-- Contenido cuando hay proceso seleccionado y lista de etapas no está vacía -->

                    <div v-else class="w-full h-auto max-h-[730px] space-y-4   overflow-y-auto p-5 ">
                        <Card
                            class="w-full h-14 text-white bg-primary hover:text-dark hover:bg-white hover:border hover:border-primary transition-colors duration-150"
                            @click="openModalEtapas()">
                            <div class="flex flex-row items-center justify-center ">
                                <p class="font-bold text-xl">
                                    Agregar
                                </p>
                            </div>
                        </Card>
                        <Card v-for="etapa in listaEtapas" :key="etapa.id"
                            class="h-14 hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white">
                            <div class="flex flex-row items-center justify-between">
                                <p class="font-bold text-xl">{{ etapa.Nombre }}</p>
                                <div class="space-x-3">
                                    <font-awesome-icon :icon="['far', 'pen-to-square']" class="edit" />
                                    <font-awesome-icon :icon="['far', 'trash-can']" class="delete" />
                                </div>
                            </div>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @closeModal="closeModal()">
            <div class="flex flex-col items-start mb-8">
                <p class="font-bold text-xl">Nuevo Proceso</p>
            </div>
            <div class="space-y-4 mb-8">
                <input v-model="nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcion" class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded"
                    placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn rounded class="border border-primary text-primary hover:bg-primary hover:text-white h-10"
                    @click="validateFields">
                    Crear
                </BaseBtn>
            </div>
        </Modal>
        <Modal :show="showModalEtapas" @closeModal="closeModalEtapas()">
            <div class="flex flex-col items-start mb-8">
                <p class="font-bold text-xl">Nuevo Etapa</p>
            </div>
            <div class="space-y-4 mb-8">
                <input v-model="nombreEtapa" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="errors.nombre" class="text-red-500 text-xs">{{ errors.nombre }}</span>
                <textarea v-model="descripcionEtapa"
                    class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded" placeholder="Descripcion" />
                <span v-if="errors.descripcion" class="text-red-500 text-xs">{{ errors.descripcion }}</span>

            </div>
            <div class="flex justify-end">
                <BaseBtn rounded class="border border-primary text-primary hover:bg-primary hover:text-white h-10"
                    @click="validateFieldsEtapa">
                    Crear
                </BaseBtn>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Breadcrumb from '@/components/Breadcrumbs.vue';
import Card from '@/components/Card/Card.vue';
import ProcesoController from '@/services/ProcesoController.js';
import EtapaController from '@/services/EtapaController.js';
import Modal from '../components/Modals/Modal.vue';

const showModal = ref(false);
const showModalEtapas = ref(false);
const nombre = ref('');
const descripcion = ref('');
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


const selectCard = (index) => {
    selectedCardIndex.value = index;
    const procesoId = listaProcesos.value[index].id;
    cargarEtapas(procesoId);
};

const openModal = () => {
    showModal.value = true;
};

const openModalEtapas = () => {
    showModalEtapas.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const closeModalEtapas = () => {
    showModalEtapas.value = false;
};

const listaProcesosPromise = ProcesoController.listaProcesos();



listaProcesosPromise
    .then((response) => {
        listaProcesos.value = response.data.reverse();; // Asigna los datos a la variable reactiva
    })
    .catch((error) => {
        console.error('Error al obtener la lista de procesos:', error);
    });

const validateFields = () => {
    errors.value.nombre = nombre.value ? '' : 'Ingrese un Nombre';

    if (descripcion.value.trim().length < 10 || descripcion.value == '') {
        errors.value.descripcion = 'La descripción debe tener al menos 10 caracteres';
    } else {
        errors.value.descripcion = '';
    }

    if (!errors.value.nombre && !errors.value.descripcion) {
        crearNuevoProceso();
    }
};

const validateFieldsEtapa = () => {
    errors.value.nombre = nombreEtapa.value ? '' : 'Ingrese un Nombre';

    if (descripcionEtapa.value.trim().length < 10 || descripcionEtapa.value == '') {
        errors.value.descripcion = 'La descripción debe tener al menos 10 caracteres';
    } else {
        errors.value.descripcion = '';
    }

    if (!errors.value.nombre && !errors.value.descripcion) {
        crearNuevaEtapa();
    }
};

const cargarEtapas = async (procesoId) => {
    try {
        dataDescripcion.value = listaProcesos.value[selectedCardIndex.value].Descripcion;
        const response = await EtapaController.listaEtapas(procesoId);
        listaEtapas.value = response.data.reverse();

    } catch (error) {
        console.error('Error al cargar las etapas:', error);
    }
};

const crearNuevoProceso = () => {
    ProcesoController.nuevoProceso(nombre.value, descripcion.value)
        .then(() => {
            cargarListaProcesos();
            closeModal();
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
        nombre.value = '';
  descripcion.value = '';
};

const crearNuevaEtapa = () => {
    const procesoId = listaProcesos.value[selectedCardIndex.value].id;
    EtapaController.nuevaEtapa(nombreEtapa.value, descripcionEtapa.value, procesoId)
        .then(() => {
            cargarEtapas(procesoId);
            closeModalEtapas();
        })
        .catch((error) => {
            console.error('Error al crear el proceso:', error);
            alert('Hubo un error al crear el proceso.');
        });
    nombreEtapa.value = '';
    descripcionEtapa.value = '';
};

const cargarListaProcesos = () => {
    // Llama al controlador para obtener la lista actualizada de procesos
    ProcesoController.listaProcesos()
        .then((response) => {
            listaProcesos.value = response.data.reverse();
            selectedCardIndex.value = 0;
            dataDescripcion.value = listaProcesos.value[selectedCardIndex.value].Descripcion;
        })
        .catch((error) => {
            console.error('Error al obtener la lista de procesos:', error);
        });
};

</script>


<style scoped>
.delete:hover {
    color: #ef4444;
}

.edit:hover {
    color: #f59e0b;
}

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
