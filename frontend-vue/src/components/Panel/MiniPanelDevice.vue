<template>
    <Card class=" h-auto w-full  mt-2 mb-2" >
        <p class="text-xl font-semibold mr-2">Informacion del dispositivo </p>
        <div class="h-auto w-full flex flex-row space-x-4">
            <div class="w-2/6">
                <CardDevice :nombre="props.dispositivoData.Nombre" :value="25"
                    :image="props.dispositivoData.tipoComponenteImage" :selected="false"
                    :unidades="props.dispositivoData.unidades"
                    :ipAddress="dispositivoData.DireccionIp" />
            </div>
            <div class="w-full">
                <div class="flex items-end flex-col w-full">
                    <p>Unidad de medida seleccionada</p>
                    <select id="small"
                        class="w-20 p-2 mb-6 text-sm text-gray-900 brounded-lg border border-gray-400 min-w-[400px]"
                        @change="onSelectUnidadDeMedida">
                        <option v-for="unidad in unidades" :key="unidad.unidad_id" :value="unidad.unidad_id">
                            <div class="p-5">
                                {{ unidad.nombre }}
                            </div>
                        </option>
                    </select>
                </div>
                <div class="flex w-full">
                    <div class="flex-1">
                        <ChartLine :componente_id="props.dispositivoData.id" :unidad="unidadSelected" :unidades="unidades"/>
                    </div>
                    <div class="flex-1">
                        <ChartBar :componente_id="props.dispositivoData.id" :unidad="unidadSelected" :unidades="unidades"/>
                    </div>
                    
                </div>

            </div>
        </div>

    </Card>
</template>

<script setup>
import { defineProps, onMounted, ref, watchEffect, defineEmits } from 'vue';
import Card from '@/components/Card/Card.vue';
import CardDevice from '@/components/Cards/CardDevice.vue';
import ChartLine from '@/components/Charts/ChartLine.vue';
import ChartBar from '@/components/Charts/ChartBar.vue';

const props = defineProps({
    dispositivoData: { type: Object, required: true }
})

const showSpinner = ref(false)
const unidades = ref(props.dispositivoData.unidades)
const unidadSelected = ref(unidades.value[0])

console.log(unidades)

const onSelectUnidadDeMedida = (e) => {
    const value = e.target.value
    unidadSelected.value = unidades.value.find(
        (unidad) => unidad.unidad_id == value
    )
}
</script>