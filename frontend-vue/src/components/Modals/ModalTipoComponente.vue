
<script setup> 

    import { defineProps ,ref,defineEmits} from 'vue';
    import Modal from '../../views/components/Modals/Modal.vue';
    import useVuelidate from '@vuelidate/core';
    import { required } from '@vuelidate/validators';
    import { Action } from '../../shared/enums/Action';
    import FilePicker from "@/components/FilePicker/FilePicker.vue";
    import TipoComponenteController from '../../services/TipoComponenteController';
    import { useNotification } from '@kyvg/vue3-notification';
    import spinner from '../../views/components/spinner/spinner.vue';


    const props = defineProps({
        show: { type: Boolean,  required: true},
        action : { type: String , required: true}
    });
    const emit = defineEmits(['onTipoComponenteCreated, onClose'])


    const { notify } = useNotification();


    const showModal = ref(props.show)
    const tipoComponenteNombre = ref("");
    const processing = ref(false);
    const submit = ref(false);

    const form =  ref({
        Nombre: '',
        Imagen: null
    });

    const rules = {
        Nombre : { required},
        Imagen: { required}
    }

    const $v = useVuelidate(rules, form);

    const onSubmit =   async()=>{
        submit.value = true;
        if($v.value.$invalid){
            return;
        }
        submit.value = false;
        processing.value = true;

        try{
            const { Nombre, Imagen } = form.value;
            const body = new FormData();
            body.append('Imagen', Imagen);
            body.append('Nombre', Nombre);
            const tipoComponenteCreated = await TipoComponenteController.create(body);
            emit('onTipoComponenteCreated', tipoComponenteCreated);
            notify({
                title: 'Tipo de Componente creado',
                text: 'El tipo de componente se ha creado correctamente',
                type: 'success'
            });
        }catch(e){
            processing.value = false;
            notify({
                title: 'Error',
                text: `Ocurrio un erro al crear el tipo de componente`,
                type: 'error'
            })
        }
    }

    const changeFilePicker = (file)=>{
        form.value.Imagen = file;
    }

   

</script>

<template>
    <Modal :show="showModal" @closeModal="emit('onClose')">
            <div class="flex flex-col items-start mb-8">
                <p class="font-bold text-xl">{{ props.action == Action.CREAR ? 'Nuevo tipo de componente' : props.action == Action.EDITAR ?  `Editar tipo de componente "${tipoComponenteNombre}"` : ''}}</p>
            </div>
            <div class="space-y-4 mb-8">
                <input v-model="form.Nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="submit && $v.Nombre.$invalid" class="text-red-500 text-xs"> Nombre es requerido</span>
            </div>
            <div class="space-y-4 mb-8">
                <FilePicker @onChangeFile="changeFilePicker" @onClearFile="form.Imagen = null"></FilePicker>
                <span v-if="submit && $v.Imagen.$invalid" class="text-red-500 text-xs"> Imagen es requerido</span>


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