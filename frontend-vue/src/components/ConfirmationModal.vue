<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="modal-overlay absolute inset-0 bg-black opacity-25"></div>
      <div class="modal-container bg-white w-96 mx-auto rounded shadow-lg z-50">
        <div class="modal-content p-4">
          <h2 class="text-lg font-semibold mb-4">{{ title }}</h2>
          <p class="mb-6">{{ message }}</p>
          <div class="flex justify-end">
            <BaseBtn rounded block @click="cancel" class="btn-cancel mr-4">Cancelar</BaseBtn>
            <BaseBtn rounded block @click="confirm" class="btn-confirm">Confirmar</BaseBtn>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      title: String,
      message: String,
      show: Boolean
    },
    data() {
      return {
        isModalOpen: false,
      };
    },
    methods: {
      open() {
        this.isModalOpen = true;
      },
      close() {
        this.$emit('cancel');
      },
      confirm() {
        this.$emit('confirm');
      },
      cancel() {
        this.$emit('cancel');
      },
    },
  };
  </script>
  
  <style scoped>
  .btn-cancel {
    background-color: #e53e3e;
    color: white;
  }
  
  .btn-confirm {
    background-color: #38a169;
    color: white;
  }
  </style>
  