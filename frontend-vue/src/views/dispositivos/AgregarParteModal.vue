<template>
    <BaseBtn @click="show = true;">Agregar Parte
      <i class="fa-solid fa-plus ml-2"></i>
    </BaseBtn>
    <div v-if="show" class="modal col-span-12">
        <BaseCard style="width: 600px;">
            
            <template v-slot:cardHeader>
                <div class="card-header">
                    <div class="col-2 end-align">
                        <i class="i-Close-Window" @click="show = false;"></i>
                    </div>
                    <div class="col-10">                       
                        <div class="card-title">Agregar nueva parte</div>             
                    </div>
                </div>
            </template>
            <div class="block w-full overflow-x-auto whitespace-nowrap borderless hover">
                <div>
                    <div>
                            <label class="text-xs text-gray-600" for="nombre"
                                >Nombre *</label
                            >
                            <span
                                class="mt-2 text-xs text-red-500 peer-[&:not(:placeholder-shown):not(:focus):invalid]:block"
                                v-if="
                                    submit && $v.$invalid && $v.Nombre.$invalid
                                ">
                                Por favor ingresa el nombre de la parte
                            </span>
                    </div>
                   
                   <div>
                        <input
                        v-model="parteData.Nombre"
                        class="w-full px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400"
                        type="text"
                        />
                   </div>
                    

                </div>
              
                <div class="mt-2 ">
                    <label class="text-xs text-gray-600" for="descipcions" >Descripcion </label>
                            
                    <textarea
                        v-model="parteData.Descripcion"
                        rows="4"
                        class="w-full block p-2.5 px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400"
                ></textarea>   
                </div>
              
            </div>

            <div class="flex justify-end mt-2">
                <BaseBtn @click="onSubmit">
                    Agregar
                </BaseBtn>
            </div>
           
        </BaseCard>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import BaseBtn from '../../components/Base/BaseBtn.vue';
import useVuelidate from '@vuelidate/core';

import { required } from '@vuelidate/validators';

const show = ref(false);
const submit = ref(false);

const parteData = ref({
    Nombre: "",
    Descripcion: ""
})
const rules = {
    Nombre: { required }
}
const $v = useVuelidate(rules,parteData);


const onSubmit = ()=>{
    submit.value = true;
    if($v.value.$invalid){
        return;
    }
    submit.value = false;
}


</script>

<style>
    .modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.end-align {
  display: flex !important;
  justify-content: end !important;
  align-items: center !important;
}
</style>