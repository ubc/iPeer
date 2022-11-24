import { defineStore, acceptHMRUpdate } from 'pinia'
import type { StoreDefinition } from 'pinia'
import {findIndex} from "lodash";
import {useStore} from "@/stores/main";
import {useAuthStore} from "@/stores/auth";
import api from "@/services/api";
import {sleep} from "@/helpers";

import type { IReview, IUser } from '@/types/typings'

export const useReviewsStore = defineStore('reviews', {
  state: () => ({
    loading: false,
    error: null as object|null,
    reviews: null as IReview|null,
    members: null as IUser[]|null
  }),

  actions: {
    async fetchReviews(eventId: string, groupId: string): Promise<void> {
      // TBD:: Check if reviews with event.id/group.id exists
      const store = useStore()
      this.loading = true
      this.error = null
      this.reviews = null
      this.members = null

      try {
        const response = await api.get('/evaluations/studentViewEvaluationResult', `${eventId}/${groupId}`)
        switch (response.status) {
          case 200:
            if(response.statusText === 'OK') {
              this.reviews = await response?.data?.data
              this.members = await response?.data?.data?.members
            } else {
              this.error = { message: response.data.message, status: response.status, type: 'error'}
            }
            break
          case 202:
            this.error = response.data
            /**
            this.error = {
              title: 'No Content',
              message: 'The following content is not available.',
              status: response.status,
              type: 'error'
            }*/
            break
          default:
            break
        }
      }
      catch (err: any) {
        if(err.response.status === 500) {
          // Internal Server Error
          this.error = {
            status: err.response.status,
            text: err.response.data.message,
            code: err.code,
            message: err.message,
            type: 'error'
          }
        }
        else if(err.response.status === 404) {
          // Not Found
          this.error = {
            status: err.status,
            message: err.message,
            type: 'error'
          }
          // @ts-ignore
          // await this.router.push({ name: 'not.found' })
        }
        else if(err.response.status === 204) {
          // No Content
          this.error = {
            status: err.response.status,
            text: err.response.data.message,
            code: err.code,
            message: err.message,
            type: 'error'
          }
        }
        // this.error = {status: err.status, code: err.code, message: err.message, type: 'error'}
        // store.setNotification({ text: err.data.response.message, status: err.data.response.status, type: err.data.response.statusText })
      }
      finally {
        await sleep(1000)
        this.loading = false
      }
    }
  },

  getters: {
    hasError(state) {
      if(state.error === null) {
        return false
      } else {
        return true
      }
    },
    getGroupMembers(state) { // check if members includes the currentUser
      // @ts-ignore
      if(state.members?.length>0) {
        const auth = useAuthStore()
        const user = auth.getCurrentUser
        // @ts-ignore
        let tmp = [...state.members]
        // @ts-ignore
        const index = findIndex(tmp, { id: user?.id })
        if (index !== -1) {
          const spliced = tmp.splice(index, 1)
          // @ts-ignore
          return [...tmp, {...spliced[0], first_name: 'Yourself', last_name: ''}]
        } else {
          return state.members
        }
      } else {
        return state.members
      }
    },
  }

})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useReviewsStore, import.meta.hot))
}
