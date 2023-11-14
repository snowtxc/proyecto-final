

<template>
  <div class="auth-layout-wrap flex justify-center min-h-screen flex-col bg-cover items-center" style="background-image: url('/images/LoginBackground.jpg');">
        <div class="container-session-v1 max-w-2xl">
          <BaseCard noPadding class="overflow-hidden p-5">
            <div class="card-header flex justify-center items-center">         
              <div class="card-title">
                <p class="text-xl font-semibold "> Inicio de sesión </p>
              </div>
            </div>
            
                <div class="p-5 flex flex-col md:p-5">
                  
                    <!--h1 class="mb-3 text-2xl">Inicio de sesion</h1-->
                    <form @submit.prevent="login">
                      <div v-if="showErrorBanner" class="error-banner bg-red-100 p-2 rounded-md mb-4 text-red-500 text-center ">
                        <p>Credenciales incorrectas.</p>
                      </div>
                      <div class="mb-3 flex flex-col">
                          <label class="text-xs text-gray-600" for="email">Correo</label>
                          <input v-model="email" ref="emailInput" class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" type="email" id="email" placeholder="Ingresa tu correo">
                          <span v-if="errors.email" class="text-red-500 text-xs">{{ errors.email }}</span>
                      </div>
                      <div class="mb-8 flex flex-col">
                          <label class="text-xs text-gray-600" for="password">Contraseña</label>
                          <input v-model="password" class="w-72 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400 rounded-full" type="password" id="password" placeholder="Ingresa tu contraseña">
                          <span v-if="errors.password" class="text-red-500 text-xs " >{{ errors.password }}</span>
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

import { appStore } from "@/store/app.js";
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
    const response = await $appStore.login(email.value, password.value); 
    showErrorBanner.value = false;
    router.push('/');
  } catch (error) {
    showErrorBanner.value = true;
  }
};
</script>

