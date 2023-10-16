import axios from "./axios";

export default{
    async create(body) {
       const response  = await axios.post("links",body);
       const { data } = response;
       return data;
    },

    async list(id) {
        const response  = await axios.get("links/" + id);
        const { data } = response;
        return data;
     },

     async delete(id) {
        const response  = await axios.delete("links/"+ id);
        const { data } = response;
        return data;
     },
} 