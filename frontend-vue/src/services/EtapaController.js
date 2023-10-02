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

    editarEtapa(id, nombre, descripcion, proceso_id) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion,
            proceso_id: proceso_id
        }
        return axios.put("etapas/"+id, body);
    },

    eliminarEtapa(id) {
        return axios.delete("etapas/"+id);
    }


}