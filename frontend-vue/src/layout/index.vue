<script setup>
import {onBeforeMount,ref } from "vue";
import { useStore } from 'vuex'
import Header from './Header.vue'
import Sidebar from './Sidebar.vue'
import GlobalLoading from "../components/GlobalLoading.vue";
import axios from "../services/axios";

import { appStore } from '../store/app';


let store = useStore();
const $appStore = appStore();
const loading = ref(true);

onBeforeMount(()=>{
    $appStore.setGlobalLoading(true);
    axios.get("auth/isLogged").then((res)=>{
        $appStore.setGlobalLoading(false);
        loading.value = false;
    })

})

</script>

<template>
  <GlobalLoading></GlobalLoading>
  <div class="app-admin-wrap-layout-2  h-screen" v-if="!loading">
    <Header />
    <Sidebar />
    <div :class="store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen === true ? '': 'full'" class="main-content-wrap">
        <main>
            <div class="main-content-wrap flex flex-col flex-grow print-area pt-32">
                <div>
                    <router-view />
                </div>
            </div>
        </main>
    </div>
  </div>
</template>

<style lang="scss" scoped>
    .app-admin-wrap-layout-2 {
        width: 100%;
        .main-content-wrap {
            width: calc(100% - 120px);
            height: 100%;
            margin-left: 90px;
            // min-height: 100vh;
            transition: all 0.24s ease-in-out;
            .main-content-body{
                min-height: calc(100vh - 80px);
            }
            &.full {
                width: 100%;
                margin-left: 0px;
                transition: all 0.24s ease-in-out;
            }
            @media screen and (max-width: 991px) {
                width: 100%;
                margin-left: 0px;
                padding-right: 16px;
                padding-left: 16px;
            }
        }
    }

    
</style>


