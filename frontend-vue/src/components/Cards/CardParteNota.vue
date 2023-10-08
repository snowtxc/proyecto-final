<template>
 <div class="w-full  bg-gray-200 shadow-lg   p-4 flex flex-col justify-between leading-normal  ">
    <div class="flex items-center">
    <img class="w-10 h-10 rounded-full mr-4 object-fill" src="https://www.mundopsicologos.com/site/article/60606/50895/las-10-cualidades-de-una-persona-mas-valoradas-0_ai1.jpg" alt="Avatar of Jonathan Reinink">
    <div class="text-sm">
        <p class="text-gray-900 leading-none">{{ props.nombreUsuario }}</p>
        <p class="text-gray-600">{{ props.fecha }}</p>
    </div>
        </div>
    <div class="mb-8">
        <p class="text-gray-700 text-base">{{ props.descripcion }}</p>
    </div>
                    
    </div>
</template>

<script setup>
    import  { defineProps } from "vue";
    const props = defineProps({
        nombreUsuario: {required: true, type: String},
        fecha : {required: true, type: String},
        descripcion : {required: true, type: String}
    })

</script>