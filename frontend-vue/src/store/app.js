import { defineStore } from 'pinia'
import axios from 'axios';
import { API_BASE_URL, LOGIN_ENDPOINT } from '@/config/api-config';

export const appStore = defineStore('appStore', {   
    state: () => ({ userdata: null , token: null , globalLoading: false , imageProfileDefault: '/src/assets/images/user.png' , alarmsNotifications: []}),   
    getters: {     
        getUserData: (state) => state.userdata,
        getToken: (state) => state.token,   
        getGlobalLoading: (state) => state.globalLoading,
        getImageProfileDefault: (state) => state.imageProfileDefault,
        getAlarmsNotifications: (state) => state.alarmsNotifications
    },   
    actions: {     
        async login(email, password) {
            const url = `${API_BASE_URL}${LOGIN_ENDPOINT}`;
            const response = await axios.post(url, { email, password });
            const data = response.data;
            this.userdata = data.user;
            this.token = data.token;
            return data;
              
        },    
        
        logout(){
            this.userdata = null;
            this.token = null;
        },

        setGlobalLoading(value) {   
            this.globalLoading = value
        },

        setProfileImage(pathUrl){
            this.userdata =  {
                ...this.userdata,
                profileImage: pathUrl
            }
        },
      

        setAlarmsNotifications(alarmas){
            this.alarmsNotifications = alarmas;
        },
        
        setUserName(name){
            this.userdata = {
                ...this.userdata,
                name: name
            }
        }
    }, 

    persist: true
})