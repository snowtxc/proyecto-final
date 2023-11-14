<template>
    <div v-if="mostrarMensaje" class="mensaje-descriptivo bg-gray-100">
        Ver notas
    </div>

    <font-awesome-icon
        :icon="[
            'far',
            'sticky-note',
        ]"
        class="w-5 h-5 m-4 hover:text-primary"
        @click="openModal"
        @mouseover="mostrarMensaje = true" 
        @mouseout="mostrarMensaje = false"
    />

    

    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">

    <div class="modal-overlay fixed inset-0 bg-black opacity-50" @click="$emit('closeModal', false)"></div>
    <div class="modal-container bg-white w-3/4	 mx-auto rounded shadow-lg z-50 overflow-y-auto">
      <div class="modal-content py-4 text-left px-4">
        <div class="flex justify-end items-center">
            <BaseBtn
                sm
                @click="show = false">
                <i class="fas fa-times"></i>
            </BaseBtn>
        </div>
        <div class="flex flex-col pr-4">
            <div class="card-header flex justify-between items-center">         
                <div class="card-title">
                    <p class="text-xl font-semibold "> {{ title }}</p>
                </div>
                <div>
                    <BaseBtn @click="showModalFormNota = true">
                            <i class="fa-solid fa-plus ml-2"></i>
                            Nueva Nota
                    </BaseBtn>
                    
                </div>
                
            </div>
            
            <!--div class="flex justify-end items-center">
                <button class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" @click="show = false">  
                    <i class="fas fa-times"></i>
                </button>
            </div-->
        
            
            <div class="px-5 py-5 mt-2 ">
                <div class="flex justify-center" v-if="loading">
                    <spinner :show="true" :width="12" height="12" ></spinner>

                </div>
                <div class="w-full" v-else>
                    <div class="flex flex-wrap gap-5 max-h-[50vh] overflow-y-auto ">
                        <div class="w-full bg-white p-8 rounded-md shadow-md "
                        v-if="notasEmpty && !loading"
>
                            <h2 class="text-2xl font-semibold mb-4">
                                No se encontraron notas asociadas
                            </h2>
                            <p class="text-gray-600">
                                No existe ninguna nota asociada a esta parte por el momento.

                     
                            </p>
                        </div>
                        <div v-else class="w-full flex flex-col gap-3">
                            <CardParteNota  v-for="nota in notasFormatted" :key="nota.id" :nombreUsuario="nota.user.name" :perfilUsuario="nota.user.profileImage" :descripcion="nota.Descripcion" :fecha="nota.Fecha" ></CardParteNota>
                        </div>
                        <div class="w-full flex justify-center mt-2">
                            <infinite-loading
                                @infinite="loadMoreData"
                                v-if="hasMoreData"
                            ></infinite-loading>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            
        </div>
        
      </div>
    </div>
    </div>

    <Modal :title="'Nueva Nota'" :show="showModalFormNota" @closeModal="showModalFormNota = false;">
            
            <div class="space-y-4 mb-8 flex flex-col">
                <span v-if="submit && $v.Descripcion.$invalid" class="text-red-500 text-xs"> {{ $v.Descripcion.required.$invalid ?  'Descripcion es requerido' : $v.Descripcion.minLength.$invalid  ? 'Descripcion debe contener minimo 10 caracteres' : '' }}</span>

                <textarea v-model="formNewPart.Descripcion"
                    class="w-full h-24 px-4 py-1 border border-gray focus:outline-none rounded" placeholder="Descripcion" />

            </div>
            <div class="flex justify-end">
                <BaseBtn  @click="onSubmit">
                    Guardar
                    <spinner :show="creatingNota" :width="4" height="4" ></spinner>
                </BaseBtn>
            </div>
    </Modal>

   
 
</template>

<script setup>
    import { ref,defineProps ,computed} from "vue";
    import useVuelidate from "@vuelidate/core";
    import { required, minLength } from "@vuelidate/validators";
    import { useNotification } from "@kyvg/vue3-notification";
    import dayjs from "dayjs";

    
    //services
    import ParteController from "../../services/ParteController";


    //components
    import Modal from '../../views/components/Modals/Modal.vue';
    import Breadcrumb from '@/components/Breadcrumbs.vue';
    import CardParteNota from "../Cards/CardParteNota.vue";
    import spinner from "../../views/components/spinner/spinner.vue";
    import InfiniteLoading from 'v3-infinite-loading'
    import 'v3-infinite-loading/lib/style.css';


    const { notify } = useNotification();

    const props = defineProps({ 
        parteNombre : { required: true, type: String},
        componenteId: { required: true, type: Number},
        parteId: {  required: true, type: Number},
    });

    const mostrarMensaje = ref(false);
    const show = ref(false);
    const showModalFormNota = ref(false);
    const creatingNota = ref(false);
    const submit = ref(false);

    const page = ref(1);
    const maxRows = ref(5);
    const hasMoreData = ref(true);

    const notasArr = ref([]);
    const loading = ref(true);

    const openModal = ()=>{
        show.value = true;
        notasArr.value = [];
        page.value = 1;
        loading.value = true;
        hasMoreData.value = true;
        getNotas().then(()=>{
            loading.value = false;
        })
    }

    const formNewPart =  ref({
        Descripcion: ""
    });

    const rules = {
        Descripcion: { required , minLength: minLength(10)}
    }

    const title =  computed(()=>{
        return  `Notas asociadas a la parte "${props.parteNombre}"`;  
    })

    const $v = useVuelidate(rules,formNewPart);


    const getNotas = async() =>{
        const COMPONENTE_ID =  props.componenteId;
        const PARTE_ID = props.parteId;
        try{
            const {data, countRows} = await ParteController.getNotas(COMPONENTE_ID, PARTE_ID,page.value);
            
            notasArr.value = [...notasArr.value, ...data];

            console.log(notasArr.value.length)
            console.log(countRows)
            if(notasArr.value.length >= countRows || notasArr.value.length == 0){
                hasMoreData.value = false;
            }
        }catch(e){
            console.log(e);
        }
    }

    const clearForm = ()=>{
        formNewPart.value.Descripcion = '';
        $v.value.$reset;
    }

    const onSubmit = async()=>{
            submit.value = true;
            if($v.value.$invalid){
                return;
            }
            submit.value = false;
            creatingNota.value = true;
            const COMPONENTE_ID =  props.componenteId;
            const PARTE_ID = props.parteId;
            try{
                const notaCreated  =   await ParteController.addNota(COMPONENTE_ID, PARTE_ID, formNewPart.value);
                clearForm();
                notasArr.value.unshift(notaCreated);
                creatingNota.value = false;
                showModalFormNota.value = false;
                notify({
                    title: 'Nota creada',
                    text: 'La nota se ha creado correctamente',
                    type: 'success'
                });
            }catch(e){
                console.log(e);
                creatingNota.value = false;
                notify({
                    title: 'Error',
                    text: 'Ha ocurrido un error al crear la nota',
                    type: 'error'
                });
            }
    }

    const notasFormatted = computed(()=>{
        return notasArr.value.map(nota =>{
            const { created_at , ...data} = nota;
            return {
                ...data,
                Fecha: dayjs(created_at).format('DD/MM/YYYY HH:mm A')
            }
        })
    })

    const notasEmpty =  computed(()=>{
        return notasArr.value.length === 0;
    })

    const loadMoreData = async ($state) => {
            page.value++;
            getNotas();
    }

</script>

<style scoped>
/* Estilo para el mensaje descriptivo */
.mensaje-descriptivo {
  position: absolute;
  /*background-color: rgb(148, 146, 146);*/
  color: #080808;
  padding: 2px;
  border-radius: 5px;
  font-size: 14px;
  margin-top: -8px; /* Ajusta según sea necesario para la posición */
  margin-left: -5px; /* Ajusta según sea necesario para la posición */
  z-index: 999; /* Asegura que esté en la parte superior */
}
</style>