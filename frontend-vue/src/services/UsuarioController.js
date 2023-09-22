import axios from "axios";

const URL = "http://localhost:8000/api/usuarios";

export default{
    listaUsuarios() {
        return axios.get(URL);
    },

    nuevoUsuario(name, email, pass) {
        const body = {
            name: name,
            email: email,
            password: pass
        }
        return axios.post(URL, body);
    },

    buscarUsuario(id){
        return axios.get(URL + '/' + id);
    },

    editarUsuario(id, name, email, pass) {
        const body = {
            name: name,
            email: email,
            password: pass
        }
        return axios.put(URL + '/' + id, body);
    },

    eliminarUsuario(id) {
        return axios.delete(URL + '/' + id);
    }

}