import axios from "./axios";

export default{
    async create(body) {
       const response  = await axios.post("componentes",body);
       const { data } = response;
       return data;
    },

    async edit(id, body) {
        const response  = await axios.post("componentes/"+id,body);
        const { data } = response;
        return data;
    },


    async list(page, maxRows,filters){
        let  url = "componentes?page="+page+"&maxRows="+maxRows;
        if(filters.search){
            url+="&nombre="+filters.search;
        }
        if(filters.tipo){
            url+="&tipo="+filters.tipo;
        }
        const response  = await axios.get(url);
        const { data } = response;
        return data;
    },

    async getById(id){
        const response  = await axios.get("componentes/"+id);
        const { data } = response;
        return data;
    },

    async delete(id){
        const response  = await axios.delete("componentes/"+id);
        const { data } = response;
        return data;      
    },


    async addImage(componentId,  body){
        const response  = await axios.post(`componentes/${componentId}/imagenes`, body);
        const { data } = response;
        return data;
    },

    async deleteImage(componentId, imageId){
        const response  = await axios.delete(`componentes/${componentId}/imagenes/${imageId}`);
        const { data } = response;
        return data;      
    },

    async listDispositivosSinNodo(id){
        const response  = await axios.get("componentes-sin-nodo/");
        const { data } = response;
        return data;      
    },

    async  marcaLast24Hours(id){
        const response  = await axios.get("componentes/"+id+"/marcaLast24Hours");
        const { data } = response;
        return data;    

    },

    async toggleOn(id, on){
        const response  = await axios.post("componentes/"+id+"/toggleOn", {
            on
        });
        const { data } = response;
        return data;    
    }
} 
