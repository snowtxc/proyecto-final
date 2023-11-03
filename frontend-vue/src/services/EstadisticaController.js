import axios from "./axios";

export default{
    async countUsers() {
       const response  = await axios.get("estadisticas/cantidadUsuarios");
       const { data } = response;
       return data;
    },

    async countComponentes() {
        const response  = await axios.get("estadisticas/cantidadComponentes");
        const { data } = response;
        return data;
     },

     async countProcesos() {
        const response  = await axios.get("estadisticas/cantidadProcesos");
        const { data } = response;
        return data;
     },

     async countTipoComponentes() {
        const response  = await axios.get("estadisticas/cantidadTipoComponentes");
        const { data } = response;
        return data;
     },

     async countComponentePorProceso(){
        const response  = await axios.get("estadisticas/cantidadComponentesPorProcesos");
        const { data } = response;
        return data;
        
     },

     async countEtapasPorProceso(){
        const response  = await axios.get("estadisticas/cantidadEtapasPorProcesos");
        const { data } = response;
        return data;
        
     },

     async getActivityForProcessLastHour(){
        const response  = await axios.get("estadisticas/actividadPorProcesos");
        const { data } = response;
        return data;
     }

} 