import { createApp } from 'vue'
import { configure } from 'vee-validate'
import router from './router'
import api from '@/services/api'
import App from './App.vue'
import './assets/sass/main.scss'

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

const web = createApp(App)
web.use(router)
router.isReady().then(() => {
  web.mount('#webapp')
})
