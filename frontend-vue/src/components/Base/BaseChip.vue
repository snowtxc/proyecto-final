<script setup>
    import { defineProps } from 'vue';
    const props =  defineProps({
        text: { type: String ,required: true}
    })
</script>

<template>
    <div class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-pink-700 bg-pink-100 border border-pink-300 ">
            <div class="text-xs font-normal leading-none max-w-full flex-initial">{{ props.text }}</div>
        </div>
</template>