import { defineStore } from 'pinia'
import axios from 'axios';
import { API_BASE_URL, LOGIN_ENDPOINT } from '@/config/api-config';

export const appStore = defineStore('appStore', {   
    state: () => ({ userdata:"lasdlf" , token:"lalala" }),   
    getters: {     
        getUserData: (state) => state.userdata,
        getToken: (state) => state.token,      
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
    }, 
})