import axios from "./axios";

export default{
    listaAlarmas(page, procesoId, componenteId, fechaIni, fechaFin) {
        let  url = "alarmas?page="+page;
        if(procesoId){
            url+="&proceso_id="+procesoId;
        }
        if(componenteId){
            url+="&proceso_id="+componenteId;
        }
        if(fechaIni){
            url+="&proceso_id="+fechaIni;
        }
        if(fechaFin){
            url+="&proceso_id="+fechaFin;
        }
        return axios.get(url);
    },

    getUsers(idAlarma){
        return axios.get("alarmas/"+idAlarma);
    }

}