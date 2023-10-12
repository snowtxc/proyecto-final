import axios from "./axios";

export default{
   async create( componenteId,body){
    const response  = await axios.post("/componentes/"+componenteId+"/partes",body);
    const { data } = response;
    return data;
   },

   async edit(componenteId, parteId, body){
      const response  = await axios.put("/componentes/"+componenteId+"/partes/"+parteId,body);
      const { data } = response;
      return data;
   },

   async list(componenteId){
        const response  = await axios.get("componentes/"+componenteId+"/partes");
        const {data } = response;
        return data;
   },

   async delete(componenteId,parteId){
      const response  = await axios.delete("componentes/"+componenteId+"/partes/"+parteId);
      const {data } = response;
      return data;
   },

   async getNotas(componenteId, parteId,page){
      const url = "componentes/"+componenteId+"/partes/"+parteId+"/notas?page="+page;
      const response = await axios.get(url);
      const {data} = response;
      return data;
   },

   async addNota(componenteId, parteId, body){
      const response = await axios.post("componentes/"+componenteId+"/partes/"+parteId+"/notas",body);
      const {data} = response;
      return data;
   }
}