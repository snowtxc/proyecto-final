import { appStore } from '../store/app';

export function AdminGuard(to, from, next) {
    const $appStore = appStore();
    const user = $appStore.userdata;
    if(!user){
        next('/signIn'); 
    }else{
        if (user.rol === 'Administrador') {
            next(); 
        } else {
            next('/acceso-denegado'); 
        }
    }
}
  
export function OperadorGuard(to, from, next) {
    const $appStore = appStore();
    const user = $appStore.userdata;
    if(!user){
        next('/signIn'); 
    }else{
        const userRole = user.rol;
        if (userRole === 'Operador' || userRole === 'Administrador') {
            next(); 
        } else {
            next('/acceso-denegado'); 
        }
    }
}
  
export function ObservadorGuard(to, from, next) {
    const $appStore = appStore();
    const user = $appStore.userdata;
    if(!user){
        next('/signIn'); 
    }else{
        const userRole = user.rol;
        if (userRole === 'Observador' || userRole === 'Operador' || userRole === 'Administrador') {
            next(); 
        } else {
            next('/acceso-denegado'); 
        }
    }
}
  