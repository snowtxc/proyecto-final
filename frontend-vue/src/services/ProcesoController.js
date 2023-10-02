import axios from "./axios";

export default{
    async getAll() {
       const response  = await axios.get("procesos");
       const { data } = response;
       return data;
    },

    async getProcesosByEtapa(id) {
        const url = `procesos/${id}/etapas`;       ;
        const response  = await axios.get(url);
        const { data } = response;
        return data;
    }
}