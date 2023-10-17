<template>
    <div class="col-span-12">
        <BaseCard>
            <template v-slot:cardHeader>
                <div class="card-title m-5">Alarmas </div>
                <div class="card-header flex justify-between items-center">
                    

                    <div class="flex ">
                    <div class="mb-2">
                        <label for="filtroProceso" class="block text-sm text-gray-700">Proceso:</label>
                        <select
                        id="filtroProceso"
                        v-model="filtroProceso"
                        @change="getAlarmas"
                        class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        >
                        <option
                            class="rounded"
                            v-for="proceso in procesos"
                            :key="proceso.id"
                            :value="proceso.id"
                        >
                            {{ proceso.Nombre }}
                        </option>
                        </select>
                    </div>

                    <div class="mb-2 ml-8">
                        <label for="fechaInicio" class="block text-sm text-gray-700">Rango de fechas:</label>
                        <input
                        type="date"
                        id="fechaInicio"
                        v-model="filtroFechaIni"
                        @change="getAlarmas"
                        class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        > - 
                        <input
                        type="date"
                        id="fechaFin"
                        v-model="filtroFechaFin"
                        @change="getAlarmas"
                        class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        >
                    </div>

                    </div>
                </div>
            </template>
            <p class="px-4 py-3" v-if="alarmas.length == 0"> No se encontraron alarmas</p>
            <div v-if="alarmas.length > 0">
                <div v-for="(item, index) in alarmas" :key="index" class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl">
                    
                    <div class="flex pl-2 flex-1">
                        <div class="flex flex-grow flex-col self-center justify-between lg:items-center lg:flex-row">
                            <!--a class="hover:text-primary" href=""></a-->
                            <div class="flex">
                                <img class="w-20 object-fill" :src="item.tipoComponenteImagen" alt="" />
                            </div>
                            <p>{{item.componenteNombre}}</p>   
                            <p>{{item.tipoComponente}}</p>
                            <p>{{item.procesoNombre}}</p>
                            <p>{{ formattedDate(item.fechaHora) }}
                            </p>
                            <i class="fa-solid fa-users m-4 text-2xl hover:text-primary"
                                @click="this.selectedAlarma = item.id, this.showModalUsuarios = true" 
                            ></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- This example requires Tailwind CSS v2.0+ -->
            <div v-if="totalPage > 1" class="bg-white px-4 py-3 flex items-center justify-between  sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Anterior
                    </a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Siguiente
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <!-- Heroicon name: solid/chevron-left -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            </a>
                            <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                            <a href="#" aria-current="page" class="z-10 bg-purple-500 border-purple-500 text-white relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            1
                            </a>
                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            2
                            </a>
                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium">
                            3
                            </a>
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                            </span>
                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 hidden md:inline-flex relative items-center px-4 py-2 border text-sm font-medium">
                            8
                            </a>
                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            9
                            </a>
                            <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                            10
                            </a>
                            <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <!-- Heroicon name: solid/chevron-right -->
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </BaseCard>
    </div>

    <ModalUsersAlarma 
        v-if="showModalUsuarios" 
        :show="showModalUsuarios" 
        :alarmaId="selectedAlarma" 
        @onClose="showModalUsuarios = false" 
        >
    </ModalUsersAlarma>
    
</template>

<script>

import AlarmaController from '@/services/AlarmaController';
import ProcesoController from '@/services/ProcesoController';
import { appStore } from "@/store/app.js";
import ModalUsersAlarma from '../../components/Modals/ModalUsersAlarma.vue';
import dayjs from "dayjs";

const $appStore = appStore();

export default{
    data() {
        return {
            alarmas: [],
            procesos: [],
            componentes: [],
            filtroProceso: null,
            filtroComponente: null,
            filtroFechaIni: null,
            filtroFechaFin: null,
            totalRows: 0,
            totalPage: 0,
            actualPage: 1,
            selectedAlarma: 0,
            showModalUsuarios: false,
            userLogged: $appStore.getUserData
        };
    },
    created() {
        $appStore.setGlobalLoading(true);
        this.getAlarmas();
        this.getProcesos();
    },
    methods: {
        getAlarmas() {
            const fecha1 = this.filtroFechaIni ? dayjs(this.filtroFechaIni).format("DD-MM-YYYY") : null;
            const fecha2 = this.filtroFechaFin ? dayjs(this.filtroFechaFin).format("DD-MM-YYYY") : null;
            console.log(fecha1);
            console.log(fecha2);
            AlarmaController.listaAlarmas(this.actualPage, this.filtroProceso, this.filtroComponente, fecha1, fecha2).then((response) => {
                console.log(response);
                if (response.status == 200) {
                    this.alarmas = response.data.data;
                    this.totalRows = response.data.countRows;
                    if (this.totalRows >= 10) {
                        this.totalPage = this.totalRows / 10;
                    }
                }
                $appStore.setGlobalLoading(false);
            });
        },
        getProcesos() {
            ProcesoController.listaProcesos().then((response) => {
                if (response.status == 200) {
                    this.procesos = response.data;
                }
                this.procesos.unshift({ id: null, Nombre: 'Todos' });
                $appStore.setGlobalLoading(false);
            });
        },
        formattedDate(fechaHora){
            return dayjs(fechaHora).format('DD/MM/YYYY HH:mm A');
        }
    },

    computed: {
        
    },

    components: { ModalUsersAlarma }
}


</script>
