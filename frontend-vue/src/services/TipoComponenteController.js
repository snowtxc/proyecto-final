import axios from "./axios";

export default{
    async getAll(search) {
       let url = `tipos_componentes`;
       if(search && search.trim().length > 0){
        url = url+`?search=${search}`
       }
       const response  = await axios.get(url);
       const { data } = response;
       return data;
    },

    async create(body){
        const response  = await axios.post("tipos_componentes",body);
        const { data } = response;
        return data;
    },

    async delete(id){
        const response  = await axios.delete("tipos_componentes/"+id);
        const { data } = response;
        return data;

    }
}