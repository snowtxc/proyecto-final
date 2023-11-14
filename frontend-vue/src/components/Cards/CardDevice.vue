<template>
    <div @click="emit('onSelect')" class="card flex flex-col  hover:bg-gray-100 dark:text-white group  cursor-pointer w-80 md:w-auto"
        :class="props.selected ? 'bg-[#25CEDE] text-white' : 'bg-white'">
        <div class="card-body flex felx-row justify-between items-center w-full">
            <div class="flex align-center">
                <img :src="props.image" alt="Image" class=" w-16 h-16 object-fill" />
                <div class="m-auto ml-5">
                    <p :class="props.selected ? 'text-white' : 'text-gray-400'">{{ props.nombre }}</p>
                    <p class="text-sm" :class="props.selected ? 'text-white' : 'text-gray-400'">{{ props.ipAddress }}</p>
                    <div class="flex w-full">
                        <BaseChip v-for="unidad in props.unidades"  :key="unidad.id"  :text="unidad.nombre"></BaseChip>
                    </div>
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import BaseChip from '../Base/BaseChip.vue';
import BaseCard from "../Base/BaseCard.vue";

const emit = defineEmits(['onSelect']);
const props = defineProps({
    nombre: { required: true, type: String },
    value: { required: true, type: [Number, null] },
    image: { required: true, type: String },
    selected: { required: true, type: [Boolean, null] },
    ipAddress: { required: true, type: String },
    unidades: { required: true, type: Array}
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