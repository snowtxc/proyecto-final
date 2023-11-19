<script setup>
    import BaseCard from '../Base/BaseCard.vue';

    import ReporteController from "@/services/ReporteController";
    import { appStore } from '@/store/app';

    import { defineEmits } from 'vue';
    const $appStore = appStore();


    const emit = defineEmits(['onReport']);

    const getDataReport = ()=>{
        $appStore.setGlobalLoading(true);
        ReporteController.getDispositivosActivosEInactivos().then(data =>{
            const labels = ['Operativos', 'Inactivos'];
            const { CantDispositivosOperativos , CantDispositivosInactivos}  = data;
            const values = [ CantDispositivosOperativos, CantDispositivosInactivos ]
            $appStore.setGlobalLoading(false);
            emit('onReport', {
                title: 'Dispositivos operativos e inactivos.',
                labels,
                values
            });        
        });
    }
    

</script>
<template>

   <BaseCard class="flex-1 text-center" :hover="true" @click="getDataReport">
        <i class="fa-solid fa-chart-simple text-6xl"></i>
        <h3>Dispositivos operativos e inactivos</h3>
    </BaseCard>



</template>