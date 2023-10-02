import axios from "./axios";

export default{
    async getAll() {
       const response  = await axios.get("tipos_componentes");
       const { data } = response;
       return data;
    }
}