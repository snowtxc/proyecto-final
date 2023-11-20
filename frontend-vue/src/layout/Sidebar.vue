<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router';

import { appStore } from '../store/app';

const $appStore = appStore();
const userData = $appStore.userdata;

let store = useStore()
let route = useRoute()

let toggleSubMenu = (e) => {
    // let parent = e.target.dataset.item;
    console.log(e.target)
}

onMounted(() => {
    window.addEventListener('resize', handleWindowResize)
})
// beforeDestroy
onBeforeUnmount(() => {
    window.removeEventListener('resize', handleWindowResize)
})

let handleWindowResize = () => {
    let sidenav = store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen

    if (window.innerWidth <= 1200) {
        if (store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen) {
            store.commit('largeSidebar/toggleSidebarProperties')
        }
    } else {
        if (!store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen) {
            store.commit('largeSidebar/toggleSidebarProperties')
        }
    }
}
</script>
<template>
    <div class="side-content-wrap" v-if="userData.rol != 'Observador'">
        <div class="side-content-wrap">
            <div
                :class="
                    store.state.largeSidebar.sidebarToggleProperties
                        .isSideNavOpen === true
                        ? 'open'
                        : ''
                "
                class="sidebar-left"
            >
                <perfect-scrollbar>
                    <ul class="navigation-left">
                        <!-- <div 
                            @mouseenter="toggleSubMenu" 
                            class="nav-item" 
                            :class="selectedParentMenu == 'dashboards' ? 'active' : ''"
                            data-item="dashboards"
                            
                        ></div> -->
                        <router-link 
                            to="/dashboards/dashboard-version-one"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="i-Bar-Chart text-3xl"></i>
                                <p>Dashboard</p>
                            </div>
                        </router-link>

                        <router-link
                            to="/diagrama"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="fa-solid fa-diagram-project"></i>
                                <p>Diagrama de Procesos</p>
                            </div>
                        </router-link>


                         <router-link
                            to="/procesos"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="fa-solid fa-industry text-3xl"></i>
                                <p>Procesos</p>
                            </div>
                        </router-link>

                        <router-link
                            to="/dispositivos"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="fa-solid fa-microchip text-3xl"></i>
                                <p>Dispositivos</p>
                            </div>
                        </router-link>

                        <router-link
                            to="/alarmas"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="fa-solid fa-exclamation-triangle text-3xl"></i>
                                <p>Alarmas</p>
                            </div>
                        </router-link>


                        <router-link
                            to="/reportes"
                            tag="li"
                            class="nav-item"
                        >
                            <div class="nav-item-hold">
                                <i class="fa-solid fa-chart-pie text-3xl"></i>                                
                                <p>Reportes</p>
                            </div>
                        </router-link>
                        
                        <div v-if="userData.rol == 'Administrador'">

                            <router-link
                                to="/tipos-componentes"
                                tag="li"
                                class="nav-item"
                            >
                                <div class="nav-item-hold">
                                    <i class="fa-solid fa-plug-circle-minus text-3xl"></i>
                                    <p>Tipos de Dispositivos</p>
                                </div>
                            </router-link>

                            <router-link
                                to="/usuarios"
                                tag="li"
                                class="nav-item"
                            >
                                <div class="nav-item-hold">
                                    <i class="fa-solid fa-users text-3xl"></i>
                                    <p>Usuarios</p>
                                </div>
                            </router-link>

                        </div>

                        
                        
                    </ul>
                </perfect-scrollbar>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.nav-item.router-link-exact-active {
    @apply text-primary;

    &:after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        bottom: -15px;
        right: -15px;
        transform: rotate(45deg);
        @apply bg-primary;
    }
}
.submenuLi {
    &:hover {
        .submenuli-icon {
            color: #8b5cf6;
        }
    }
    .submenuli-icon {
        color: #9ca3af;
    }
}
.submneu-nested-link {
    padding: 0 !important;
    color: #000 !important;
    &:hover {
        background-color: transparent !important;
        color: #8b5cf6 !important;
    }
}
.side-content-wrap {
    .sidebar-left {
        position: fixed;
        top: 80px;
        left: -120px;
        width: 120px;

        background: #fff;
        box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06),
            0 1px 4px rgba(0, 0, 0, 0.08);
        z-index: 90;
        transition: all 0.24s ease-in-out;
        &.open {
            left: 0;
            transition: all 0.24s ease-in-out;
        }
        .ps {
            height: calc(100vh - 80px);
        }
        .navigation-left {
            list-style: none;
            text-align: center;
            width: 120px;
            height: 100%;
            margin: 0;
            padding: 0;
            .nav-item,
            .nav-item-single {
                position: relative;
                display: block;
                width: 100%;
                overflow: hidden;
                cursor: pointer;
                &:hover {
                    .nav-item-hold {
                        @apply text-primary;
                    }
                    &:after {
                        content: '';
                        position: absolute;
                        width: 30px;
                        height: 30px;
                        bottom: -15px;
                        right: -15px;
                        transform: rotate(45deg);
                        @apply bg-primary;
                    }
                }

                &.active {
                    @apply text-primary;

                    &:after {
                        content: '';
                        position: absolute;
                        width: 30px;
                        height: 30px;
                        bottom: -15px;
                        right: -15px;
                        transform: rotate(45deg);
                        @apply bg-primary;
                    }
                }
                border-bottom: 1px solid #dee2e6;
                .nav-item-hold {
                    display: block;
                    width: 100%;
                    padding: 26px 0;
                    span.material-icons {
                        font-size: 2rem;
                    }
                }
            }
        }
    }
    .sidebar-left-secondary {
        position: fixed;
        top: 80px;
        left: calc(-220px - 20px);
        z-index: 89;
        height: calc(100vh - 80px);
        width: 220px;
        padding: 0.75rem 0;
        transition: all 0.24s ease-in-out;
        background: #fff;

        &.open {
            left: 120px;
            transition: all 0.24s ease-in-out;
        }
        ul.childNav {
            li {
                &.dropdown-sidemenu {
                    display: block;
                    transition: all 0.3s ease-in;
                    &.open {
                        a {
                            .dd-arrow {
                                transform: rotate(90deg);
                                transition: all 0.3s ease-in;
                            }
                        }
                        ul.submenu {
                            display: block;
                            max-height: 1000px;
                            transition: all 0.3s ease-in;
                        }
                    }

                    a {
                        .dd-arrow {
                            margin-left: auto !important;
                            transition: all 0.3s ease-in;
                        }
                    }
                }

                // &.active {
                //     a {
                //         background-color: #f3f4f6;
                //         @apply text-primary;
                //     }
                // }
                a {
                    text-transform: capitalize;
                    display: flex;
                    align-items: center;
                    font-size: 13px;
                    cursor: pointer;
                    padding: 12px 24px;
                    transition: 0.15s all ease-in;
                    &:hover {
                        background-color: #f3f4f6;
                        @apply text-primary;
                    }
                    &.router-link-active.router-link-exact-active {
                        @apply text-primary;
                    }
                }
                ul.submenu {
                    @apply bg-gray-50;
                    display: none;
                    max-height: 0px;
                    transition: all 0.3s ease-in;

                    &.open {
                        display: block;
                        transition: all 0.3s ease-in;
                    }
                    li {
                        a {
                            padding-left: 48px;
                        }
                    }
                }
            }
        }
    }
    .sidebar-overlay {
        display: none;
        position: fixed;
        width: calc(100% - 120px - 220px);
        height: calc(100vh - 80px);
        bottom: 0;
        right: 0;
        background: rgba(0, 0, 0, 0);
        z-index: 101;
        cursor: w-resize;
        &.open {
            display: block;
        }
    }
}
</style>
