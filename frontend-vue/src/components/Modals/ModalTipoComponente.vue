
<script setup> 

    import { defineProps ,ref,defineEmits ,onBeforeMount} from 'vue';
    import Modal from '../../views/components/Modals/Modal.vue';
    import useVuelidate from '@vuelidate/core';
    import { required,requiredIf } from '@vuelidate/validators';
    import { Action } from '../../shared/enums/Action';
    import FilePicker from "@/components/FilePicker/FilePicker.vue";
    import TipoComponenteController from '../../services/TipoComponenteController';
    import { useNotification } from '@kyvg/vue3-notification';
    import spinner from '../../views/components/spinner/spinner.vue';

    
    const props = defineProps({
        show: { type: Boolean,  required: true},
        action : { type: String , required: true},
        tipoComponenteData : { type: Object, required: false}
    });
    const emit = defineEmits(['onTipoComponente onClose'])

    const { notify } = useNotification();

    const showModal = ref(props.show)
    const processing = ref(false);
    const submit = ref(false);

    const form =  ref({
        Nombre: '',
        Imagen: null
    });

    
    const imageIsRequired  = ()=>{
        return props.action == Action.CREAR && !form.value.Imagen;
    }

    const rules = {
        Nombre : { required},
        Imagen: { required: requiredIf(imageIsRequired)  }
    }

    const defaultImgUrl = ref("");

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
            body.append('Nombre', Nombre);

            switch(props.action){
                case "CREAR":
                        body.append('Imagen', Imagen);
                        break;
                case "EDITAR":
                        if(form.value.Imagen){
                            body.append('Imagen', Imagen);
                        }
                        break;
            }
            const tipoComponente =  props.action == Action.CREAR ?  await TipoComponenteController.create(body): await TipoComponenteController.edit(props.tipoComponenteData.id, body);
            emit('onTipoComponente', tipoComponente);
            notify({
                title:   props.action == Action.CREAR ? 'Tipo de Componente creado' : 'Tipo componente editado correctamente',
                text:    props.action  == Action.CREAR ?  'El tipo de componente se ha creado correctamente' :  'El tipo de componente se ha editado correctamente' ,
                type: 'success'
            });
        }catch(e){
            console.log(e)
            processing.value = false;
            notify({
                title: 'Error',
                text: `Ocurrio un error al enviar el formulario de componente`,
                type: 'error'
            })
        }
    }

    const changeFilePicker = (file)=>{
        form.value.Imagen = file;
    }

    onBeforeMount(()=>{
         if(props.action == Action.EDITAR && props.tipoComponenteData){
            const { Nombre, Imagen }  = props.tipoComponenteData;
            form.value.Nombre = Nombre;
            defaultImgUrl.value = Imagen;
         }
    })

</script>

<template>
    <Modal :show="showModal" @closeModal="emit('onClose')">
            <div class="flex flex-col items-start mb-8">
                <p class="font-bold text-xl">{{ props.action == Action.CREAR ? 'Nuevo tipo de componente' : props.action == Action.EDITAR ?  `Editar tipo de componente` : ''}}</p>
            </div>
            <div class="space-y-4 mb-8">
                <input v-model="form.Nombre" class="w-full px-4 py-1 border border-gray focus:outline-none rounded-full"
                    type="text" placeholder="Nombre" />
                <span v-if="submit && $v.Nombre.$invalid" class="text-red-500 text-xs"> Nombre es requerido</span>
            </div>
            <div class="space-y-4 mb-8">
                <FilePicker @onChangeFile="changeFilePicker" @onClearFile="form.Imagen = null" :defaultImgUrl="defaultImgUrl"></FilePicker>
                <span v-if="submit && $v.Imagen.$invalid" class="text-red-500 text-xs"> Imagen es requerido</span>


            </div>
            
            <div class="flex justify-end">
                <BaseBtn  @click="onSubmit"   rounded class="border border-primary text-primary hover:bg-primary hover:text-white h-10"
                    >
                      {{  $props.action == Action.CREAR ?   'Crear' : 'Editar' }}
                    <spinner :show="processing" :width="4" height="4" ></spinner>
                </BaseBtn>
            </div>
    </Modal>

</template>