<script setup>
import { onMounted, ref, watch, watchEffect ,computed} from 'vue'
import { Switch } from '@headlessui/vue'
import HeaderSearch from '../components/HeaderSearch.vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { useStore } from 'vuex'
import { appStore } from '../store/app'
import { useRouter } from 'vue-router';
import { useNotification } from '@kyvg/vue3-notification'

import UsuarioController from "@/services/UsuarioController.js";

const { notify } = useNotification();

let store = useStore();
const inputFile = ref(null);

const $appStore = appStore();

const $router = useRouter();

const sideBarToggle = () => {
    let sidenav = store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen

    if (sidenav == false) {
        store.commit('largeSidebar/toggleSidebarProperties')
    } else {
        store.commit('largeSidebar/toggleSidebarProperties')
    }
}

const onLogout = ()=>{
    $appStore.logout();
    $router.push({name: 'SignIn'});   
}

const myProfile = ()=>{
    $router.push({name: 'profile'});   
}

const changeProfileImage = ()=>{
        inputFile.value.click();
}

const onAvatarSelected = async($event)=>{
    const file = $event.target.files[0];
    $appStore.setGlobalLoading(true);
    try{
        const body = new FormData();
        body.append('profileImage', file);
        const newProfileImageUrl =  await UsuarioController.changeMeProfileImage(body);
        console.log(newProfileImageUrl)
        $appStore.setProfileImage(newProfileImageUrl);
        $appStore.setGlobalLoading(false);

    }catch(e){
        $appStore.setGlobalLoading(false);
        notify({
            title: 'Error',
            text: 'Error al cambiar la imagen de perfil',
            type: 'error'
        });
    }
    
    
}

const userProfileImage = computed(()=>{
    console.log($appStore.getUserData);
    return $appStore.getUserData?.profileImage;
})

const userProfileDefault = computed(()=>{
    return $appStore.getImageProfileDefault;
})


</script>

<template>
    <div class="header-wrapper flex bg-white justify-between px-4">
        <div class="flex items-center">
            <div class="logo flex justify-center">
                
            </div>
            <div class="mx-0 sm:mx-3">
                <button
                    @click="sideBarToggle"
                    class="
                        menu-toggle
                        cursor-pointer
                        text-gray-500
                        align-middle
                        focus:outline-none
                    "
                    type="button"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex items-center">
            <!-- notification-dropdown  -->
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="
                            btn btn-sm
                            hover:bg-gray-100
                            rounded
                            badge-top-container
                            inline-flex
                            justify-center
                            w-full
                            px-4
                            py-2
                            text-sm
                            font-medium
                        "
                    >
                        <span class="badge-count text-white bg-primary"
                            >3</span
                        >
                        <i class="i-Bell text-xl header-icon text-gray-800"></i>
                    </MenuButton>
                </div>

                <MenuItems
                    class="
                        absolute
                        right-0
                        w-44
                        mt-2
                        overflow-hidden
                        origin-top-right
                        bg-white
                        rounded-md
                        custom-box-shadow
                        focus:outline-none
                    "
                >
                    <div class="">
                        <MenuItem
                            v-for="(item, index) in 4"
                            :key="index"
                            v-slot="{ active }"
                        >
                            <button
                                :class="[
                                    active
                                        ? 'bg-primary text-white'
                                        : 'text-gray-900',
                                    'group flex  items-center w-full px-4 py-2 text-sm',
                                ]"
                            >
                                <div class="flex flex-1 justify-between">
                                    <div>
                                        <h6>New Message</h6>
                                        <p
                                            :class="[
                                                active
                                                    ? 'text-gray-300'
                                                    : 'text-gray-500',
                                            ]"
                                        >
                                            How are you ?
                                        </p>
                                    </div>
                                    <div>
                                        <p
                                            :class="[
                                                active
                                                    ? 'text-gray-200'
                                                    : 'text-gray-400',
                                            ]"
                                        >
                                            10s
                                        </p>
                                    </div>
                                </div>
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </Menu>

            <!-- profile-dropdown  -->
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="
                            inline-flex
                            justify-center
                            w-full
                            px-4
                            py-2
                            text-sm
                            font-medium
                        "
                    >
                        <img
                            class="avatar rounded-full"
                            :src="userProfileImage ? userProfileImage : userProfileDefault" 
                            alt=""
                        />
                    </MenuButton>
                </div>

                <MenuItems
                    class="
                        absolute
                        right-0
                        w-44
                        mt-2
                        overflow-hidden
                        origin-top-right
                        bg-white
                        rounded-md
                        custom-box-shadow
                        focus:outline-none
                    "
                >
                    <div class="">
                        <MenuItem v-slot="{ active }">
                            <button
                                @click="myProfile"
                                :class="[
                                    active
                                        ? 'bg-[#25CEDE] text-white'
                                        : 'text-gray-900',
                                    'group flex  items-center w-full px-4 py-2 text-sm',
                                ]"
                            >
                                Mi Perfil
                            </button>
                        </MenuItem>

                        <MenuItem v-slot="{ active }">
                            <button
                                @click="changeProfileImage"
                                :class="[
                                    active
                                        ? 'bg-[#25CEDE] text-white'
                                        : 'text-gray-900',
                                    'group flex  items-center w-full px-4 py-2 text-sm',
                                ]"
                            >
                                Cambiar foto de perfil
                            </button>
                        </MenuItem>


                        <MenuItem v-slot="{ active }">
                            <button
                                 @click="onLogout"
                                :class="[
                                    active
                                        ? 'bg-[#25CEDE] text-white'
                                        : 'text-gray-900',
                                    'group flex  items-center w-full px-4 py-2 text-sm',
                                ]"
                            >
                                Cerrar sesion
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </Menu>

            <input type="file" class="hidden" ref="inputFile" @change="onAvatarSelected"/>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.header-wrapper {
    position: fixed;
    top: 0;
    width: 100%;
    height: 80px;
    z-index: 100;
    box-shadow: 0 1px 15px rgb(0 0 0 / 4%), 0 1px 6px rgb(0 0 0 / 4%);
}
.mega-menu {
    width: 1200px;
}
ul.links {
    column-count: 2;
    li {
        margin-bottom: 8px;
    }
}
</style>
