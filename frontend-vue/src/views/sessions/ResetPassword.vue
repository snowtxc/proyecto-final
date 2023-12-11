<template>
  <div class="auth-layout-wrap flex justify-center min-h-screen flex-col bg-cover items-center" style="background: linear-gradient(to bottom, white, rgba(37, 206, 222, 0.7));"> >
        <div class="container-session-v1 max-w-2xl">
            <BaseCard noPadding class="overflow-hidden ">
                <div class="p-5 flex flex-col bg-white">
                    <img src="/images/LogoScada.png" class="w-10 m-auto"/>      
                    <h1 class="mb-3 text-2xl">Reestablecer contraseña </h1>
                      <div v-if="error" class="error-banner bg-red-100 p-2 rounded-md mb-4 text-red-500 text-center ">
                        <p> {{ errorMessage }} </p>
                      </div>
                      <div class="mb-3 flex flex-col">
                          <label class="text-xs text-gray-600" for="password">Contraseña</label>
                          <input 
                              v-model="password" 
                              class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" 
                              type="password" 
                              id="password" 
                              name="password" 
                              placeholder="Ingresa tu nueva contraseña"
                          >
                          
                      </div>
                      <div class="mb-8 flex flex-col">
                          <label class="text-xs text-gray-600" for="passwordConfirm">Confirmación</label>
                          <input 
                              v-model="passwordConfirm" 
                              class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" 
                              type="password" 
                              id="passwordConfirm" 
                              name="passwordConfirm"
                              placeholder="Confirma tu contraseña"
                          >
                          
                      </div>
                      <div class="mb-4">
                          <BaseBtn 
                              block 
                              class="bg-[#25CEDE] mb-2 text-white  rounded-full"
                              @click="confirm">
                              Confirmar
                              <spinner :show="reseting" width="6" height="6"></spinner>
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
import { useRoute, useRouter } from 'vue-router';
import  AuthController  from '../../services/AuthController'
import { useNotification } from "@kyvg/vue3-notification";
import spinner from "../components/spinner/spinner.vue";

const route = useRoute();
const router = useRouter();
const token = route.params.token;
const { notify } = useNotification();

const $appStore = appStore();

const passwordConfirm = ref('');
const password = ref('');
const error = ref(false);
const errorMessage = ref('');
const reseting = ref(false);

const confirm = () => {
  if( password.value == '' || passwordConfirm.value == ''){
    errorMessage.value = 'Ingrese una contraseña'
    error.value = true;
  }else{
    if( password.value != passwordConfirm.value ){
      errorMessage.value = 'Las contraseñas no coinciden.'
      error.value = true;
    }else{
      error.value = false;
    }
  }

  if( !error.value ){
    $appStore.setGlobalLoading(true);  
    reseting.value = true;
    AuthController.resetPassword(token, password.value)
    .then((response) => {
        console.log(response);
        router.push('/');   
        reseting.value = false;     
    }).catch((e) => {
        console.error(e);
        reseting.value = false;
        notify({
            title: 'Error',
            text: e.response.data.message,
            type: 'error',
        })
    })
    .finally(() => {
        $appStore.setGlobalLoading(false);
    }); 
  }
};


</script>

