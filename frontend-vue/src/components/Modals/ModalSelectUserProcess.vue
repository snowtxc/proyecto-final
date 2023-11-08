<template>
    <Card
        class="card w-full h-14 text-white bg-primary hover:text-dark hover:bg-white hover:border hover:border-primary transition-colors duration-150 flex items-center justify-center rounded-lg shadow-sm"
        @click="showModal">
        <p class="font-bold text-xl cursor-pointer">Agregar Nuevo Usuario</p>
    </Card>

    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white md:w-1/3 mx-auto rounded shadow-lg z-50 overflow-y-auto h-[70vh]">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex flex-col">
                    <div class="flex justify-end items-center">
                        <button class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            @click="show = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="px-5 py-5 mt-5">
                        <div class="flex justify-center" v-if="loading">
                            <spinner :show="true" :width="12" height="12"></spinner>
                        </div>
                        <div class="w-full" v-else>
                            <div class="w-full flex justify-between items-center">
                                <Breadcrumb parentTitle="Seleccionar usuario" />
                            </div>

                            <div class="flex flex-wrap gap-5  mt-5">
                                <div class="w-full bg-white p-8 rounded-md shadow-md" v-if="usersEmpty && !loading">
                                    <h2 class="text-2xl font-semibold mb-4">
                                        No se encontraron usuarios
                                    </h2>
                                    <p class="text-gray-600">
                                        No existe ningun usuario para agregar al grupo de este proceso
                                    </p>
                                </div>
                                <div v-else class="w-full flex flex-col gap-10">
                                    <Card v-for="user in users" :key="user.id"
                                        class="w-full hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white p-1 rounded-md	cursor-pointer">
                                        <div class="flex flex-row items-center justify-between">
                                            <div class="flex items-center">
                                                <img :src="user.profileImage ? user.profileImage : imageProfileDefault"
                                                    alt="User Image" class="w-12 h-12 rounded-full mr-2 object-cover" />

                                                <div class="flex flex-col">
                                                    <p class="font-bold text-xl">{{ user.name }}</p>
                                                    <p>{{ user.email }}</p>
                                                    <p class="text-gray-500">{{ user.rol }}</p>
                                                </div>
                                                <input type="checkbox"
                                                    class="form-checkbox h-5 w-5 text-primary focus:ring-primary"
                                                    @change="toggleSelection(user, $event.target.checked)" />

                                            </div>
                                        </div>
                                    </Card>
                                </div>

                                <div class="w-full mt-5">
                                    <Card
                                        class="cursor-pointer w-full h-14 text-white bg-primary hover:text-dark hover:bg-white hover:border hover:border-primary transition-colors duration-150 flex items-center justify-center"
                                        @click="onSubmit">
                                        <p class="font-bold text-xl">Agregar</p>
                                    </Card>
                                </div>
                                <!--
                                <div class="w-full flex justify-center mt-2">
                                    <infinite-loading
                                        @infinite="loadMoreData"
                                        v-if="hasMoreData"
                                    ></infinite-loading>
                                </div>
                                -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue'
import spinner from '../../views/components/spinner/spinner.vue';
import Breadcrumb from '../Breadcrumbs.vue';
import ProcesoController from '../../services/ProcesoController';
import { appStore } from '../../store/app';


const $appStore = appStore();
const show = ref(false);
const props = defineProps({
    processId: {
        type: Number,
        required: true
    }
});

const users = ref([]);
const usersSelected = ref([]);
const emit = defineEmits(['onAddUsers'])


const loading = ref(false);

const showModal = () => {
    show.value = true;
    getUsers();
}

const getUsers = async () => {
    loading.value = true;
    const processID = props.processId;
    try {
        const data = await ProcesoController.getUsersNotInProcess(processID);
        users.value = data;
        loading.value = false;
    } catch (e) {
        console.log(e);
        loading.value = false;
    }
}

const toggleSelection = async (user, isChecked) => {
    if (isChecked) {
        usersSelected.value.push(user);
        return;
    }
    usersSelected.value = usersSelected.value.filter(u => u.id !== user.id);
}

const onSubmit = () => {
    if (usersSelected.value.length === 0) {
        return;
    }
    show.value = false;
    const usuarios = [...usersSelected.value];
    usersSelected.value = [];
    emit("onAddUsers", usuarios);
}


const usersEmpty = computed(() => {
    return users.value.length === 0;
});

const imageProfileDefault = computed(() => {
    return $appStore.getImageProfileDefault;
});

</script>

<style lang="scss" scoped>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 20px 1px rgb(0 0 0 / 6%), 0 1px 4px rgb(0 0 0 / 8%);
    // box-shadow:rgb(149 157 165 / 20%) 0px 8px 24px;

}
</style>
