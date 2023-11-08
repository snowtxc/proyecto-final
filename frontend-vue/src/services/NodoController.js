import axios from "./axios";

export default{
    async create(body) {
       const response  = await axios.post("nodos",body);
       const { data } = response;
       return data;
    },

    async list(id) {
        const response  = await axios.get("nodos/" + id);
        const { data } = response;
        return data;
     },

     async getById(id) {
      const response  = await axios.get("nodos/nodo/" + id);
      const { data } = response;
      return data;
   },

    async delete(id) {
        const response  = await axios.delete("nodos/" + id);
        const { data } = response;
        return data;
     },

     async updatePosition(id, position) {
      const response = await axios.put(`/nodos/${id}/updatePosition`, {NewPosition: position})
      const { data } = response;
      return data;
     }

} 