import axios from "./axios";


export default{
    listaProcesos() {
        return axios.get("procesos");
    },

    nuevoProceso(nombre, descripcion) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion
        }
        return axios.post("procesos", body);
    },

    buscarProceso(id){
        return axios.get("procesos/"+id);
    },

    editarProceso(id, nombre, descripcion) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion
        }
        return axios.put("procesos/"+id, body);
    },

    eliminarProceso(id) {
        return axios.delete("procesos/"+id);
    },

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