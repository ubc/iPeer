import { createApp } from 'vue'
import { configure } from 'vee-validate'
import App from './App.vue'
// @ts-ignore
import router from './router'

import './assets/sass/main.scss'

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