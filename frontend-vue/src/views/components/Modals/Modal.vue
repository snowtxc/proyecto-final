<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="$emit('closeModal', false)"></div>
    <div class="modal-container bg-white w-96 mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="card-header flex justify-between items-center">         
          <div class="card-title">
            <p class="text-xl font-semibold "> {{ title }}</p>
          </div>
          <BaseBtn
            sm
            @click="$emit('closeModal', false)">
            <i class="fas fa-times"></i>
          </BaseBtn>
      </div>
      <div class="modal-content py-4 text-left px-4">
        
        <!--button @click="$emit('closeModal', false)" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 focus:outline-none">
          <font-awesome-icon :icon="['far', 'rectangle-xmark']" size="lg" class="close"/>
        </button-->
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits} from 'vue';

const { show, title } = defineProps(['show', 'title']);
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
