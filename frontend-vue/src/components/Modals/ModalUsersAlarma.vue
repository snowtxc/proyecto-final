<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50" >
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white w-1/3 mx-auto rounded shadow-lg z-50 overflow-y-auto h-[70vh]" >
            <div class="modal-content py-4 text-left px-6">
                <div class="flex flex-col">

                    <div class="card-header flex justify-between items-center">         
                        <div class="card-title">
                            <p class="text-xl font-semibold "> Usuarios </p>
                        </div>
                        <BaseBtn
                            sm
                            @click="$emit('onClose')">
                            <i class="fas fa-times"></i>
                        </BaseBtn>
                    </div>
                         
                    <div class="flex flex-wrap mt-2 text-center">
                        <div class="w-full text-center mt-4">
                            <spinner v-if="loading" :show="loading"></spinner>
                        </div>
                        <div
                            class="w-full bg-white rounded-md shadow-md "
                            v-if="users.length == 0 && !loading"
                            >
                            <h2 class="text-2xl font-semibold mt-4">
                                No se encontraron usuarios
                            </h2>
                            <p class="text-gray-600 m-4">
                                Ningún usuario recibió esta alarma
                            </p>
                        </div>
                        <div v-else class="w-full flex flex-col ">
                            <Card v-for="user in users" :key="user.id" class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl">
                                    <div class="flex m-2">
                                        <img :src="user.profileImage ? user.profileImage : imageProfileDefault" alt="User Image" class="w-12 h-12 rounded-full mr-4 object-cover" />

                                        <div class="flex flex-col items-start">
                                                <p class="font-bold text-xl">{{ user.name }}</p>  
                                                <p>{{ user.email }}</p>
                                                <p class="text-gray-500"> {{ user.rol }} </p>
                                        </div>
                                    </div>
                            </Card>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import AlarmaController from '../../services/AlarmaController';
import { appStore } from '../../store/app';
import spinner from '../../views/components/spinner/spinner.vue';

const $appStore = appStore();

export default {
    props: {
      alarmaId: Number,
      show: Boolean
    },
    data() {
      return {
        users: [],
        loading: true
      };
    },
    created() {
        this.getUsers();
    },
    methods: {
        getUsers(){
            AlarmaController.getUsers(this.alarmaId).then((response) => {
                if(response.status == 200){
                    this.users = response.data;
                }
                this.loading = false;
            });
        }
    },
    computed: {
        imageProfileDefault(){
            return $appStore.getImageProfileDefault;
        }
    },
    components: { spinner }
}

</script>
