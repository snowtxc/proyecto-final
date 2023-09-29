import { createRouter, createWebHistory } from 'vue-router'
import NotFound from '../views/NotFound.vue'
import store from '../store'
import SignIn from '../views/sessions/SignIn.vue'
import SignUp from '../views/sessions/SignUp.vue';


const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../layout/index.vue'),
        redirect: '/dashboards/dashboard-version-one',
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
                    },
                ],
            },
            {
                path: '/components',
                name: 'components',
                component: () => import('../views/components/index.vue'),
                meta: {
                    title: 'Components',
                },
                children: [
                    {
                        path: 'button',
                        name: 'button',
                        component: () =>
                            import('../views/components/Button.vue'),
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
    
            },

            {
                path: '/profile',
                name: 'profile',
                component: () => import('../views/profile/ProfileTwo.vue'),
                
            },

            { 
                path: '/usuarios', 
                name: 'ListaUsuarios',
                component: ()=> import("../views/usuarios/ListadoUsuarios.vue"),
            },

            { 
                path: '/procesos', 
                name: 'ListaProcesos',
                component: ()=> import("../views/procesos/ListadoProcesos.vue"),
            }
        ],
    },

    { path: '/signIn', name: 'SignIn', component: SignIn },
    { path: '/signUp', component: SignUp },

    { path: '/:path(.*)', component: NotFound },


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
