<template>
    <BaseBtn 
        @click="showModal"
        :block="true"
        >
        <i class="mr-2 fa-solid fa-plus"></i>
        Agregar Usuario
    </BaseBtn>

    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white md:w-1/3 mx-auto rounded shadow-lg z-50 ">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex flex-col">
                    <div class="card-header flex justify-between items-center">
                        <div class="card-title">
                            <p class="text-xl font-semibold mr-2"> Seleccionar usuarios </p>
                        </div>
                        <BaseBtn
                            sm
                            @click="show = false">
                            <i class="fas fa-times"></i>
                        </BaseBtn>
                    </div>

                    <div class="px-5 py-5 mt-5">
                        <div class="flex justify-center" v-if="loading">
                            <spinner :show="true" :width="12" height="12"></spinner>
                        </div>
                        <div class="w-full" v-else>

                            <div class="flex flex-wrap gap-5  mt-5">
                                <div class="w-full bg-white p-8 rounded-md shadow-md" v-if="usersEmpty && !loading">
                                    <h2 class="text-2xl font-semibold mb-4">
                                        No se encontraron usuarios
                                    </h2>
                                    <p class="text-gray-600">
                                        No hay ningún usuario disponible para asociar a este proceso
                                    </p>
                                </div>
                                <div v-else class="w-full flex flex-col gap-10 overflow-y-auto  h-[50vh]" id="scrollContainer4">
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
                                                
                                            </div>
                                            <div class="flex justify-end mr-4">
                                                    <input type="checkbox"
                                                        class="form-checkbox h-5 w-5 text-primary focus:ring-primary"
                                                        @change="toggleSelection(user, $event.target.checked)" />
                                                </div>
                                        </div>
                                    </Card>
                                </div>

                                <div class="w-full mt-5 flex justify-end">
                                    <BaseBtn 
                                        @click="onSubmit"
                                        >
                                        <i class="mr-2 fa-solid fa-plus"></i>
                                        Agregar
                                    </BaseBtn>
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
import ProcesoController from '../../services/ProcesoController';
import { appStore } from '../../store/app';
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';


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

        setTimeout(() => {
            const container4 = document.getElementById('scrollContainer4');
            new PerfectScrollbar(container4);
        }, 0);

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
        show.value = false;
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
