

<template>
  <div class="auth-layout-wrap flex justify-center min-h-screen flex-col bg-cover items-center relative" style="background-image: url('/images/LoginBackground.jpg');">

    <div class="container-session-v1">
      <BaseCard noPadding class="overflow-hidden p-5 bg-white relative">
        <div class="card-header flex justify-center items-center flex-col"> 
          <img src="/images/LogoScada.png" class="w-10"/>      
          <div class="card-title">
            <p class="text-2xl font-semibold "> Inicio de sesión </p>
          </div>
        </div>
        
        <div class="p-5 flex flex-col md:p-5">
          <form @submit.prevent="login">
            <div v-if="showErrorBanner" class="error-banner bg-red-100 p-2 rounded-md mb-4 text-red-500 text-center ">
              <p>Credenciales incorrectas.</p>
            </div>
            <div class="mb-3 flex flex-col">
              <div class="flex gap-2 items-center">
                <label class="text-medium text-gray-600" for="email">Correo</label>
                <span v-if="errors.email" class="text-red-500 text-xs">{{ errors.email }}</span>
              </div>
              

              <input v-model="email" ref="emailInput" class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" type="email" id="email" placeholder="Ingresa tu correo">
            </div>
            <div class="mb-8 flex flex-col">
              <div class="flex gap-2 items-cente">
                <label class="text-xs text-gray-600" for="password">Contraseña</label>
                <span v-if="errors.password" class="text-red-500 text-xs " >{{ errors.password }}</span>


              </div>
              <input v-model="password" class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" type="password" id="password" placeholder="Ingresa tu contraseña">
            </div>
            <div class="mb-4">
              <BaseBtn block @click="signIn">Iniciar</BaseBtn>
            </div>
            <div class="text-center">
              <a class="hover:text-[#25CEDE] underline text-gray-600" href="/forgot-password">Olvidaste tu contraseña?</a>
            </div>
          </form>
        </div>
      </BaseCard>
    </div>
  </div>
</template>


<script setup>

import { appStore } from '@/store/app';
import {ref} from 'vue'
import { useRouter } from 'vue-router';


const $appStore = appStore();
const router = useRouter();

const email = ref('');
const password = ref('');
const showErrorBanner = ref(false);

const errors = ref({
  email: '',
  password: '',
});

const signIn = () => {
  errors.value.email = validateEmail(email.value) ? '' : 'Ingrese un correo';
  errors.value.password = password.value ? '' : 'Ingrese una contraseña';

  if (!errors.value.email && !errors.value.password) {
    makeAPICall();
  }
};

const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
};

const makeAPICall = async () => {
  try {
    $appStore.setGlobalLoading(true);
    const response = await $appStore.login(email.value, password.value); 
    $appStore.setGlobalLoading(false);
    showErrorBanner.value = false;
    router.push('/'); 
  } catch (error) {
    showErrorBanner.value = true;
  }
};
</script>

