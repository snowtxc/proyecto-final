import dayjs from "dayjs";
import axios from "./axios";

export default{
    async getProcesosMasActivo(filters){
        let url = "reportes/procesosMasActivos";
        if(filters.startDate){
            const start =  dayjs(filters.startDate).format("DD/MM/YYYY HH:mm");
            url =  url+"?startDate="+start;
        }

        if(filters.endDate){
            const end =  dayjs(filters.endDate).format("DD/MM/YYYY HH:mm");
            url = filters.startDate ? url+"&endDate="+end : url+"?endDate="+end;
        }
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getAlarmasPorDispositivos(filters){
        let url = `reportes/alarmasPorDispositivos`;
        if(filters.startDate){
            const start =  dayjs(filters.startDate).format("DD/MM/YYYY HH:mm");
            url =  url+"?startDate="+start;
        }
        if(filters.endDate){
            const end =  dayjs(filters.endDate).format("DD/MM/YYYY HH:mm");
            url = filters.startDate ? url+"&endDate="+end : url+"?endDate="+end;
        }
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async  getDispositivosMasActivos(filters){
        let url = `reportes/dispositivosMasActivos`;
        if(filters.startDate){
            const start =  dayjs(filters.startDate).format("DD/MM/YYYY HH:mm");
            url =  url+"?startDate="+start;
        }
        if(filters.endDate){
            const end =  dayjs(filters.endDate).format("DD/MM/YYYY HH:mm");
            url = filters.startDate ? url+"&endDate="+end : url+"?endDate="+end;
        }
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getAlarmasPorProceso(filters){
        let url = `reportes/alarmasPorProceso`;
        if(filters.startDate){
            const start =  dayjs(filters.startDate).format("DD/MM/YYYY HH:mm");
            url =  url+"?startDate="+start;
        }
        if(filters.endDate){
            const end =  dayjs(filters.endDate).format("DD/MM/YYYY HH:mm");
            url = filters.startDate ? url+"&endDate="+end : url+"?endDate="+end;
        }
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getDispositivosActivosEInactivos(){
        const url = `reportes/dispositivosActivosEInactivos`;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getDispositivosActivosPorProceso(){
        const url = `reportes/dispositivosActivosPorProceso`;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getCantidadDispositivosPorTipo(){
        const url = `reportes/cantidadDispositivosPorTipo`;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getCantidadAlarmasPorTipoDispositivo(){
        const url = `reportes/cantidadAlarmasPorTipoDispositivo`;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    }
    

}