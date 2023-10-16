import dayjs from "dayjs";
import axios from "./axios";

export default{
    async list(componenteId, page, maxRows,filters) {
        let url = `componentes/${componenteId}/registros?page=${page}&maxRows=${maxRows}`;
        
        const { startDate , endDate } = filters;
        if(startDate){
            const fechaInicio =  dayjs(startDate).format("DD/MM/YYYY HH:mm");
            url =  url + `&fechaInicio=${fechaInicio}`;
        }
        if(endDate){
            const fechaFin =  dayjs(endDate).format("DD/MM/YYYY HH:mm");
            url = url + `&fechaFin=${fechaFin}`;
        }
        const response = await axios.get(url);
        const { data } = response;
        return data;
    },

    async exportExcel(componenteId,filters){
        let url = `/componentes/${componenteId}/registros/exportExcel?`;
        const { startDate , endDate } = filters;
        if(startDate){
            const fechaInicio =  dayjs(startDate).format("DD/MM/YYYY HH:mm");
            url =  url + `&fechaInicio=${fechaInicio}`;
        }
        if(endDate){
            const fechaFin =  dayjs(endDate).format("DD/MM/YYYY HH:mm");
            url = url + `&fechaFin=${fechaFin}`;
        }
      const response = await axios.get(url, { responseType: 'blob' })
      const { data } = response;
      return data;
    }
}