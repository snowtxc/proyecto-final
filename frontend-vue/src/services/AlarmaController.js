import axios from "./axios";

export default{
    listaAlarmas(page, procesoId, componenteId, fechaIni, fechaFin) {
        let  url = "alarmas?page="+page;
        if(procesoId){
            url+="&proceso_id="+procesoId;
        }
        if(componenteId){
            url+="&componente_id="+componenteId;
        }
        if(fechaIni){
            url+="&fechaInicio="+fechaIni;
        }
        if(fechaFin){
            url+="&fechaFin="+fechaFin;
        }
        return axios.get(url);
    },

    getUsers(idAlarma){
        return axios.get("alarmas/"+idAlarma+"/usuarios");
    }

}