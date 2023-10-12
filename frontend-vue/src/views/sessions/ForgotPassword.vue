<template>
  <div class="auth-layout-wrap flex justify-center min-h-screen flex-col bg-cover items-center" >
        <div class="container-session-v1 max-w-2xl">
            <BaseCard noPadding class="overflow-hidden ">
                <div class="p-5 flex flex-col">
                    <h1 class="mb-4 text-2xl">Reestablecer contraseña </h1>
                      <div v-if="error" class="error-banner bg-red-100 p-2 rounded-md mb-4 text-red-500 text-center ">
                        <p> {{ errorMessage }} </p>
                      </div>
                      <div v-if="okMessage" class="success-banner bg-green-100 p-2 rounded-md mb-4 text-green-500 text-center ">
                        <p> {{ okMessage }} </p>
                      </div>
                      <div class="mb-3 flex flex-col">
                          <label class="text-xs text-gray-600 my-2" for="email">Email</label>
                          <input 
                              v-model="email" 
                              class=" mb-4 w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" 
                              type="email" 
                              id="email" 
                              name="email" 
                              placeholder="Ingrese su correo electrónico"
                          >                         
                      </div>
                      <div class="mb-4">
                          <BaseBtn 
                              block 
                              class="bg-[#25CEDE] mb-2 text-white  rounded-full"
                              @click="confirm">
                              Confirmar
                          </BaseBtn>
                      </div>
                </div>
            </BaseCard>    
        </div>
    </div>
</template>

<script setup>

import { appStore } from "@/store/app.js";
import {ref} from 'vue'
import { useRouter } from 'vue-router';
import  AuthController  from '../../services/AuthController'

//const router = useRouter();

const $appStore = appStore();

const email = ref('');
const error = ref(false);
const errorMessage = ref('');
const okMessage = ref('');

const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

const confirm = () => {
  if( email.value == '' ){
    errorMessage.value = 'Por favor ingrese un correo electrónico.'
    error.value = true;
  }else{
    if (!emailPattern.test(email.value)) {
        errorMessage.value = 'Por favor ingrese un correo electrónico válido.';
        error.value = true;
    }
    else{
      error.value = false;
    }
  }

  if( !error.value ){

    $appStore.setGlobalLoading(true);  
   
    AuthController.forgotPassword(email.value)
    .then((response) => {
        console.log(response);
        okMessage.value = response.data.message;
        error.value= false;
        errorMessage.value = '';
        //router.push('/');        
    }).catch((e) => {
        console.error(e);
        okMessage.value = '';
        error.value= true;
        errorMessage.value = e.response.data.message;
    })
    .finally(() => {
        $appStore.setGlobalLoading(false);
    }); 
  }
};


</script>

