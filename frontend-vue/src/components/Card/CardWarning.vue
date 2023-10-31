<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        text: { required : true, type:String}
    })
</script>

<template>
     <BaseCard>
        <div class="flex items-center mb-2 text-red-500">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <p>{{ props.text }}</p>
        </div>
     </BaseCard>
</template>