<template>
    <div class="w-72" v-if="fotos.length > 0">
    <swiper-container
        :slides-per-view="1"       
        :centered-slides="true"
        :pagination="true"
        :navigation="true"
        
    >
        <swiper-slide v-for="foto in fotos"><img :src=foto.Path alt=""></swiper-slide>
       
    </swiper-container>
</div>
</template>
  
  <script>
    import { register } from 'swiper/element/bundle';

    register();

    export default {
        
        props:{
            fotos: Array
        },
    }
</script>
  