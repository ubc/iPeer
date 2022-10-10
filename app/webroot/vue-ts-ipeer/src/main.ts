import { createApp } from 'vue'
import App from './App.vue'
// @ts-ignore
import router from './router'

import './assets/sass/main.scss'

const web = createApp(App)
web.use(router)
router.isReady().then(() => {
  web.mount('#webapp')
})