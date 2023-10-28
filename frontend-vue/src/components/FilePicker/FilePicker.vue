<script setup>
  import { ref , defineEmits,defineProps} from 'vue';
  import { useNotification } from '@kyvg/vue3-notification'

  const { notify } = useNotification();
  const emit = defineEmits(['onChangeFile','onClearFile']);

  const props =  defineProps({
    defaultImgUrl : { type: String ,required: false}
  })
  const imagePreview = ref(props.defaultImgUrl ? props.defaultImgUrl : null);
  const inputFile = ref(null);

  const validateExtension = (ext) => {
    return true;
  };

  const clearImage = () => {
        imagePreview.value = null;
        emit('onClearFile');
  };

  const previewImage = (file)=>{
    const reader = new FileReader();

      reader.onload = () => {
        imagePreview.value = reader.result;
      };

      reader.readAsDataURL(file);
  }

  const onChangeFile = (e) => {
    const fileInput = e.target;
    const file = fileInput.files[0];

    if (file && validateExtension(file.type)) {
        previewImage(file);
        emit('onChangeFile', file)
        return;
    }
    notify({
        title: 'Error',
        text: `Imagen o formato invalido, solo puedes importar imagenes JPG, PNG o JPEG`,
        type: 'error'
    })


    
  };
</script>

<template>
  <div class="flex items-center justify-center w-full relative">
    <label
      v-if="!imagePreview"
      for="dropzone-file"
      class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
    >
      <div class="flex flex-col items-center justify-center pt-5 pb-6">
      </div>
      <input id="dropzone-file" type="file" class="hidden" @change="onChangeFile" ref="inputFile"  />
      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400 flex items-center"><span class="font-semibold">Click para seleciconar una imagen</span>
        <i class="fa-solid fa-image ml-2"></i>
     </p>

    </label>

    <div v-else>
      <button @click="clearImage" class="absolute top-2 right-2 p-1 bg-white border border-gray-300  text-gray-500 hover:bg-gray-100 hover:text-gray-600 focus:outline-none">
        <i class="fa-solid fa-times"></i>

      </button>

      <img :src="imagePreview" alt="Preview" class="w-full h-auto object-fit" />
    </div>
  </div>
</template>