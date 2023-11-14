<script setup>
    import { onBeforeMount,ref ,computed,onMounted,onUnmounted} from "vue";
    import { MenuItem,MenuItems,Menu ,MenuButton} from "@headlessui/vue";
    import UsuarioController from "@/services/UsuarioController";
    import  { pushAlarmaNotificationChannel } from "@/shared/helpers/channels";
    import { appStore } from "@/store/app";
    import spinner from "../../views/components/spinner/spinner.vue";
    import dayjs from "dayjs"; 

    const $appStore = appStore();
    const myNotifications = ref([]);
    const countNotifications = ref([]);
    const userId = $appStore.getUserData.id;
    onBeforeMount(()=>{
        UsuarioController.getMyAlarmsNotifications().then(alarms =>{
            myNotifications.value = alarms;
            countNotifications.value = alarms.length;
        })
    })

    const listNotifications = computed(()=>{
        return myNotifications.value.map(item =>{
            const { created_at } = item;
            return{ 
                ...item,
                created_at: dayjs(created_at).format("DD/MM/YYYY hh:mm a")
            }
        })
    })

    const onReadNotifications = ()=>{
        countNotifications.value = 0;
        UsuarioController.readMyAlarmsNotifations().then();
    }

    onMounted(()=>{
        window.Echo.channel(pushAlarmaNotificationChannel(userId)).listen('PushAlarmaNotificacion', (data) =>{
            myNotifications.value.unshift(data);
            countNotifications.value++;
        })
    })

    onUnmounted(()=>{
        window.Echo.leave(pushAlarmaNotificationChannel(userId));
    })
</script>

<template>
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
                        @click="onReadNotifications"
                    >
                        <span class="badge-count text-white bg-primary"
                            v-if="countNotifications > 0"
                            >{{ countNotifications }}</span
                        >
                        <i class="i-Bell text-xl header-icon text-gray-800"></i>
                    </MenuButton>
                </div>

                <MenuItems
                    class="
                        absolute
                        right-0
                        w-[600px]	
                        max-h-[900px]
                        mt-2
                        overflow-y-auto
                        origin-top-right
                        bg-white
                        rounded-md
                        custom-box-shadow
                        focus:outline-none
                        
                    "
                >
                    <div class="">
                        <div class="p-5 flex items-center justify-center" v-if="countNotifications <= 0 && myNotifications.length <= 0">
                             <p>No tienes nuevas notificaciones de alarmas</p>
                            <i class="fa-regular fa-bell ml-5"></i>
                        </div>
                        <MenuItem
                            v-for="(item) in listNotifications"
                            :key="item.id"
                            v-slot="{ active }"
                        >
                            <button
                                :class="[
                                    active
                                        ? 'bg-primary text-white'
                                        : 'text-gray-900',
                                    'group flex flex-col items-center w-full px-4 py-2 text-sm',
                                ]"
                            >
                                <div class="flex  flex-1 justify-between items-center">
                                    
                                    <div class="flex ">
                                        <img
                                            class="w-10 h-10 m-4 shadow-lg avatar-md object-fill"
                                            :src= "item.componente.tipoComponenteImage"
                                            alt=""
                                        />
                                        <div class="flex flex-col">   
                                            <h6 class="text-red-700">Atencion!</h6>
                                                <p
                                                    class="text-xs"
                                                    :class="[
                                                        active
                                                            ? 'text-gray-300'
                                                            : 'text-gray-500',
                                                    ]"
                                                >
                                                   {{ item.motivo }}
=                                               </p>

                                        </div>
                                       
                                    </div>
                                    <div>
                                        <p
                                            :class="[
                                                active
                                                    ? 'text-gray-200'
                                                    : 'text-gray-400',
                                            ]"
                                        >
                                            {{ item.created_at }}
                                        </p>
                                    </div>
                                    
                                </div>
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
    </Menu>
</template>

