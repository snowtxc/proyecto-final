import axios from 'axios';

class PasswordController {
    resetPassword(token, password){
        const body = {
            token: token,
            password: password
        }
        const instance = axios.create({
            baseURL: 'http://localhost:8000/api/',
            timeout: 5000
          });
        return instance.post('setPassword', body);
    }

}

export default new PasswordController();