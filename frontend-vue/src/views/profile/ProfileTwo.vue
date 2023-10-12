<script setup>
  import Breadcrumb from '@/components/Breadcrumbs.vue'
  import { ref ,computed} from 'vue'
  import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
  import { appStore } from '@/store/app';

  const $appStore = appStore();
  const userData = $appStore.userdata;

  const userProfileImage = computed(()=>{
    console.log($appStore.getUserData);
    return $appStore.getUserData?.profileImage;
  })

  const userProfileDefault = computed(()=>{
      return $appStore.getImageProfileDefault;
  })

</script>

<template>
  <div class="container mx-auto">
    <Breadcrumb parentTitle='Perfil'/>
    <BaseCard noPadding class="user-profile overflow-hidden relative p-20 flex items-center">
      <div class="flex flex-col items-center justify-center">
          <div class="text-center"><img class="relative z-1 w-24 h-24 m-auto rounded-full border-2 border-white object-fill" :src="userProfileImage ? userProfileImage : userProfileDefault" /></div>
          <p class="text-2xl">{{ userData.name }}</p>
          <p class="text-gray-600">Aqui va el rol</p>
          <p class="text-gray-600">{{ userData.email }}</p>
          <BaseBtn block class="bg-[#25CEDE] mt-8 text-white rounded-full" >Editar</BaseBtn>
      </div>
      
    </BaseCard>
</div>
</template>
