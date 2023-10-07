<template>
    <Breadcrumb parentTitle='Procesos' subParentTitle="Etapa"/>
</template>
<script setup>
import Breadcrumb from '@/components/Breadcrumbs.vue';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const procesoId = ref(null);
const etapaId = ref(null);

const route = useRoute();

onMounted(() => {
  procesoId.value = route.params.procesoId;
  etapaId.value = route.params.etapaId;

  console.log(procesoId.value, etapaId.value);
});
</script>