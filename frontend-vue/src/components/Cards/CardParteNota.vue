<template>
    <div class="w-full bg-white shadow-md rounded-lg overflow-hidden p-4 flex flex-col justify-between">
        <div class="flex items-center mb-4">
            <img class="w-12 h-12 rounded-full mr-4 object-cover" :src="props.perfilUsuario ? props.perfilUsuario: userProfileDefault" alt="Avatar de Usuario">
            <div class="text-sm">
                <p class="text-gray-900 font-semibold">{{ props.nombreUsuario }}</p>
                <p class="text-gray-600">{{ props.fecha }}</p>
                <p class="text-gray-700 text-base">{{ props.descripcion }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import  { defineProps,computed } from "vue";
    import { appStore } from "../../store/app";
    const props = defineProps({
        nombreUsuario: {required: true, type: String},
        perfilUsuario: { required: true, type:[String,null]},
        fecha : {required: true, type: String},
        descripcion : {required: true, type: String}
    })

    const $appStore = appStore();

    const userProfileDefault = computed(()=>{
        return $appStore.getImageProfileDefault;
    })


</script>