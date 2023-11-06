<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        text: { type: String , required: true},
        bgColor: { type : String , required: false}
    });
</script>

<template>
    <span :class="[props.bgColor ? props.bgColor : 'bg-green-100']" class="text-xs font-medium mr-2 px-3 py-1 rounded dark:bg-red-900 dark:text-red-300">{{ props.text}}</span>

</template>

