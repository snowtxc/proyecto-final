<template>
    <div class="col-span-12">
        <BaseCard>
            <template v-slot:cardHeader>
                <div class="card-header">
                    <div class="card-title pt-3">Usuarios 
                    <div class="end-align">
                    <BaseBtn 
                        rounded
                        class="border border-primary text-primary hover:bg-primary hover:text-white"
                        @click="this.selectedUser = 0, this.showModalUsuario = true"
                        >
                        Nuevo
                    </BaseBtn>
                    </div>
                    </div>
                </div>
                
            </template>
            <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                <div class="dataTable-wrapper dataTable-loading no-footer fixed-columns">
                    <div class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover">
                        <table v-if="usuarios.length > 0" class="table-3 dataTable-table max-w-full w-full">
                            <thead>
                                <tr class="">
                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold px-4">
                                        Nombre
                                    </th>
                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold">
                                        Email
                                    </th>
                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold"></th>
                                    <th class="text-left border-b pb-3 mb-3 text-gray-500 font-semibold"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-500 cursor-pointer"
                                    v-for="(user, index) in usuarios"
                                    :key="index"
                                >
                                <td class="px-4 py-3">
                                    {{ user.name }}
                                </td>

                                <td>
                                    {{ user.email }}
                                </td>
                                <td>
                                    <BaseBtn 
                                        rounded
                                        class="border border-primary text-primary hover:bg-primary hover:text-white"
                                        @click="this.selectedUser = user.id, this.showModalUsuario = true"
                                        >
                                        Ver
                                    </BaseBtn>
                                </td>
                                <td>
                                    <BaseBtn 
                                        rounded
                                        class="border border-primary text-primary hover:bg-primary hover:text-white"
                                        @click="this.userDelete = user.id, this.showConfirmationModal = true"
                                        >
                                        Eliminar
                                    </BaseBtn>
                                </td>
                                </tr>
                            </tbody>
                        </table>

                        <p class="px-4 py-3" v-if="usuarios.length == 0"> No se encontraron usuarios</p>
                                
                    </div>
                    <div class="dataTable-bottom">
                        <div class="dataTable-info">
                            
                        </div>
                        <nav class="dataTable-pagination">
                            <ul class="dataTable-pagination-list"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </BaseCard>
    </div>

    <DetalleUsuario 
        v-if="showModalUsuario" 
        :show="showModalUsuario" 
        :userId="selectedUser" 
        @onClose="showModalUsuario = false" 
        @onConfirm="onConfirmEvent">
    </DetalleUsuario>
    <ConfirmationModal
      v-if="showConfirmationModal"
      :show="showConfirmationModal"
      :title="modalTitle"
      :message="modalMessage"
      @confirm="eliminar"
      @cancel="cancelar"
    />
</template>

<script>

import UsuarioController from '../../services/UsuarioController'
import DetalleUsuario from './DetalleUsuario.vue'
import ConfirmationModal from '../../components/ConfirmationModal.vue';
import { appStore } from "@/store/app.js";

const $appStore = appStore();

export default{

    data(){
        return {
            usuarios : [],
            selectedUser : 0,
            showModalUsuario: false,
            showConfirmationModal: false,
            modalTitle: "Confirmación",
            modalMessage: "¿Estás seguro de que deseas eliminar este usuario?",
            userDelete: null,
        }
    },

    created(){
        $appStore.setGlobalLoading(true);
        this.getUsuarios();
    },

    methods: {
        getUsuarios(){
            this.usuarios = [];
            UsuarioController.listaUsuarios().then((response) => {
                if(response.status == 200){
                this.usuarios = response.data;
                }
                $appStore.setGlobalLoading(false);
            })
        },
        onConfirmEvent(){
            this.getUsuarios();
            this.showModalUsuario = false; 
            this.selectedUser = 0;
        },
        async eliminar(){
            $appStore.setGlobalLoading(true);
            UsuarioController.eliminarUsuario(this.userDelete).then((response) =>{
                if (response.status === 200) {
                    this.getUsuarios();
                }else{
                    $appStore.setGlobalLoading(false);
                    //mensaje de error
                    console.error("Error al eliminar usuario:", error);
                }
            });
            this.showConfirmationModal = false;
            this.userDelete = null;
        },

        cancelar(){
            this.userDelete = null;
            this.showConfirmationModal = false;
        }
    },
    components:{
        DetalleUsuario,
        ConfirmationModal
    }


}


</script>

<style scoped>
.end-align {
  display: flex !important;
  justify-content: flex-end !important;
  align-items: center !important;
}

</style>