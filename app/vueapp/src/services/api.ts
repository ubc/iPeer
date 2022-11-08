import axios from 'axios'
import Cookies from 'js-cookie'
import { useRouter } from 'vue-router'

const cookies = Cookies.get('IPEER')
// if(!cookies) {
//   api.logout()
// }

export default {
  init() {
    axios.defaults.baseURL = import.meta.env.VITE_APP_ENV === 'development' ? import.meta.env.VITE_BASE_URL : import.meta.env.VITE_PROD_URL
    axios.defaults.headers.common['Accept'] = 'application/json'
    axios.defaults.headers.get['Content-Type'] = 'application/json'
    axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
    axios.defaults.headers.common['X-CSRF-Token'] = cookies
  },
  get(resource: string, params?: string|null) {
    return axios.get(`${resource}/${params}`)
  },
  // @ts-ignore
  post(resource: string, params?: string|null, data: HTMLFormElement, config?: {withCredentials: true}) {
    return axios.post(`${resource}/${params}`, data, config)
  },
  // logout
  logout() {
    Cookies.remove('IPEER')
    useRouter().push({name: 'user.login'}).then(r => {
      console.log({r})
    })
  }
}
