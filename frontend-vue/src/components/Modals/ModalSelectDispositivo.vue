<template>
    <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50" >
        <div class="modal-overlay fixed inset-0 bg-black opacity-50"></div>
        <div class="modal-container bg-white w-1/3 mx-auto rounded shadow-lg z-50 overflow-y-auto h-[70vh]" >
            <div class="modal-content py-4 text-left px-6">
                
                    <div class="card-header">
                        <div class="col-2 flex justify-end items-center">
                            <button
                                class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                @click="$emit('onClose')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="col-10">                       
                            <div class="card-title">Dispositivos</div>                   
                        </div>
                    </div>
                    <input
                        class="w-full px-4 py-1 bg-gray-100 focus:outline-none border border-gray-400"
                        type="text"
                        placeholder="Buscar"
                        v-model="filter.search"
                        @input="handleSearch"
                    />
                    <template v-if="componentes.length > 0">
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
                    </template>
                    <template v-else>
                        <p class="w-full text-center mt-4">No hay dispositivos disponibles.</p>
                    </template>
                    
                    <div class="flex justify-center mt-2">
                        <infinite-loading
                            @infinite="loadMoreData"
                            v-if="hasMoreData"
                        ></infinite-loading>
                    </div>
                
                </div>
            </div>
        </div>
</template>

<script>

import ComponenteController from '../../services/ComponenteController';
import { appStore } from '../../store/app';
import CardDevice from '../../components/Cards/CardDevice.vue'
import InfiniteLoading from 'v3-infinite-loading'
import 'v3-infinite-loading/lib/style.css';

const $appStore = appStore();

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
        hasMoreData: false
      };
    },
    created() {
        $appStore.setGlobalLoading(true);
        this.getComponentes();
    },
    methods: {
        getComponentes(){
            console.log([this.page, this.maxRows, this.filter]);
            ComponenteController.list(this.page, this.maxRows, this.filter).then((response) => {
                console.log(response);
                const { data, countRows } = response;
                //this.componentes.push(data);
                this.componentes = [...this.componentes, ...data]
                this.hasMoreData = this.componentes.length < countRows;
                $appStore.setGlobalLoading(false);
                this.loading = false;
            });
        },
        loadMoreData(){
            this.page += 1;
            this.getComponentes();
        },
        handleSearch(){
            this.componentes = [];
            this.page = 1;
            $appStore.setGlobalLoading(true);
            this.getComponentes();
        },
        handleSelectedDevice(item) {
            this.$emit('dispositivoSelected', item);
        }

    },
    components: { InfiniteLoading, CardDevice }
}

</script>
