<script lang="ts" setup>
import {computed, reactive} from 'vue'
import { useStore } from '@/stores/main'
import Notification from '@/components/messages/Notification.vue'
import './_messages.scss'
import type { INotification } from '@/types/typings'
// REFERENCES
const emit    = defineEmits<{}>()
const props   = defineProps<{}>()
const store   = useStore()
// DATA
const state = reactive({
  statusBar: false
})
// COMPUTED
const notifications = computed(() => store.getNotifications) as INotification[]
// METHODS
function closeNotification(notification_id: string) {
  store.delNotification(notification_id)
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div v-if="notifications?.length" class="notifications">
    <Notification
        :status-bar="state.statusBar"
        v-for="notification of notifications" :key="notification?.id"
        :notification="notification"
        @close:notification="closeNotification"
    />
  </div>
</template>
