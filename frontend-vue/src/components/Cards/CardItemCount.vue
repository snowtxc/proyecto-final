<script setup>
        import { defineProps  } from 'vue';

        const props  = defineProps({
            title: { required: true, type: String},
            count: { required: true, type:Number},
            icon: { required: true , type: String}
        })

</script>

<template>
    <BaseCard>
        <div class="flex align-center">
            <i class="text-6xl text-primary" :class="props.icon"></i>
            <div class="m-auto">
                <p class="text-gray-400">{{ props.title }}</p>
                <p class="text-xl text-primary">{{  props.count }}</p>
            </div>
        </div>
    </BaseCard>
</template>
