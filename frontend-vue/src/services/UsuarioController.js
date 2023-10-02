import axios from "./axios";

export default{
    listaUsuarios() {
        return axios.get("usuarios");
    },

    nuevoUsuario(name, email, pass) {
        const body = {
            name: name,
            email: email,
            password: pass
        }
        return axios.post("usuarios", body);
    },

    buscarUsuario(id){
        return axios.get("usuarios/"+id);
    },

    editarUsuario(id, name, email, pass) {
        const body = {
            name: name,
            email: email,
            password: pass
        }
        return axios.put("usuarios/"+id, body);
    },

    eliminarUsuario(id) {
        return axios.delete("usuarios/"+id);
    }


}