<template>
    <div class="col-span-12">
        <BaseCard>
            <template v-slot:cardHeader>
                <div class="card-title m-5">Alarmas </div>
                <div class="card-header flex justify-between items-center ">


                    <div class="flex flex-col md:flex-row md:items-end">
                        <div class="mb-2">
                            <label for="filtroProceso" class="block text-sm text-gray-700">Proceso:</label>
                            <select id="filtroProceso" v-model="filtroProceso" @change="filter"
                                class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400">
                                <option class="rounded" v-for="proceso in procesos" :key="proceso.id" :value="proceso.id">
                                    {{ proceso.Nombre }}
                                </option>
                            </select>
                        </div>

                        <div class="mb-2  md:ml-8 flex flex-col md:flex-col md:items-start">
                            <label for="fechaInicio" class="block text-sm text-gray-700">Rango de fechas:</label>
                            <div class="flex flex-col md:flex-row md:items-center">
                                <input type="date" id="fechaInicio" v-model="filtroFechaIni" @change="filter"
                                class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400">
                            -
                            <input type="date" id="fechaFin" v-model="filtroFechaFin" @change="filter"
                                class="w-40 p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none border border-gray-400">
                            </div>
                            
                        </div>

                        <div class="mb-2 md:ml-8">
                            <label for="filtroProceso" class="block text-sm text-gray-700">Dispositivo:</label>
                            <BaseBtn maxWidth="500px" @click="showModalDispositivos = true"> {{ filtroComponente ?
                                selectedDispositivo.Nombre : 'Seleccionar' }}

                                <i class="fa-solid fa-filter"></i>
                            </BaseBtn>

                        </div>

                    </div>
                </div>
            </template>
            <p class="px-4 py-3" v-if="alarmas.length == 0"> No se encontraron alarmas</p>
            <div v-if="alarmas.length > 0">
                <div v-for="(item, index) in alarmas" :key="index"
                    class="flex overflow-hidden flex-row mb-6 shadow-md rounded-xl">

                    <div class="flex pl-2 flex-1">
                        <div class="flex flex-grow flex-col self-center justify-between lg:items-center lg:flex-row">
                            <!--a class="hover:text-primary" href=""></a-->
                            <div class="flex">
                                <img class="w-20 object-fill" :src="item.tipoComponenteImagen" alt="" />
                            </div>
                            <p>{{ item.componenteNombre }}</p>
                            <p>{{ item.tipoComponente }}</p>
                            <p>{{ item.procesoNombre }}</p>
                            <p>{{ formattedDate(item.fechaHora) }}
                            </p>
                            <i class="fa-solid fa-users m-4 text-2xl hover:text-primary"
                                @click="this.selectedAlarma = item.id, this.showModalUsuarios = true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-2">
                <infinite-loading @infinite="loadMoreData" v-if="hasMoreData"></infinite-loading>
            </div>

        </BaseCard>
    </div>

    <ModalUsersAlarma v-if="showModalUsuarios" :show="showModalUsuarios" :alarmaId="selectedAlarma"
        @onClose="showModalUsuarios = false">
    </ModalUsersAlarma>

    <ModalSelectDispositivo v-if="showModalDispositivos" :show="showModalDispositivos" :selected="filtroComponente"
        @onClose="showModalDispositivos = false" @dispositivoSelected="dispositivoSelected">
    </ModalSelectDispositivo>
</template>

<script>

import AlarmaController from '@/services/AlarmaController';
import ProcesoController from '@/services/ProcesoController';
import { appStore } from "@/store/app.js";
import ModalUsersAlarma from '../../components/Modals/ModalUsersAlarma.vue';
import ModalSelectDispositivo from '../../components/Modals/ModalSelectDispositivo.vue';
import dayjs from "dayjs";
import InfiniteLoading from 'v3-infinite-loading'
import 'v3-infinite-loading/lib/style.css';

const $appStore = appStore();

export default {
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
            actualPage: 1,
            selectedDispositivo: {
                id: null,
                Nombre: 'Todos'
            },
            showModalUsuarios: false,
            showModalDispositivos: false,
            hasMoreData: false
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
            AlarmaController.listaAlarmas(this.actualPage, this.filtroProceso, this.filtroComponente, fecha1, fecha2).then((response) => {
                if (response.status == 200) {
                    const { data, countRows } = response.data;
                    this.alarmas = [...this.alarmas, ...data]
                    this.hasMoreData = this.alarmas.length < countRows;
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
        formattedDate(fechaHora) {
            return dayjs(fechaHora).format('DD/MM/YYYY HH:mm A');
        },
        loadMoreData() {
            this.actualPage += 1;
            this.getAlarmas();
        },
        filter() {
            $appStore.setGlobalLoading(true);
            this.alarmas = [];
            this.actualPage = 1;
            this.getAlarmas();
        },
        dispositivoSelected(item) {
            item == null ? this.filtroComponente = item : this.filtroComponente = item.id;
            this.selectedDispositivo = item;
            this.showModalDispositivos = false;
            this.filter();
        }
    },

    computed: {

    },

    components: { ModalUsersAlarma, InfiniteLoading, ModalSelectDispositivo }
}


</script>
