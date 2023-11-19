<script setup>
    import BaseCard from '../Base/BaseCard.vue';

    import ReporteController from "@/services/ReporteController";
    import { appStore } from '@/store/app';

    import { defineEmits } from 'vue';
    const $appStore = appStore();

    const emit = defineEmits(['onReport']);

    const getDataReport = ()=>{
        $appStore.setGlobalLoading(true);
        ReporteController.getCantidadAlarmasPorTipoDispositivo().then(data =>{
            const labels = data.map(item => item.Nombre);
            const values = data.map(item => item.CantDispositivos);
            $appStore.setGlobalLoading(false);
            emit('onReport', {
                title: 'Alarmas por tipos de dispositivos.',
                labels,
                values
            });        
        });
    }
    

</script>
<template>
   <BaseCard class="flex-1 text-center" :hover="true" @click="getDataReport">
         <i class="fa-solid fa-chart-simple text-6xl"></i>
        <h3>Alarmas por tipo de Dispositivo</h3>
    </BaseCard>
</template>