import { defineStore } from 'pinia'
import { v4 as uuidv4 } from 'uuid'
import { findIndex, isEmpty } from 'lodash'
import { useAuthStore } from '@/stores/auth'
import { useEventsStore } from '@/stores/events'

import type { INotification } from '@/types/typings'
import {sleep} from "@/helpers";

export const useStore = defineStore('main', {
  state: () => ({
    mode: 'light',
    initialized: false,
    error: null,
    loading: false,
    notification: {} as INotification,
    notifications: [] as INotification[]
  }),
  // : Promise<void>
  actions: {
    async initialize() {
      this.loading = true
      const authStore = useAuthStore()
      const eventsStore = useEventsStore()

      try {
        const response = await authStore.fetchCurrentUser('')

        if(response?.status === 200 && response?.statusText === 'OK') {
          // TBD:: fetching individually with params
          // await eventsStore.fetchEvents('?work=current')
          // await eventsStore.fetchEvents('?work=completed&per_page=10')
          await eventsStore.fetchEvents() // fetch all events
          this.initialized = true
        } else {
          this.initialized = false
          this.setNotification(response.data)
        }
      } catch (err: any) {
        this.setNotification({ message: err.response.message, status: err.status, type: 'error' })
      }
    },
    /**
     * Set Loading Status
     * @param value
     */
    setLoading(value:boolean) {
      this.loading = value
    },
    /**
     * Add Notification
     * @param payload
     */
    async setNotification(payload: INotification): Promise<void> {
      // this.loading = true
      this.notification = {} as INotification
      if(!isEmpty(payload)) {
        const prepareMessage: INotification = { id: uuidv4(), ...payload }
        this.notification = prepareMessage        // always respond/flash with the latest notification
        this.notifications.push(prepareMessage)   // add notification to notifications collection
      }
      this.loading = false
    },
    /**
     * Remove Notification By Id
     * @param id
     */
    async delNotification(id: string|number): Promise<void> {
      // @ts-ignore
      const index = findIndex(this.notifications, { id: id })
      this.notifications.splice(index, 1)

      await sleep(2000)
      // @ts-ignore
      this.notification = {}
    }
  },

  getters: {
    isInitialized(state) {
      return state.initialized
    },
    isError(state) {
      return state.error
    },
    isLoading(state) {
      return state.loading
    },
    getNotifications(state) {
      return state.notifications
    },
    getNotification(state) {
      return state.notification
    }
  }
})
