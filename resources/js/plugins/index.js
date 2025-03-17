import pinia from '@/stores/index.js'
import router from '@/router/index.js'
import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import InstantSearch from 'vue-instantsearch/vue3/es';

const options = {
    transition: 'Vue-Toastification__bounce',
    maxToasts: 20,
    newestOnTop: true,
    position: POSITION.TOP_RIGHT,
    timeout: 5000,
    closeOnClick: true,
    pauseOnHover: true,
}
export function registerPlugins(app) {
    app.use(router).use(InstantSearch).use(pinia).use(Toast, options)
}
