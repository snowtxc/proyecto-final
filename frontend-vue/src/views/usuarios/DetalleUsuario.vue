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
                        
                        
                            <div class="mb-3">   
                                <BaseBtn :disabled="formInvalid" rounded block class="primary text-white px-4 py-2" type="submit">Guardar cambios</BaseBtn>
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

import { RadioGroup } from '@headlessui/vue';
import UsuarioController from '../../services/UsuarioController'
import { appStore } from "@/store/app.js";

const $appStore = appStore();

export default{
    data() {
        return {
            name: '',
            email: '',
            errorEmail: '',
            selectedRol: "Observador",
            errorName: ''
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
                console.log(response);
                if (response.status == 200) {
                    const data = response.data;
                    this.name = data.name;
                    this.email = data.email;
                    this.selectedRol = data.rol;
                }
                $appStore.setGlobalLoading(false);
            });
        },
        guardarUsuario() {
            if (this.formInvalid) {
                return;
            }
                $appStore.setGlobalLoading(true);
                if (this.userId != 0) {
                    UsuarioController.editarUsuario(this.userId, this.name, this.email, this.selectedRol).then((response) => {
                        console.log(response);
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
    
    components: { RadioGroup }
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