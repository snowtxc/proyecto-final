import axios from "./axios";


export default{
    listaEtapas(procesoId) {
        return axios.get("etapas/" + procesoId);
    },

    nuevaEtapa(nombre, descripcion,procesoId) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion,
            proceso_id: procesoId
        }
        return axios.post("etapas", body);
    },

    buscarEtapa(id){
        return axios.get("etapas/"+id);
    },

    editarEtapa(id, nombre, descripcion) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion
        }
        return axios.put("etapas/"+id, body);
    },

    eliminarEtapa(id) {
        return axios.delete("etapas/"+id);
    }


}