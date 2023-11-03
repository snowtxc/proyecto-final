<script setup>
        import { defineProps } from 'vue';
        const props = defineProps({
            title: { type: String, required: true},
            options: { type: Object, required: true}
        })
</script>


<template>
        <BaseCard>
                <h4 class="card-title mb-4">{{ props.title }}</h4>
                <apexchart
                    type="pie"
                    height="290"
                    :options="props.options.chartOptions"
                    :series="props.options.series"
                ></apexchart>
        </BaseCard>
</template>