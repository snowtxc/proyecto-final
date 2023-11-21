<script setup>
    import { computed, defineProps ,ref } from 'vue';
    import ComponenteController from '@/services/ComponenteController';
    import { appStore } from '@/store/app';
    import { useNotification } from '@kyvg/vue3-notification';
    import { defineEmits } from 'vue';
    

    const $appStore = appStore();
    const { notify } = useNotification();

    const emit = defineEmits(['onToggle']);

    const props = defineProps({
        componenteId: { type: Number,required: true},
        defaultValue: { type: Boolean, required: false}
    });
    const val = ref(props.defaultValue ? props.defaultValue : false);
    const text = computed(()=>{
        return  val.value ? 'Prendido' : 'Apagado';
    });

    const changeToggle = (e)=>{      
        $appStore.setGlobalLoading(true);
        ComponenteController.toggleOn(props.componenteId, val.value).then(data =>{
            $appStore.setGlobalLoading(false);
            emit("onToggle", val.value);
            notify({
                title:
                    val.value 
                        ? 'Componente encendido correctamente'
                        : 'Componente apagado correctamente.',
                text:
                    val.value 
                        ? 'El componente se ha encendido'
                        : 'El componente se ha apagado.', 
                type: 'success',
            })

        })

    }
    
</script>



<template>
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" v-model="val" @change="changeToggle"  class="sr-only peer">
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ text }}</span>
    </label>
</template>


