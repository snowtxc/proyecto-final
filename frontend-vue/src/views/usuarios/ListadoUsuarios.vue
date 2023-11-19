<template>
    <div class="col-span-12">
        <div class="card-header flex justify-between items-center">           
            <div class="card-title ">
                <p class="text-xl font-semibold"> Usuarios </p>
            </div>
            <BaseBtn 
                 @click="this.selectedUser = null, this.showModalUsuario = true"
            >
                <i class="mr-2 fa-solid fa-plus"></i>
                Nuevo Usuario 
            </BaseBtn>
        </div>

            <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                <div class="dataTable-wrapper dataTable-loading no-footer fixed-columns">
                    <div class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover max-h-[70vh] overflow-y-auto"
                        id="scrollContainer">                        
                        <ul v-if="usuarios.length > 1">
                            <li v-for="(user, index) in usuarios" :key="index">
                                <div v-if="user.id != userLogged.id" class="flex flex-col items-center  mb-4 md:flex-row overflow-hidden flex-row mb-6 shadow-md rounded-xl px-5 h-24" >
                                    <img class="w-14 h-14 m-4 shadow-lg avatar-md rounded-full object-fill"
                                        :src="user.profileImage ? user.profileImage : imageProfileDefault" alt="" />
                                    <div class="flex-grow md:text-left">
                                        <h5>
                                            <p class="text-gray-800">
                                                {{ user.name }} - {{ user.rol }}
                                            </p>
                                        </h5>
                                        <p class="text-gray-400 text-xs mb-3 md:mb-0">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <div>
                                         <font-awesome-icon :icon="['far', 'pen-to-square']"
                                        @click="this.selectedUser = user, this.showModalUsuario = true"
                                        class="w-5 h-5 m-4 hover:text-primary" />
                                    <font-awesome-icon :icon="['far', 'trash-can']"
                                        @click="this.userDelete = user.id, this.showConfirmationModal = true"
                                        class="w-5 h-5 m-4 hover:text-primary" />
                                    </div>
                                   
                                </div>
                            </li>
                        </ul>
                        <div
                            class="w-full bg-white rounded-md shadow-md "
                            v-if="usuarios.length == 1"> 
                            
                            <h2 class="text-2xl font-semibold m-4">
                                No se encontraron usuarios
                            </h2>
                            <p class="text-gray-600 m-4">
                                No hay ningún usuario registrado
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <!--/BaseCard-->
    </div>

    <DetalleUsuario v-if="showModalUsuario" :show="showModalUsuario" :userData="selectedUser"
        @onClose="showModalUsuario = false" @onConfirm="onConfirmEvent">
    </DetalleUsuario>
    <ConfirmationModal v-if="showConfirmationModal" :show="showConfirmationModal" :title="modalTitle"
        :message="modalMessage" @confirm="eliminar" @cancel="cancelar" />
</template>

<script>

import UsuarioController from '../../services/UsuarioController'
import DetalleUsuario from './DetalleUsuario.vue'
import ConfirmationModal from '../../components/ConfirmationModal.vue';
import { appStore } from "@/store/app.js";
import BaseBtn from '../../components/Base/BaseBtn.vue';

import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

const $appStore = appStore();

export default {

    data() {
        return {
            usuarios: [],
            selectedUser: null,
            showModalUsuario: false,
            showConfirmationModal: false,
            modalTitle: "Confirmación",
            modalMessage: "¿Estás seguro de que deseas eliminar este usuario?",
            userDelete: null,
            userLogged: $appStore.getUserData
        }
    },

    created() {
        $appStore.setGlobalLoading(true);
        this.getUsuarios();
    },
    mounted(){
        const container = document.getElementById('scrollContainer');
        new PerfectScrollbar(container);
    },

    methods: {
        getUsuarios() {
            UsuarioController.listaUsuarios().then((response) => {
                if (response.status == 200) {
                    this.usuarios = response.data;
                    console.log(this.usuarios);
                }
                $appStore.setGlobalLoading(false);
            })
        },
        onConfirmEvent() {
            this.getUsuarios();
            this.showModalUsuario = false;
            this.selectedUser = 0;
        },
        async eliminar() {
            $appStore.setGlobalLoading(true);
            UsuarioController.eliminarUsuario(this.userDelete).then((response) => {
                console.log(response);
                if (response.status === 200) {
                    this.getUsuarios();
                } else {
                    $appStore.setGlobalLoading(false);
                    //mensaje de error
                    console.error("Error al eliminar usuario:", error);
                }
            });
            this.showConfirmationModal = false;
            this.userDelete = null;
        },

        cancelar() {
            this.userDelete = null;
            this.showConfirmationModal = false;
        }
    },

    computed: {
        imageProfileDefault: () => {
            return $appStore.getImageProfileDefault;
        }
    },
    components: {
        DetalleUsuario,
        ConfirmationModal,
        BaseBtn
    }


}


</script>
