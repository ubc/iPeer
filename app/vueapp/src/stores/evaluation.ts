import { defineStore } from 'pinia'
import { findIndex } from 'lodash'
import { useStore } from '@/stores/main'
import { useAuthStore } from '@/stores/auth'
import { useEventsStore } from '@/stores/events'
import api from '@/services/api'
import {sleep} from "@/helpers";

import type { IUser, IEvaluation } from '@/types/typings'
export const useEvaluationStore = defineStore('evaluation', {
  state: () => ({
    loading: false,
    error: null as object|null,
    evaluation: null as IEvaluation|null,
    members: null as IUser[]|null,
    autosave: false,
  }),

  actions: {
    async fetchEvaluation(eventId: string, groupId: string): Promise<void> {
      const store = useStore()
      this.loading = true
      this.error = null
      this.evaluation = null
      this.members = null

      try {
        const response = await api.get('/evaluations/makeEvaluation', `${eventId}/${groupId}`)
        switch (response.status) {
          case 200:
            if(response.statusText === 'OK') {
              this.evaluation = await response?.data?.data
              this.members = await response?.data?.data?.members
            } else {
              this.error = response.data
            }
            break
          case 204:
            this.error = {
              message: 'The following content is not available.',
              status: response.data.status,
              type: 'error'
            }
            break
          default:
            break
        }
      } catch (err: any) {
        switch (err.response.status) {
          case 500:
            this.error = {message: err.response.data.message, status: err.response.status, type: 'error'}
            break
          case 404:
            this.error = {message: err.message, status: err.status, type: 'error'}
            // await this.router.push({ name: 'not.found' })
            break
          case 201:
            this.error = {message: err.message.data.message, status: err.response.status, type: 'error'}
            break
          default:
            break
        }
      } finally {
        await sleep(1000)
        this.loading = false
      }
    },

    async makeEvaluation(formData: HTMLFormElement, eventId: string, groupId: string) {
      const store = useStore()
      const eventsStore = useEventsStore()
      try {
        const response = await api.post('/evaluations/makeEvaluation', `${eventId}/${groupId}`, formData)
        if(response.status === 200 && response.statusText === 'OK') {
          store.setNotification(response.data)
          eventsStore.fetchEvents()
          // @ts-ignore
          await this.router.push({name: 'student.events'})
        } else {
          store.setNotification(response.data)
        }
      } catch (err: any) {
        store.setNotification({ message: err.response.data.message, status: err.response.data.status, type: 'error' })
      }
    },

    async editEvaluation(formData: HTMLFormElement, eventId: string, groupId: string) {
      this.autosave = true
      const store = useStore()
      //const eventsStore = useEventsStore()

      try {
        const response = await api.post('/evaluations/makeEvaluation', `${eventId}/${groupId}`, formData)
        if(response.status === 200 && response.statusText === 'OK') {
          store.setNotification(response.data)
          //eventsStore.fetchEvents()
        } else {
          store.setNotification(response.data)
        }
      } catch (err: any) {
        store.setNotification({ message: err.response.data.message, status: err.response.data.status, type: 'error' })
      }
    },

    async autoSaveEvaluation(formData: HTMLFormElement|any, eventId: string, groupId: string) {
      const store = useStore()
      this.autosave = true
      try {
        const response = await api.post('/evaluations/makeEvaluation', `${eventId}/${groupId}`, formData)
        if(response.status === 200 && response.statusText === 'OK') {
          this.autosave = false
        }
      } catch (err: any) {
        store.setNotification({message: err.response.message, status: err.response.status, type: 'error'})
      } finally {
        this.autosave = false
      }
    },


  },

  getters: {
    hasError(state) {
      if(state.error === null) {
        return false
      } else {
        return true
      }
    },
    getGroupMembers(state) {
      // check if members includes the currentUser
      if(state.members) {
        const auth = useAuthStore()
        const user = auth.getCurrentUser
        let tmp = [...state.members]
        // @ts-ignore
        const index = findIndex(tmp, { id: user?.id })
        if (index === -1) {
          return state.members
        } else {
          const spliced = tmp.splice(index, 1)
          // @ts-ignore
          return [...tmp, {...spliced[0], first_name: 'Yourself', last_name: ''}]
        }
      }
      return state.members
    }
  }

})
