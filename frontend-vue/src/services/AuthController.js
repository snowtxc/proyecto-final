import axios from 'axios';

const instance = axios.create({
            baseURL: 'http://localhost:8000/api/',
            timeout: 10000
          });

class AuthController {
    
    resetPassword(token, password){
        const body = {
            token: token,
            password: password
        }
        
        return instance.post('setPassword', body);
    }

    forgotPassword(email){
        const body = {
            email: email
        }
        
        return instance.post('forgotPassword', body);
    }


}

export default new AuthController();