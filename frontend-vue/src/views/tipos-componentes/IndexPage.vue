<script setup>
     import { ref ,onBeforeMount, computed } from "vue";
     import Breadcrumbs from "../../components/Breadcrumbs.vue";
     import TipoComponenteController from  "../../services/TipoComponenteController.js";
     import { appStore } from "../../store/app";
     import { Action } from "../../shared/enums/Action";
     import BaseBtn from "../../components/Base/BaseBtn.vue";
     import ModalTipoComponente from "../../components/Modals/ModalTipoComponente.vue";
     import ConfirmationModal from "../../components/ConfirmationModal.vue";
     import { useNotification } from "@kyvg/vue3-notification";
    

     const  { notify } = useNotification();
     const $appStore =  appStore();
   
    const tiposComponentes  = ref([]);
    const action  = ref(null);
    const showModal= ref(false);
    const showModalDelete = ref(false);
    const tipoComponenteSelected = ref(null);
    const debounceSearch = ref(null);
    const loading = ref(true);

    const tiposComponentesEmpty = computed(()=>{
        return tiposComponentes.value.length <= 0;
    })


    onBeforeMount(()=>{
        getTiposComponentes();
    });

    const getTiposComponentes = async(search = null)=>{
          $appStore.setGlobalLoading(true);
          tiposComponentes.value = await  TipoComponenteController.getAll(search);
          $appStore.setGlobalLoading(false);
          loading.value = false;
    }

    const addNewTypeComponent = ()=>{
        action.value = Action.CREAR;
        showModal.value = true;
    }

    const handleTipoComponenteModal= (typeComponent) =>{
        console.log(typeComponent)
        showModal.value = false;
        if(action.value ==  Action.CREAR){
            showModal.value = false;
            tiposComponentes.value.unshift(typeComponent);
            return;
        }
        const index =  tiposComponentes.value.findIndex(item => item.id == typeComponent.id);
        tiposComponentes.value[index] = typeComponent;
       

    }

    const onShowModalDelete = (tipoComponente)=>{
        tipoComponenteSelected.value = tipoComponente;
        showModalDelete.value = true;
    }

    const onDelete = async()=>{
        if(!tipoComponenteSelected.value){
            return;
        }
        showModalDelete.value = false;
        const { id } = tipoComponenteSelected.value;
        $appStore.setGlobalLoading(true);
        try{
            const tipoComponentDeleted  = await TipoComponenteController.delete(id);
            const index = tiposComponentes.value.findIndex(item => item.id == tipoComponentDeleted.id);
            tiposComponentes.value.splice(index,1);
            $appStore.setGlobalLoading(false);
            notify({
                title: 'Tipo de Componente removido',
                text: 'El tipo de componente se ha removido correctamente',
                type: 'success'
            });
    
        }catch(e){
            console.log(e)
            $appStore.setGlobalLoading(false);

            notify({
                title: 'Error',
                text: `Ocurrio un error al eliminar tipo componente`,
                type: 'error'
            })
        }
    }

    const handleSearch = ($event) => {
        const value = $event.target.value;
        if (debounceSearch.value) {
            clearTimeout(debounceSearch.value)
        }
        debounceSearch.value = setTimeout(() => {
            getTiposComponentes(value)
        }, 500)
    }

    const edit = (tipoComponente) =>{
         action.value = Action.EDITAR;
         tipoComponenteSelected.value = tipoComponente;
         showModal.value = true;
    }



    
</script>


<template>
    <div class="flex justify-between items-center">
        <Breadcrumbs parentTitle="Tipo de Componentes"></Breadcrumbs>
        <BaseBtn @click="addNewTypeComponent">
            Agregar nuevo tipo de componente
        </BaseBtn>
    </div>
    <input class="focus:outline-none text-2xl mb-7 bg-gray-200 p-2 w-full" type="text" placeholder="Buscar" @input="handleSearch" />

    <div>

        <div class="max-h-[70vh] overflow-y-auto">
            <div
                    class="w-full bg-white p-8 rounded-md shadow-md"
                    v-if="tiposComponentesEmpty && !loading"
                >
            
                    <p class="text-gray-600 text-center">
                        No se encontro ningun tipo de componente 
                    </p>
            </div>
            <div v-else>
                <div v-for="tipoComponente in tiposComponentes" :key="tipoComponente.id" class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl px-5">
            <div class="flex">
                <img class="w-16 object-fill" :src="tipoComponente.Imagen" alt="" />
            </div>
            <div class="flex pl-2 flex-1">
                <div class="flex flex-grow flex-col self-center justify-between lg:items-center lg:flex-row">
                    <a class="hover:text-purple-500" href="">
                        
                        {{tipoComponente.Nombre}}</a>
                
                    
                    <div class="">
                        <font-awesome-icon
                                :icon="[
                                    'far',
                                    'pen-to-square',
                                ]"
                                class="w-5 h-5 m-4 hover:text-primary"
                                @click="
                                    edit(
                                        tipoComponente
                                    )
                                "
                            />
                        <font-awesome-icon
                                :icon="['far', 'trash-can']"
                                @click="onShowModalDelete(tipoComponente)"
                                class="w-5 h-5 m-4 hover:text-primary"
                            />
                    </div>
                </div>
            </div>
             </div>
            </div>
            
        </div>
        
    </div>

    <ModalTipoComponente v-if="showModal" :show="showModal" :action="action" @onTipoComponente="handleTipoComponenteModal" @onClose="showModal = false" :tipoComponenteData="tipoComponenteSelected"></ModalTipoComponente>

    <ConfirmationModal 
    v-if="showModalDelete" :show="showModalDelete" 
    title="Seguro deseas eliminar este tipo de componente" 
    warning="Atencion.Al eliminar el tipo de componente, se eliminar todos los componentes asociados con su datos relacionados"
    @cancel="showModalDelete = false"
    @confirm="onDelete"></ConfirmationModal>
</template>

