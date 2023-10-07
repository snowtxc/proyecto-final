<template>
    <div  @click="emit('onSelect')" class="card flex flex-col  hover:bg-gray-100 dark:text-white group  cursor-pointer" :class="props.selected ? 'bg-[#25CEDE] text-white': 'bg-white'">
    <div class="card-body">
        <div class="flex align-center">
            <img :src="props.image" alt="Image" class=" w-16 h-16 object-cover" />
            <div class="m-auto">
                <p :class="props.selected  ? 'text-white' : 'text-gray-400'">{{ props.nombre }}</p>
                <p class="text-xl" :class="props.selected ? 'text-white' : 'text-primary'">{{ props.value }}</p>
                <p class="text-sm">{{ props.ipAddress }}</p>

            </div>
        </div>
    </div>
    </div>
</template>

<script setup>
    import { defineProps, defineEmits } from 'vue';
    
    const emit = defineEmits(['onSelect']);
    const props = defineProps({
        nombre: { required: true, type: String },
        value: { required: true, type: Number },
        image: { required: true, type: String },
        selected: { required: true, type: Boolean},
        ipAddress: {required: true, type:Boolean}
    })
</script>

<style lang="scss" scoped>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 20px 1px rgb(0 0 0 / 6%), 0 1px 4px rgb(0 0 0 / 8%);
    // box-shadow:rgb(149 157 165 / 20%) 0px 8px 24px;

    .card-body {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
        &.noPadding {
            padding: 0 !important;
        }
    }
}
</style>