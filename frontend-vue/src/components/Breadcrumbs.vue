<script setup>
    const props = defineProps({
        parentTitle: String,
    })
</script>

<template>
    <div class="breadcrumb flex items-end border-b border-gray-300 pb-4 mb-6">
        <p class="text-xl font-semibold mr-2"> {{ props.parentTitle }} </p>
        
    </div>
</template>

