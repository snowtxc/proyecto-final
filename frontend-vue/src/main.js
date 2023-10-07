import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './assets/scss/global.scss'
import './index.css'
import BaseCard from './components/Base/BaseCard.vue'
import BaseBtn from './components/Base/BaseBtn.vue'
import { createPinia } from 'pinia'

import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'
import VueApexCharts from "vue3-apexcharts";

import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'

import Notifications from '@kyvg/vue3-notification'

import { library } from '@fortawesome/fontawesome-svg-core'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { faPenToSquare, faRectangleXmark, faTrashCan, faSquarePlus } from '@fortawesome/free-regular-svg-icons'

library.add(faTrashCan, faPenToSquare, faRectangleXmark, faSquarePlus)
const pinia = createPinia();

pinia.use(piniaPluginPersistedstate);


 
createApp(App)
    .component('font-awesome-icon', FontAwesomeIcon)
    .component('BaseCard', BaseCard)
    .component('BaseBtn', BaseBtn)
    .use(PerfectScrollbar)
    .use(VueApexCharts)
    .use(store)
    .use(router)
    .use(pinia)
    .use(Notifications)
    .mount('#app')