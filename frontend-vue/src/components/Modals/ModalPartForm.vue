<template>
     <Modal :show="true" @closeModal="emit('onClose')">
            <div class="flex flex-col items-start mb-8">
                <p class="font-bold text-xl">{{ props.action == Action.CREAR ? 'Nueva parte para el dispositivo' : props.action == Action.EDITAR ?  `Editar parte "${partName}"` : ''}}</p>
            </div>
            <div class="space-y-4 mb-8">
                <input v-model="form.Nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="submit && v$.Nombre.$invalid" class="text-red-500 text-xs"> Nombre es requerido</span>
                <textarea v-model="form.Descripcion"
                    class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded" placeholder="Descripcion" />
                    <span v-if="submit && v$.Descripcion.$invalid" class="text-red-500 text-xs"> Descripcion es requerido</span>


            </div>
            <div class="flex justify-end">
                <BaseBtn  @click="onSubmit"   rounded class="border border-primary text-primary hover:bg-primary hover:text-white h-10"
                    >
                    Crear
                    <spinner :show="processing" :width="4" height="4" ></spinner>
                </BaseBtn>
            </div>
        </Modal>
</template>

<script setup>
    import { ref ,defineProps, defineEmits,onBeforeMount} from 'vue';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import { Action } from '../../shared/enums/Action';
    import Modal from '../../views/components/Modals/Modal.vue';
    import ParteController from "../../services/ParteController";
    import { useNotification } from '@kyvg/vue3-notification';
    import spinner from '../../views/components/spinner/spinner.vue'

    const { notify } = useNotification();

    const props =  defineProps({
           componente_id: { required: true, type: Number},
           action : { required: true, type: String},
           partData : { required: true, type: Object}
    });


    const form = ref({
        id: 0,
        Nombre: '',
        Descripcion: ''
    });

    const submit = ref(false);
    const emit = defineEmits(['onProcessed','onClose'])
    const processing = ref(false);
    const partName = ref("");

    const rules = {
        Nombre: {required},
        Descripcion: { required }
    }

    const v$  = useVuelidate(rules,form);

    onBeforeMount(()=>{
        if(props.action == Action.EDITAR && props.partData){
           form.value.id = props.partData.id;
           form.value.Nombre = props.partData.Nombre;
           form.value.Descripcion =  props.partData.Descripcion;
           partName.value = props.partData.Nombre;  
        }
    })

    const clearForm = ()=>{
        form.value.Nombre = '';
        form.value.Descripcion = '';
        v$.value.$reset();
    }

    const onSubmit = async()=>{
        submit.value = true;
        if(v$.value.$invalid){
            return;
        }
        const body = {
            ...form.value,
            componente_id: props.componente_id
        }
        processing.value = true;
        try{ 
            const part =   props.action == Action.CREAR  ?  await ParteController.create(props.componente_id,body) : props.action == Action.EDITAR ?  await ParteController.edit(props.componente_id,form.value.id, body) : null;
            processing.value = false;
            notify({
                title: props.action == Action.CREAR ?  'Parte creada correctamente' : props.action ? 'Parte editada correctamente' : '',
                text:  props.action  ==  Action.CREAR ?  `Se ha creado la parte ${part.Nombre}` : props.action == Action.EDITAR ?  `Se ha editado la parte ${body.Nombre}` : '',
                type: 'success'
            })
            if(props.action == Action.CREAR){
                clearForm();
            }

            emit('onProcessed',part);
        }catch(e){
            console.log(e);
            notify({
                title: 'Error',
                text: `Ocurrio un error al crear la parte`,
                type: 'error'
            })
            processing.value = false;
        }

    }
     

</script>