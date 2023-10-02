import axios from 'axios';
import { appStore } from '../store/app';



import router from "../router/index";


const $appStore =  appStore();

const instance = axios.create({
  baseURL: 'http://localhost:8000/api/',
  timeout: 5000
});

// Agrega un interceptor para configurar el encabezado en cada solicitud
instance.interceptors.request.use((config) => {
  const token = $appStore.getToken; // Recupera el token desde donde lo tengas almacenado

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});


instance.interceptors.response.use(
  response=>{
    return response;
  },
  error => {
      const { response  } = error;
      const { status } = response;
      if(status == 401){
        router.push("/signIn");
        return;
      }
      return response;
  }
);

export default instance;