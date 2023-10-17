<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50" >
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white w-1/3 mx-auto rounded shadow-lg z-50 overflow-y-auto h-[70vh]" >
            <div class="modal-content py-4 text-left px-6">
                <div class="flex flex-col">

                    <div class="card-header">
                        <div class="col-2 flex justify-end items-center">
                            <button
                                class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                @click="$emit('onClose')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="col-10">                       
                            <div class="card-title">Usuarios</div>                   
                        </div>
                    </div>
                    <div class="px-5 ">
                        <div class="w-full">                          
                            <div
                                class="flex flex-wrap gap-5  mt-5"
                            >
                                <div
                                    class="w-full bg-white p-8 rounded-md shadow-md"
                                    v-if="users.length == 0 && !loading"
                                >
                                    <h2 class="text-2xl font-semibold mb-4">
                                        No se encontraron usuarios
                                    </h2>
                                    <p class="text-gray-600">
                                        Ningun usuario recibio esta alarma
                                    </p>
                                </div>
                                <div v-else class="w-full flex flex-col gap-10">
                                    <Card v-for="user in users" :key="user.id" class="w-full hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white p-1 rounded-md	cursor-pointer">
                                        <div class="flex flex-row items-center justify-between">
                                            <div class="flex items-center">
                                            <img :src="user.profileImage ? user.profileImage : imageProfileDefault" alt="User Image" class="w-12 h-12 rounded-full mr-2 object-cover" />

                                            <div class="flex flex-col">
                                                <p class="font-bold text-xl">{{ user.name }}</p>  
                                                <p>{{ user.email }}</p>
                                                <p class="text-gray-500"> {{ user.rol }} </p>
                                            </div>
                                            </div>
                                        </div>
                                    </Card>
                                </div>
                            </div>
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
        $appStore.setGlobalLoading(true);
        this.getUsers();
    },
    methods: {
        getUsers(){
            AlarmaController.getUsers(this.alarmaId).then((response) => {
                if(response.status == 200){
                    this.users = response.data;
                }
                this.loading = false;
                $appStore.setGlobalLoading(false);
            });
        }
    },
    computed: {
        imageProfileDefault(){
            return $appStore.getImageProfileDefault;
        }
    }
}

</script>
