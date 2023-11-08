<template>
    <BaseCard>
        <template v-slot:cardHeader>
            <div class="card-header flex justify-between items-center">
                <div class="card-title py-3">Partes</div>
                <AgregarParteModal></AgregarParteModal>
            </div>
        </template>
        <div class="
                            block
                            w-full
                            overflow-x-auto
                            whitespace-nowrap
                            borderless
                            hover
                        ">
            <div class="
                                dataTable-wrapper dataTable-loading
                                no-footer
                                fixed-columns
                            ">
                <div class="
                                    dataTable-container
                                    block
                                    w-full
                                    overflow-x-auto
                                    whitespace-nowrap
                                    borderless
                                    hover
                                ">
                    <table class="
                                        table-3
                                        dataTable-table
                                        max-w-full
                                        w-full
                                    ">
                        <thead>
                            <tr class="">

                                <th class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                ">
                                    Nombre
                                </th>
                                <th class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                ">
                                    Descripcion
                                </th>

                                <th class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                ">
                                    Fecha
                                </th>


                                <th class="
                                                    text-left
                                                    border-b
                                                    pb-3
                                                    mb-3
                                                    text-gray-500
                                                    font-semibold
                                                ">
                                    Accion
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            <tr class="
                                                hover:bg-gray-100
                                                cursor-pointer
                                            " v-for="(n, index) in 8" :key="index">


                                <td class="text-xs">
                                    Jhon {{ index + 1 }}
                                </td>
                                <td class="text-xs">
                                    Jhon {{ index + 1 }}
                                </td>
                                <td class="text-xs">
                                    12-05-2001 15:03 PM
                                </td>

                                <td class="py-5">
                                    <BaseBtn rounded class="
                                                        border border-primary
                                                        text-primary
                                                        hover:bg-primary
                                                        hover:text-white
                                                    ">
                                        Ver Notas
                                        <i class="fa-solid fa-note-sticky ml-2"></i>
                                    </BaseBtn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </BaseCard>
</template>

<script setup>
import AgregarParteModal from './AgregarParteModal.vue';
</script>