<script setup>

const emit = defineEmits(['click']);
const props = defineProps({
    block: Boolean,
    sm: Boolean,
    xl: Boolean,
    icon: Boolean,
    rounded: Boolean,
    maxWidth: String,
    bgColor: String
    
})
</script>
<template>
    <button
        @click="emit('click')"
        type="button"
        class="btn rounded-lg border transition-all bg-primary text-white  hover:bg-white hover:text-primary hover:border-primary  dark:hover:bg-red-800"
        :class="[
            props.bgColor ? props.bgColor :  'bg-primary',
            props.block ? 'block w-full' : 'inline-block',
            props.sm ? 'sm' : '',
            props.xl ? 'xl' : '',
            props.icon ? 'p-2 flex items-center justify-center' : 'py-2 px-5',
            maxWidth ? `max-w-[${maxWidth}]` : '' 
        ]"
    >
        <slot />
    </button>
</template>
<style lang="scss" scoped>
// loop colors

.btn {
    font-size: medium;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out,
        -webkit-box-shadow 0.15s ease-in-out;

    &.ripple {
        position: relative;
        overflow: hidden;
        transform: translateZ(0);
    }
    &.sm {
        font-size: 0.71137rem;
        @apply py-1 px-3;
    }
    &.xl {
        font-size: 1.18rem;
        padding: 0.75rem 2rem;
    }
    border-radius: 10px;
    box-shadow: 0 4px 20px 1px rgb(0 0 0 / 6%), 0 1px 4px rgb(0 0 0 / 8%);
}
</style>
