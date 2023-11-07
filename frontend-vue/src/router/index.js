import { createRouter, createWebHistory } from 'vue-router'
import NotFound from '../views/NotFound.vue'
import store from '../store'
import SignIn from '../views/sessions/SignIn.vue'
import SignUp from '../views/sessions/SignUp.vue';
import ResetPassword from '../views/sessions/ResetPassword.vue'
import ForgotPassword from '../views/sessions/ForgotPassword.vue'
import { AdminGuard, OperadorGuard, ObservadorGuard } from './guards';
import AccesoDenegado from '../views/AccesoDenegado.vue'


const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../layout/index.vue'),
        redirect: '/diagrama',
        meta: {
            title: 'Home',
        },
        children: [
            {
                path: '/dashboards',
                name: 'Dashboards',
                component: () => import('../views/dashboards/index.vue'),
                meta: {
                    title: 'Dashboard',
                },
                children: [
                    {
                        path: 'dashboard-version-one',
                        name: 'dashboard-version-one',
                        component: () =>
                            import('../views/dashboards/Dashboards.v1.vue'),
                        beforeEnter: OperadorGuard
                    },
                ],
            },

            {
                path: '/dispositivos',
                name: 'dispositivos',
                component: () => import('../views/dispositivos/IndexPage.vue'),
                meta: {
                    title: 'Dispositivos',
                },
                beforeEnter: OperadorGuard 
    
            },

            {
                path: '/dispositivos/nuevo',
                name: 'nuevoDispositivo',
                component: () => import('../views/dispositivos/DevicePage.vue'),
                beforeEnter: OperadorGuard 
            },

            
            {
                path: '/dispositivos/:id/editar',
                name: 'editarDispositivo',
                component: () => import('../views/dispositivos/DevicePage.vue'),
                beforeEnter: OperadorGuard 
            },

            {
                path: '/dispositivos/:id/historicos',
                name: 'VerHistoricos',
                component: () => import('../views/historicos/VerHistorico.vue'),
                beforeEnter: OperadorGuard 
            },

            
            {
                path: '/tipos-componentes',
                name: 'TipoComponentes',
                component: () => import('../views/tipos-componentes/IndexPage.vue'),
                beforeEnter: AdminGuard 
            },



            {
                path: '/profile',
                name: 'profile',
                component: () => import('../views/profile/ProfileTwo.vue'),
                beforeEnter: ObservadorGuard 
            },

            { 
                path: '/usuarios', 
                name: 'ListaUsuarios',
                component: ()=> import("../views/usuarios/ListadoUsuarios.vue"),
                beforeEnter: AdminGuard
            },

            { 
                path: '/procesos', 
                name: 'ListaProcesos',
                component: ()=> import("../views/procesos/ListadoProcesos.vue"),
                beforeEnter: OperadorGuard 
            },

            { 
                path: '/diagrama', 
                name: 'Diagrama',
                component: ()=> import("../views/diagrama/Diagrama.vue"),
                beforeEnter: ObservadorGuard
            },

            { 
                path: '/etapas/:procesoId/:etapaId', 
                name: 'editarEtapa',
                component: ()=> import("../views/etapas/etapa.vue"),
                beforeEnter: OperadorGuard 
            },

            { 
                path: '/alarmas', 
                name: 'ListaAlarmas',
                component: ()=> import("../views/alarmas/ListadoAlarmas.vue"),
                beforeEnter: OperadorGuard 
            },
        ],
    },

    {
        path: '/reset-password/:token',
        name: 'reset-password',
        component: ResetPassword
    },

    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword
    },

    { path: '/signIn', name: 'SignIn', component: SignIn },
    { path: '/signUp', component: SignUp },

    { path: '/:path(.*)', component: NotFound },

    { path: '/acceso-denegado', component: AccesoDenegado },

]

const router = createRouter({
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { left: 0, top: 0 }
    },
    routes,
})

router.afterEach(() => {
    if (window.innerWidth <= 1200) {
        const sidenav =
            store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen

        store.commit('largeSidebar/toggleSidebarProperties')
    }
})

export default router;
