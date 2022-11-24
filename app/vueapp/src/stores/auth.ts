import { defineStore } from 'pinia'
import Cookies from 'js-cookie'
import api from '@/services/api'
import { useStore } from '@/stores/main'

import type { IUser } from '@/types/typings'
import type { AxiosResponse } from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null as string|null,
    currentUser: null as IUser|null,
  }),

  actions: {
    async signIn(credentials: HTMLFormElement) {
      let response = await api.post('auth/login', '', credentials)
      return this.attempt(response.data.token)
    },
    async attempt(token: string) {
      if(token) {
        this.token = token
      }
      if(!this.token) return

      try {
        let response = await api.get('auth/me')
        if(response.data.status === 200 && response.data.statusText === 'OK') {
          this.currentUser = response.data.data
        }
      } catch (err) {
        console.log({err})
        this.token = null
        this.currentUser = null
      }
    },
    async logout() {
      Cookies.remove('IPEER')
      this.token = null
      this.currentUser = null
      // @ts-ignore
      this.router().push({name: 'user.login'})
    },
    /**
     * fetch loggedIn user
     * set token
     * @param params
     */
    async fetchCurrentUser(params?: string|null) {
      const store = useStore()
      const cookies = Cookies.get('IPEER')

      return await api.get('users/editProfile', params)
        .then(response => {
          if(response.status === 200 && response.statusText === 'OK') {
            this.currentUser = response.data.data
            // NOTE:: test for dev. and will be replaced with jwt_token??
            // @ts-ignore
            this.token = cookies
          } else {
            this.token = null
            this.currentUser = null
            // @ts-ignore
            store.setNotification(response.data)
          }
          return response
        }).catch((err: any) => {
          this.token = null
          this.currentUser = null
          // @ts-ignore
          store.setNotification({ message: 'something went wrong. please try again', status: 500, type: 'error' })
          return err
        }) as AxiosResponse<any>
    },
    /**
     * update user profile
     * @param params
     * @param payload
     */
    async updateCurrentUser(params: string|any, payload: HTMLFormElement): Promise<void> {
      const store = useStore()
      try {
        const response = await api.post('users/editProfile', params, payload)
        if(response.status === 200 && response.statusText === 'OK') {
          await this.fetchCurrentUser(params)
          await store.setNotification(response.data)
        }
        else {
          await store.setNotification(response.data)
        }
      } catch (err: any) {
        // await store.setNotification({message: err.response.message, status: err.response.status, type: 'error'})
        await store.setNotification(err.response.data)
      } finally {
        store.setLoading(false)
      }
    }
  },

  getters: {
    authenticated(state) {
      return !!(state.token && state.currentUser)
    },
    getCurrentUser(state) {
      return state.currentUser
    },
    getToken(state) {
      return (state.token && state.currentUser)
    },
  }
})
