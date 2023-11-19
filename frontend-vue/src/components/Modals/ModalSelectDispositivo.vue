<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50" >
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white md:w-1/3 mx-auto rounded shadow-lg z-50 ">
            <div class="modal-content py-4 text-left px-6">
                
                    <div class="card-header flex justify-between items-center">         
                        <div class="card-title">
                            <p class="text-xl font-semibold "> Seleccionar Dispositivo </p>
                        </div>
                        <BaseBtn
                            sm
                            @click="$emit('onClose')">
                            <i class="fas fa-times"></i>
                        </BaseBtn>
                    </div>


                    <input
                        class="w-full p-2 mt-4 text-sm text-gray-900 rounded-lg bg-gray-50 focus:outline-none border border-gray-400"
                        type="text"
                        placeholder="Buscar"
                        v-model="filter.search"
                        @input="handleSearch"         
                    />
                    <div id="scrollContainer2" class=" overflow-y-auto  h-[50vh]" >
                    <div class="w-full text-center mt-4">
                        <spinner v-if="loading" :show="loading"></spinner>
                    </div>
                    <div v-if="componentes.length == 0 && !loading" class="w-full">
                        <div class="w-full bg-white p-8 rounded-md shadow-md">
                            <h2 class="text-2xl font-semibold mb-4">
                                No se encontraron dispositivos
                            </h2>
                            <p class="text-gray-600">
                                No hay ningún dispositivo disponible
                            </p>
                        </div>
                    </div>
                    <div v-if="componentes.length > 0 && !loading">
                            <div class="mt-2">
                                <CardDevice 
                                    :selected="selected == null"
                                    :key="null" 
                                    :nombre="'Todos'" 
                                    :ipAddress="''"
                                    :value="null" 
                                    :image="'/src/assets/images/none.png'" 
                                    @click="handleSelectedDevice(null)">
                                </CardDevice>
                            </div>
                            <div class="mt-2" v-for="item in componentes" >
                                <CardDevice 
                                    :selected="selected == item.id"
                                    :key="item.id" 
                                    :nombre="item.Nombre" 
                                    :ipAddress="item.DireccionIp"
                                    :value="null" 
                                    :image="item.tipoComponenteImage" 
                                    @click="handleSelectedDevice(item)">
                                </CardDevice>
                            </div>
                        </div>
                    
                    <div class="flex justify-center mt-2">
                        <infinite-loading
                            @infinite="loadMoreData"
                            v-if="hasMoreData"
                        ></infinite-loading>
                    </div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>

import ComponenteController from '../../services/ComponenteController';
import CardDevice from '../../components/Cards/CardDevice.vue';
import InfiniteLoading from 'v3-infinite-loading'
import 'v3-infinite-loading/lib/style.css';
import spinner from '../../views/components/spinner/spinner.vue';
import PerfectScrollbar from 'perfect-scrollbar';
import 'perfect-scrollbar/css/perfect-scrollbar.css';

export default {
    props: {
      selected: Number,
      show: Boolean
    },
    data() {
      return {
        componentes: [],
        maxRows: 10,
        page: 1,
        filter: {
            search: ''
        },
        loading: true,
        hasMoreData: false,
        loading: true,
        searchTimeout: null
      };
    },
    created() {
        this.getComponentes();
    },
    mounted(){
        const container = document.getElementById('scrollContainer2');
        new PerfectScrollbar(container);
    },
    methods: {
        getComponentes(){
            console.log([this.page, this.maxRows, this.filter]);
            ComponenteController.list(this.page, this.maxRows, this.filter).then((response) => {
                console.log(response);
                const { data, countRows } = response;
                this.componentes = [...this.componentes, ...data]
                this.hasMoreData = this.componentes.length < countRows;
                this.loading = false;

            });
        },
        loadMoreData(){
            this.page += 1;
            this.getComponentes();
        },
        handleSearch(){
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.componentes = [];
                this.page = 1;
                this.loading = true;
                this.getComponentes();
            }, 300);
        },
        handleSelectedDevice(item) {
            this.$emit('dispositivoSelected', item);
        }

    },
    components: { InfiniteLoading, CardDevice, spinner }
}

</script>
