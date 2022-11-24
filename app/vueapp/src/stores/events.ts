import { defineStore } from 'pinia'
import api from '@/services/api'
import { useStore } from '@/stores/main'
import type { StoreDefinition } from 'pinia'

import type {INotification, IEvent, IUser} from '@/types/typings'
export const useEventsStore = defineStore('events', {
  state: () => ({
    loading: false,
    error: null as INotification|null,
    events: {
      current:[] as IEvent[],
      completed: [] as IEvent[]
    } as object|any
  }),

  actions: {
    async fetchEvents(params?: string): Promise<void> {
      // @ts-ignore
      const store = new useStore()
      this.loading = true

      try {
        const response = await api.get('/home', params)
        if(response.status === 200 && response.statusText === 'OK') {
          // TBD:: fetching individually with params
          // if(params === 'current') {
          //   this.events.current = response?.data?.data
          // }
          // if(params === 'completed') {
          //   this.events.completed = response?.data?.data
          // }
          this.events = response?.data?.data
        } else {
          this.error = response.data
          await store.setNotification({
            message: response?.data?.message,
            status: response?.data?.status,
            type: response?.data?.type
          })
        }
      } catch (err: Error | any) {
        this.error = err
        await store.setNotification({
          message: err?.message,
          status: err?.status,
          type: 'error'
        })
      } finally {
        this.loading = false
      }
    }
  },

  getters: {
    getCurrentEvents(state) {
      return state.events?.current
    },
    getCompletedEvents(state) {
      return state.events?.completed
    },
    hasError(state) {
      return state.error !== null;
    },
  }

})
