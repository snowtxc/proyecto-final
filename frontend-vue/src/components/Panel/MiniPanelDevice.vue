<template>
    <Card class=" h-auto w-full  mt-2 mb-2" >
        <p class="text-xl font-semibold mr-2">Informacion del dispositivo </p>
        <div class="h-auto w-full flex flex-row space-x-4">
            <div class="w-2/6">
                <CardDevice :nombre="dispositivoData.Nombre" :value="25"
                    :image="dispositivoData.tipoComponenteImage" :selected="false"
                    :unidades="dispositivoData.unidades"
                    :ipAddress="dispositivoData.DireccionIp" />

                    <div class="mt-5">
                        <div class="mb-2">
                            Descripción:
                            {{ dispositivoData.Descripcion }}
                        </div>
                        <div class="mb-2">
                            Estado:
                            <BaseBadge
                                :bgColor="
                                    deviceIsActive
                                        ? 'bg-green-100'
                                        : 'bg-red-100'
                                "
                                :text="
                                    deviceIsActive ? 'Operativo' : 'Inactivo'
                                "
                            ></BaseBadge>
                        </div>
                        <div class="mb-2 flex">
                            Proceso:
                            <a :class="deviceIsActive ?  'text-blue-500 hover:underline  cursor-pointer' : ''" class="ml-2"
                                @click="redirectToProcess(dispositivoData.nodoInfo.procesoId)"
                            >
                                {{
                                    dispositivoData.nodoInfo
                                        ? dispositivoData.nodoInfo.proceso
                                        : 'Sin Informacion'
                                }}
                            </a>
                        </div>
                        
                        <div class="mb-2">
                            Etapa:
                            <a :class="deviceIsActive ?  'text-blue-500 hover:underline  cursor-pointer' : ''" class="ml-2"
                                @click="redirectToEtapa(props.deviceInfo.nodoInfo.procesoId, props.deviceInfo.nodoInfo.etapaId)"
                            >
                                {{
                                dispositivoData.nodoInfo
                                    ? dispositivoData.nodoInfo.etapa
                                    : 'Sin Informacion'
                            }}
                            </a>
                            
                        </div>
                        
                        <div>
                            Desde:
                            {{
                                dispositivoData.nodoInfo
                                    ? dayjs(
                                          dispositivoData.nodoInfo
                                              .fechaDeIngreso
                                      ).format('DD/MM/YYYY hh:mm A')
                                    : 'Sin Informacion'
                            }}
                        </div>
                    </div>
            </div>
            <div class="w-full">
                <div class="flex justify-end items-center gap-5 w-full"> 
                    <div class="">
                        <ToggleDevice :componenteId="dispositivoData.id" :defaultValue="dispositivoData.On" @onToggle="changeOnDevice"></ToggleDevice>
                    </div>
                    <div class="flex flex-col">
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
                    
                </div>
                <div class="flex w-full">
                    <div class="flex-1">
                        <ChartLine :componente_id="dispositivoData.id" :unidad="unidadSelected" :unidades="unidades"/>
                    </div>
                    <div class="flex-1">
                        <ChartBar :componente_id="dispositivoData.id" :unidad="unidadSelected" :unidades="unidades"/>
                    </div>
                    
                </div>

            </div>
        </div>

    </Card>
</template>

<script setup>
import { defineProps, onMounted, ref, watchEffect, defineEmits,computed } from 'vue';
import Card from '@/components/Card/Card.vue';
import CardDevice from '@/components/Cards/CardDevice.vue';
import ChartLine from '@/components/Charts/ChartLine.vue';
import ChartBar from '@/components/Charts/ChartBar.vue';
import ToggleDevice from '../Toggle/ToggleDevice.vue';
import BaseBadge from '../Base/BaseBadge.vue';
import dayjs from 'dayjs';


const props = defineProps({
    dispositivoData: { type: Object, required: true }
})

const dispositivoData =  ref({ ... props.dispositivoData});
const showSpinner = ref(false)
const unidades = ref(dispositivoData.value.unidades)
const unidadSelected = ref(unidades.value[0])

console.log(unidades)

const onSelectUnidadDeMedida = (e) => {
    const value = e.target.value
    unidadSelected.value = unidades.value.find(
        (unidad) => unidad.unidad_id == value
    )
}

const deviceIsActive = computed(() => {
    return dispositivoData.value.nodoInfo && dispositivoData.value.On ? true : false
})


const changeOnDevice = (on)=>{
    dispositivoData.value.On = on;
}



</script>