<template>
    <div v-if="show" class="modal col-span-12">
        <BaseCard style="width: 600px;">
            
            <template v-slot:cardHeader>
                <div class="card-header">
                    <div class="col-2 end-align">
                        <i class="i-Close-Window" @click="$emit('onClose')"></i>
                    </div>
                    <div class="col-10">                       
                        <div class="card-title">Usuario</div>                   
                    </div>
                </div>
            </template>
            <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                    <div class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover">
                        <form @submit.prevent="guardarUsuario">
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Nombre</label>
                            </div>
                            <div class="mb-3"> 
                                <input v-model="name" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="text" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Email</label>
                            </div>
                            <div class="mb-3"> 
                                <input v-model="email" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="email" placeholder="" @blur="checkEmail" >
                            </div>
                            <span v-if="emailExists" class="px-2 py-2 text-red-500 text-xs"> {{ this.errorEmail }}</span>
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Rol</label>
                            </div>
                        
                            <div class="mb-3">
                                <input type="radio" v-model="selectedRol" id="observador" name="rol" value="Observador" class="ml-4 mr-1 px-4 py-1 border border-gray focus:outline-none rounded-full">
                                <label for="observador">Observador</label>
                                
                                <input type="radio" v-model="selectedRol" id="operador" name="rol" value="Operador" class="ml-4 mr-1 px-4 py-1 border border-gray focus:outline-none rounded-full">
                                <label for="operador">Operador</label>

                                <input type="radio" v-model="selectedRol" id="admin" name="rol" value="Administrador" class="ml-4 mr-1 px-4 py-1 border border-gray focus:outline-none rounded-full">
                                <label for="admin">Administrador</label>
                            </div>
                        
                        
                            <div class="mb-3">   
                                <BaseBtn rounded block class="primary text-white px-4 py-2" type="submit">Guardar cambios</BaseBtn>
                            </div>
                        </form>
                    </div>
                    <div class="dataTable-bottom">
                    </div>
                </div>
           
        </BaseCard>
    </div>
</template>

<script>

import UsuarioController from '../../services/UsuarioController'
import { appStore } from "@/store/app.js";

const $appStore = appStore();

export default{
    data() {
        return {
            name: "",
            email: "",
            emailExists: false,
            errorEmail: "",
            selectedRol: "Observador",
            emailAnterior: ""
        };
    },
    props: {
        show: Boolean,
        userId: Number
    },
    created() {
        if (this.userId != 0) {
            $appStore.setGlobalLoading(true);
            this.getUsuario();
        }
    },
    methods: {
        getUsuario() {
            UsuarioController.buscarUsuario(this.userId).then((response) => {
                if (response.status == 200) {
                    const data = response.data;
                    this.name = data.name;
                    this.email = data.email;
                    this.selectedRol = data.rol;
                    this.emailAnterior = data.email;
                }
                $appStore.setGlobalLoading(false);
            });
        },
        guardarUsuario() {
            if (!this.emailExists) {
                $appStore.setGlobalLoading(true);
                if (this.userId != 0) {
                    UsuarioController.editarUsuario(this.userId, this.name, this.email, this.selectedRol).then((response) => {
                        if (response.status == 200) {
                            this.resetForm();
                            this.$emit('onConfirm', this.userId);
                        }
                    });
                }
                else {
                    UsuarioController.nuevoUsuario(this.name, this.email, this.selectedRol).then((response) => {
                        if (response.status == 201) {
                            this.resetForm();
                            this.$emit('onConfirm', this.userId);
                        }
                    });
                }
            }
            else {
                //mensaje de error 
            }
        },
        resetForm() {
            this.name = "";
            this.email = "";
            this.selectedRol = "observador";
        },
        checkEmail() {
            if (this.email != this.emailAnterior) { //this.userId == 0 || (
                UsuarioController.checkEmail(this.email).then((response) => {
                    if (response.status == 200) {
                        this.emailExists = false;
                    }
                    else {
                        if (response.data.message == "The email has already been taken.") {
                            this.errorEmail = "El correo electrónico ya está en uso.";
                        }
                        else {
                            this.errorEmail = "Ingrese un correo electrónico válido";
                        }
                        this.emailExists = true;
                    }
                })
                    .catch((error) => {
                    this.emailExists = true;
                });
            }
        }
    },
}


</script>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.end-align {
  display: flex !important;
  justify-content: flex-end !important;
  align-items: center !important;
}

</style>