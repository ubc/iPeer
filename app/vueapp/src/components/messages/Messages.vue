<script lang="ts" setup>
import {ref, computed, shallowRef, toRef, toRefs, unref, watch} from 'vue'
import { v4 as uuidv4 } from 'uuid'
import {findIndex, isEmpty} from 'lodash'
import Toast from '@/components/messages/Toast.vue'
import {IconWarning, IconCheckmark} from '@/components/icons'
import './_messages.scss'
/**
 * What Are Flash/Toast Messages?
 * Toast messages helps to deliver simple feedback to the user.
 * They most often follow an action performed by a user
 * and provide information regarding the success or failure of that action.
 */
interface IMessage {
  id?: string
  text?: string
  status: number
  type: string
}
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  message: IMessage
}>()
// DATA
const action = shallowRef({
  title: '',
  text: '',
  type: 'toast', // TODO::toast OR flash
  duration: 3000,
  icon: {success: IconCheckmark, error: IconWarning, warning: IconWarning, info: ''},
})
const messages = ref([])
// COMPUTED
// METHODS
function deleteMessage(msgId) {
  const index = findIndex(messages.value, { id: msgId })
  messages.value.splice(index, 1)
}
// WATCH
watch(() => props.message, (current, previous) => {
  messages.value.push({id: uuidv4(), text: current.message, status: current.status, type: current.statusText})
}, { deep: true })
// LIFECYCLE
</script>

<template>
  <div v-if="messages?.length" :class="`messages`" :key="messages">
    <Toast
        v-for="(toast) of messages"
        :key="toast?.id"
        :action="action"
        :toast="toast"
        @update:messages="deleteMessage"
    />
  </div>
</template>
