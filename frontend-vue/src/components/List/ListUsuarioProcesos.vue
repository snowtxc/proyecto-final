<template>

    <div
        class="w-full h-auto max-h-[730px] space-y-4 overflow-y-auto p-5 flex flex-col items-center"
    >
        <ModalSelectUserProcess v-if="rol == 'Administrador'"
            :processId="props.procesoId"
            @onAddUsers="addNewUsersToProcess"
        ></ModalSelectUserProcess>
        <spinner v-if="loading" :show="loading"></spinner>
        <div v-if="!loading && usersEmpty" class="w-full">
            <div class="w-full bg-white p-8 rounded-md shadow-md" v-if="usersEmpty && !loading">
                <h2 class="text-2xl font-semibold mb-4">
                    No se encontraron usuarios
                </h2>
                <p class="text-gray-600">
                    No existe ningun usuario para agregar al grupo de este
                    proceso
                </p>
            </div>
        </div>
        <div v-if="!loading && !usersEmpty" class="w-full flex flex-col gap-4">
            <Card v-for="user in props.usuarios" :key="user.id"
                class="w-full hover:bg-gray-100 transition-colors duration-150 ease-in-out bg-white p-4 rounded-md cursor-pointer">
                <div class="flex flex-row items-center justify-between">
                    <div class="flex items-center">
                        <img :src="user.profileImage
                                ? user.profileImage
                                : imageProfileDefault
                            " alt="User Image" class="w-12 h-12 rounded-full mr-2 object-cover" />

                        <div class="flex flex-col">
                            <p class="font-bold text-xl">{{ user.name }}</p>
                            <p>{{ user.email }}</p>
                            <p class="text-gray-500">{{ user.rol }}</p>
                        </div>
                    </div>

                    <font-awesome-icon
                        v-if="rol == 'Administrador'"
                        :icon="['far', 'trash-can']"
                        class="w-5 h-5 m-4 hover:text-primary"
                        @click="removeUser(user)"
                    />
                </div>
            </Card>
        </div>
    </div>

    <ConfirmationModal :title="title" :message="text" :show="showModalRemove" @cancel="showModalRemove = false"
        @confirm="onRemoveUser"></ConfirmationModal>
</template>

<script setup>
import { ref, computed, defineEmits } from 'vue'
import { appStore } from '../../store/app'
import ConfirmationModal from '../ConfirmationModal.vue'
import ModalSelectUserProcess from '../Modals/ModalSelectUserProcess.vue';
import ProcesoController from '../../services/ProcesoController';

import { useNotification } from '@kyvg/vue3-notification'

const { notify } = useNotification()
const $appStore = appStore()

const rol = $appStore.getUserData?.rol;

const loading = ref(false)
const props = defineProps({
    procesoId: {
        type: Number,
        required: true,
    },

    procesoName: {
        type: String,
        required: true,
    },
    usuarios: {
        type: Array,
        required: true,
    },
})

const emit = defineEmits(['onRemoveUser'])

const userIdSelected = ref(null)
const showModalRemove = ref(false)
const title = ref('Remover usuario')
const text = ref(
    '¿Está seguro que desea remover este usuario del proceso?'
)

const imageProfileDefault = computed(() => {
    return $appStore.getImageProfileDefault
})

const removeUser = (user) => {
    userIdSelected.value = user.id
    showModalRemove.value = true
}

const onRemoveUser = () => {
    if (!userIdSelected.value) {
        return
    }
    showModalRemove.value = false
    emit('onRemoveUser', userIdSelected.value)
}

const addNewUsersToProcess = async (users) => {
    const usersId = users.map((u) => u.id)
    if (usersId.length === 0) {
        return
    }
    $appStore.setGlobalLoading(true)
    try {
        const data = await ProcesoController.addUsersToProcess(
            props.procesoId,
            usersId
        )
        $appStore.setGlobalLoading(false)
        notify({
            title: 'Usuarios agregados',
            text: 'Los usuarios fueron agregados al proceso correctamente',
            type: 'success',
        })
        emit('onAddUsers', users)
    } catch (e) {
        notify({
            title: 'Error',
            text: 'Ocurrió un error al agregar los usuarios al proceso',
            type: 'error',
        })
        $appStore.setGlobalLoading(false)
        console.log(e)
    }
}


const usersEmpty = computed(() => {
    return props.usuarios.length === 0;
});


</script>

<style scoped>
.delete:hover {
    color: #ef4444;
}

.edit:hover {
    color: #f59e0b;
}
</style>
