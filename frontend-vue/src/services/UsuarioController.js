import axios from "./axios";

export default{
    listaUsuarios() {
        return axios.get("usuarios");
    },

    nuevoUsuario(name, email, rol) {
        const body = {
            name: name,
            email: email,
            rol: rol
        }
        return axios.post("usuarios", body);
    },

    buscarUsuario(id){
        return axios.get("usuarios/"+id);
    },

    editarUsuario(id, name, email, rol) {
        const body = {
            name: name,
            email: email,
            rol: rol
        }
        return axios.put("usuarios/"+id, body);
    },

    eliminarUsuario(id) {
        return axios.delete("usuarios/"+id);
    },

    checkEmail(email){
        const body = {
            email: email
        }
        return axios.post('checkEmail', body);
    },

    async changeMeProfileImage(body){
        
        const response  = await  axios.post('auth/changeMeProfileImage', body);
        const { data } =  response;
        return data;
    },

}