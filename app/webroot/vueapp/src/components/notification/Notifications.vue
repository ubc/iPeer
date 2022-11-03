<script lang="ts" setup>
import {shallowRef} from 'vue';
import {IconWarning} from "@/components/icons";
import Notification from "@/components/notification/Notification.vue";
import './_notifications.scss'

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  notifications: object[]
}>()
// DATA
const notification_action = shallowRef({
  default_icon: IconWarning,
  default_title: 'Main Notification',
  default_popup: 'popup/Main.vue',
})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="notifications" v-if="notifications?.length>0">
    <Notification
        v-for="notification of notifications"
        :key="notification?.id"
        :action="notification_action"
        :notification="notification"
    />
  </div>
</template>
