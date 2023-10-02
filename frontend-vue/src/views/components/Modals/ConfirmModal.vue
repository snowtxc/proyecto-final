<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="cancelar"></div>
        <div class="modal-container bg-white w-96 mx-auto rounded shadow-lg z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6 flex flex-col justify-center">
                <slot></slot>
                <!-- Contenido personalizado del modal -->
                <p class="text-2xl font-bold mb-4 text-center pb-5">{{ mensaje }}</p>
                <div class="flex justify-center space-x-4">
                    <BaseBtn rounded class="border border-danger text-danger hover:bg-danger hover:text-white h-10"
                    @click="$emit('closeModal', false)">
                        Cancelar
                    </BaseBtn>
                    <BaseBtn rounded class="border border-primary text-primary hover:bg-primary hover:text-white h-10"
                    @click="$emit('confirmModal', false)">
                        Aceptar
                    </BaseBtn>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const { show, mensaje } = defineProps(['show', 'mensaje']);
const { closeModal, confirmModal } = defineEmits(['closeModal', 'confirmModal']);

const confirmar = () => {
    // Llama a la función de confirmación proporcionada por el padre (por ejemplo, eliminarEtapa)
    // Si tienes una función proporcionada por el padre para manejar la confirmación, puedes llamarla aquí.
    // closeModal(true) también cierra el modal después de la confirmación.
    show.value = false;
};

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
</style>
  