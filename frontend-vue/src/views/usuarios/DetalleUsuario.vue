<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="$emit('onClose')"></div>
        <div class="modal-container bg-white mx-auto rounded shadow-lg z-50 overflow-y-auto">
        <BaseCard style="width: 600px;">
            
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
            <div class="modal-content py-4 text-left px-4">
            
            <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                    <div class="dataTable-container block w-full overflow-x-auto whitespace-nowrap borderless hover">
                        <form @submit.prevent="guardarUsuario">
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Nombre</label>
                            </div>
                            <div class="mb-3"> 
                                <input v-model="name" @input="validateName" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="text" placeholder="">
                            </div>
                            <span v-if="errorName" class="px-2 py-2 text-red-500 text-xs"> {{ errorName }}</span>
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Email</label>
                            </div>
                            <div class="mb-3"> 
                                <input v-model="email" @input="validateEmail" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="email" placeholder="" @change="checkEmail" :disabled="userId != 0">
                            </div>
                            <span v-if="errorEmail" class="px-2 py-2 text-red-500 text-xs"> {{ this.errorEmail }}</span>
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
                        
                        
                            <div class="flex justify-end">   
                                <BaseBtn :disabled="formInvalid || processing" type="submit">
                                    Guardar
                                    <spinner :show="processing" :width="4" height="4" ></spinner>
                                </BaseBtn>
                            </div>
                        </form>
                    </div>
                    <div class="dataTable-bottom"> 
                    </div>
                </div>
            </div>
        </BaseCard>
    </div>
    </div>

</template>

<script>

import UsuarioController from '../../services/UsuarioController'
import { appStore } from "@/store/app.js"; 
import spinner from '../../views/components/spinner/spinner.vue';

const $appStore = appStore();

export default{
    data() {
        return {
            name: '',
            email: '',
            userId: 0,
            errorEmail: '',
            selectedRol: "Observador",
            errorName: '',
            processing: false
        };
    },
    props: {
        show: Boolean,
        userData: Object
    },
    beforeMount() {
        if (this.userData != null) {
            this.userId = this.userData.id;
            this.name = this.userData.name;
            this.email = this.userData.email;
            this.selectedRol = this.userData.rol;
        }
    },
    methods: {
        
        guardarUsuario() {
            if (this.formInvalid) {
                return;
            }
            this.processing = true
                //$appStore.setGlobalLoading(true);
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
                        console.log(response);
                        if (response.status == 201) {
                            this.resetForm();
                            this.$emit('onConfirm', this.userId);
                        }
                    });
                }
        },
        resetForm() {
            this.name = '';
            this.email = '';
            this.selectedRol = "observador";
            this.processing = false;
        },

        validateName() {
            this.errorName = this.name ? '' : 'Por favor ingrese el nombre del usuario';
        },

        validateEmail() {
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!this.email) {
                this.errorEmail = 'Por favor ingrese un correo electrónico.';
                return;
            }

            if (!emailPattern.test(this.email)) {
                this.errorEmail = 'Por favor ingrese un correo electrónico válido.';
                return;
            }

            if (this.userId === 0) {
                UsuarioController.checkEmail(this.email)
                    .then((response) => {
                        if (response.status === 200) {
                            this.errorEmail = '';
                        } else {
                            if (response.data.message === 'The email has already been taken.') {
                                this.errorEmail = 'El correo electrónico ya está en uso.';
                            }
                        }
                    })
            }
        },
    },

    computed: {
        formInvalid() {
            return this.name == '' || this.email == '' || this.errorName != '' || this.errorEmail != '' ;
        }
    },
    
    components: { spinner }
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
.modal-overlay {
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-container {
  max-height: 80vh;
  position: relative;
}

.close:hover {
  color: #25CEDE;
}

</style>