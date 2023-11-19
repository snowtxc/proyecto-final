<script setup>
    import BaseCard from '../Base/BaseCard.vue';
    import Modal from '@/views/components/Modals/Modal.vue';

    import ReporteController from "@/services/ReporteController";
    import { appStore } from '@/store/app';

    import { defineEmits ,ref} from 'vue';
    const $appStore = appStore();
    const showModal = ref(false)

    const filters = ref({
        startDate: null,
        endDate: null,
    })
    const emit = defineEmits(['onReport']);

    const getDataReport = ()=>{
        showModal.value = false;
        $appStore.setGlobalLoading(true);
        ReporteController.getDispositivosMasActivos(filters.value).then(data =>{
            console.log(data);
            const labels = data.map(item => item.Nombre);
            const values = data.map(item => item.CantidadRegistros);
            $appStore.setGlobalLoading(false);
            emit('onReport', {
                title: 'Actividad por Dispositivo',
                labels,
                values
            });
        
        })
    }
    

</script>


<template>

   <BaseCard class="flex-1 text-center" :hover="true" @click="showModal = true">
        <i class="fa-solid fa-chart-simple text-6xl"></i> 
        <h3>Actividad por Dispositivo</h3>
    </BaseCard>

    <Modal :show="showModal" title="Generar reporte" @closeModal="showModal = false;"> 
        <div class="w-full flex flex-col gap-5">
            <div>
                <label class="text-gray-600 mr-2">Desde:</label>
                <input
                    type="datetime-local"
                    v-model="filters.startDate"
                    class="border rounded px-2 py-1 w-full"
                />
            </div>
            <div>
                <label class="text-gray-600 mr-2">Hasta:</label>
                <input
                    type="datetime-local"
                    v-model="filters.endDate"
                    class="border rounded px-2 py-1 w-full"
                />
            </div>

            <BaseBtn @click="getDataReport" >Generar reporte
                <i class="fa-solid fa-chart-simple ml-2"></i>
            </BaseBtn>
        </div>
    </Modal>



</template>