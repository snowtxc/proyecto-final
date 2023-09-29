<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="$emit('closeModal', false)"></div>
    <div class="modal-container bg-white w-96 mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="modal-content py-4 text-left px-6">
        <!-- Botón para cerrar el modal -->
        <button @click="$emit('closeModal', false)" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none">
          <font-awesome-icon :icon="['far', 'rectangle-xmark']" size="lg" class="close"/>
        </button>
        <!-- Contenido personalizado del modal -->
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits} from 'vue';

const { show } = defineProps(['show']);
const { closeModal } = defineEmits(['closeModal']);



</script>

<style scoped>
/* Estilos del modal */
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
