<script setup>
    import { onBeforeMount,ref ,computed} from "vue";
    import { MenuItem,MenuItems,Menu ,MenuButton} from "@headlessui/vue";
    import UsuarioController from "@/services/UsuarioController";
    import spinner from "../../views/components/spinner/spinner.vue";
    import dayjs from "dayjs"; 

    const myNotifications = ref([]);
    onBeforeMount(()=>{
        UsuarioController.getMyAlarmsNotifications().then(alarms =>{
            myNotifications.value = alarms;
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

    const countNotifications = computed(()=>{
        return myNotifications.value.length;
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
                    >
                        <span class="badge-count text-white bg-primary"
                            >{{ countNotifications }}</span
                        >
                        <i class="i-Bell text-xl header-icon text-gray-800"></i>
                    </MenuButton>
                </div>

                <MenuItems
                    class="
                        absolute
                        right-0
                        w-96	
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
                                            class="w-14 h-14 m-4 shadow-lg avatar-md rounded-full object-fill"
                                            :src= "item.componente.tipoComponenteImage"
                                            alt=""
                                        />
                                        <div class="flex flex-col">   
                                            <h6 class="text-red-700">El Dispositivo Sensor K26232 ha generado una alarma!!</h6>
                                                <p
                                                    :class="[
                                                        active
                                                            ? 'text-gray-300'
                                                            : 'text-gray-500',
                                                    ]"
                                                >
                                                   Se ha registrado una marca de 50&
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

