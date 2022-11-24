import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import { configure } from 'vee-validate'
import router from './router'
import App from './App.vue'
import './assets/sass/main.scss'

import api from '@/services/api'
api.init()

configure({
  generateMessage: context => {
    return `The _field ${context.field} is invalid`;
  },
  validateOnBlur: true,
  validateOnChange: true,
  validateOnInput: true,
  validateOnModelUpdate: true
})
const pinia = createPinia()
pinia.use(({ store }) => {
  store.router = markRaw(router)
})
const web = createApp(App)

web.use(pinia)
web.use(router)

router.isReady().then(() => {
  web.mount('#webapp')
})
