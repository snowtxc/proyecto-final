import axios from "./axios";

export default{
     list: async()=>{
      const response  = await axios.get("unidades");
      const  { data } = response;
      return data;
    },
}