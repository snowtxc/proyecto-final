<script setup>
     import { ref ,onBeforeMount } from "vue";
     import Breadcrumbs from "../../components/Breadcrumbs.vue";
     import TipoComponenteController from  "../../services/TipoComponenteController.js";
     import { appStore } from "../../store/app";

     const $appStore =  appStore();
   
    const tiposComponentes  = ref([]);


    onBeforeMount(()=>{
        getTiposComponentes();
    });

    const getTiposComponentes = async()=>{
          $appStore.setGlobalLoading(true);
          tiposComponentes.value = await  TipoComponenteController.getAll();
          $appStore.setGlobalLoading(false);


    }
</script>


<template>
    <div class="flex justify-between items-center">
        <Breadcrumbs parentTitle="Tipo de Componentes"></Breadcrumbs>
        <BaseBtn>
            Agregar nuevo tipo de componente
        </BaseBtn>
    </div>
    <input class="focus:outline-none text-2xl mb-7" type="text" placeholder="Buscar" />

<div>

    <div class="max-h-[70vh] overflow-y-auto">
        <div v-for="tipoComponente in tiposComponentes" :key="tipoComponente.id" class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl px-5">
        <div class="flex">
            <img class="w-16 object-fill" :src="tipoComponente.Imagen" alt="" />
        </div>
        <div class="flex pl-2 flex-1">
            <div class="flex flex-grow flex-col self-center justify-between lg:items-center lg:flex-row">
                <a class="hover:text-purple-500" href="">
                    
                    {{tipoComponente.Nombre}}</a>
               
                
                <div >
                    <font-awesome-icon
                            :icon="['far', 'trash-can']"
                            @click="showModalDeleteComponent = true"
                            class="w-5 h-5 m-4 hover:text-primary"
                        />
                </div>
            </div>
        </div>
    </div>
    </div>
    
</div>
</template>

