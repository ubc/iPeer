import axios from 'axios'
import Cookies from 'js-cookie'

export default {
  init() {
    axios.defaults.baseURL = import.meta.env.VITE_APP_ENV === 'development' ? import.meta.env.VITE_BASE_URL : import.meta.env.VITE_PROD_URL
    axios.defaults.headers.common['Accept'] = 'application/json'
    axios.defaults.headers.get['Content-Type'] = 'application/json'
    axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=UTF-8'
    axios.defaults.headers.common['X-CSRF-Token'] = Cookies.get('IPEER')
  },
  get(resource: string, params?: string|null) {
    return axios.get(`${resource}/${params}`)
  },
  // @ts-ignore
  post(resource: string, params?: string|null, data: HTMLFormElement, config?: {}) {
    return axios.post(`${resource}/${params}`, data, config)
  }
}
