import {ref, readonly, watch} from 'vue'
import { v4 as uuidv4 } from 'uuid'

import {cloneDeep, findIndex, isEmpty} from "lodash";

interface INotification {
  type: string
  status?: number
  title?: string
  message: string
}

export default function useNotification() {

  const notifications = ref<INotification[]>([])

  function getNotifications() {
    return notifications.value
  }

  function setNotification(notification: INotification) {
    if(!isEmpty(notification)) {
      // @ts-ignore
      notifications.value.push({id: uuidv4(), ...notification})
    }
  }

  function delNotification(notification_id: string) {
    // @ts-ignore
    const index = findIndex(notifications.value, { id: notification_id })
    notifications.value.splice(index, 1)
  }

  watch(() => cloneDeep(notifications), () => {
    getNotifications()
    // console.log(JSON.stringify(notifications.value, null, 2))
  })


  return {
    notifications: readonly(notifications), getNotifications, setNotification, delNotification
  }
}
