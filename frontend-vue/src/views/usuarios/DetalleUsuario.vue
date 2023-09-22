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
                                <input v-model="email" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="email" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Password</label>
                            </div>
                            <div class="mb-3"> 
                                <input v-model="password" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="password" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label class="text-base text-gray" for="">Retype Password</label>
                            </div>
                            <div class="mb-3">    
                                <input v-model="confirmPass" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full" type="password" placeholder="">
                            </div>
                            <div class="mb-3">   
                                <BaseBtn rounded block class="bg-purple-500 text-white px-4 py-2" type="submit">Guardar cambios</BaseBtn>
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

export default{

    data(){
        return {
            name: "",
            email: "",
            password: "",
            confirmPass: ""

        }
    },
    props: {
        show:Boolean,
        userId: Number
    },

    beforeMount(){
        if(this.userId != 0){
            this.getUsuario();
        }
        
    },

    methods: {
        getUsuario(){
            UsuarioController.buscarUsuario(this.userId).then((response) => {
                console.log(response);
                if(response.status == 200){
                    const data = response.data;
                    this.name = data.name;
                    this.email = data.email;
                    this.password = data.password;
                }
            })
        },

        guardarUsuario(){
            if (this.confirmPass == this.password){
                if(this.userId != 0){
                    UsuarioController.editarUsuario(this.userId, this.name, this.email, this.password).then((response) => {
                        if(response.status == 200){
                            this.resetForm();
                            this.$emit('onConfirm', this.userId);
                        }
                    });
                }else{
                    UsuarioController.nuevoUsuario(this.name, this.email, this.password).then((response) => {
                        if(response.status == 200){
                            this.resetForm();
                            this.$emit('onConfirm', this.userId);
                        }
                    });
                }
                
            }else{
                console.log('error password');
            }
        },

        resetForm(){
            this.name = "";
            this.email = "";
            this.password = "";
            this.confirmPass = "";
        }
    }


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
  justify-content: end !important;
  align-items: center !important;
}

</style>