import axios from "./axios";


export default {
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

    buscarProceso(id) {
        return axios.get("procesos/" + id);
    },

    editarProceso(id, nombre, descripcion) {
        const body = {
            Nombre: nombre,
            Descripcion: descripcion
        }
        return axios.put("procesos/" + id, body);
    },

    eliminarProceso(id) {
        return axios.delete("procesos/" + id);
    },

    async getAll() {
        const response = await axios.get("procesos");
        const { data } = response;
        return data;
    },

    async getProcesosByEtapa(id) {
        const url = `procesos/${id}/etapas`;;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getUsuariosByProceso(id) {
        const url = `procesos/${id}/usuarios`;;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },


    async addUsersToProcess(idProceso, usersIdArr) {
        const body = {
            usersIdArr
        }
        const url = `procesos/${idProceso}/usuarios`;
        const response = await axios.post(url, body);
        const { data } = response;
        return data;

    },


    async removeUserFromProcess(idProceso, idUsuario) {
        const url = `procesos/${idProceso}/usuarios/${idUsuario}`;;
        const response = await axios.delete(url);
        const { data } = response;
        return data;
    },


    async getUsersNotInProcess(id) {
        const url = `procesos/${id}/usuarios/notInProcess`;;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getProcesosByUser(id) {
        const url = `procesos/${id}/procesos`;
        const response = await axios.get(url);
        const { data } = response;
        return data;
    }

}