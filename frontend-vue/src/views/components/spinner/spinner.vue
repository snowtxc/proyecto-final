<template>
      <div v-if="show" class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
        <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
      </div>
  </template>
  
  <script setup>
  import { ref , defineProps} from 'vue';
  
  const { show, mensaje } = defineProps(['show']); // Cambia a true para mostrar el spinner
  </script>