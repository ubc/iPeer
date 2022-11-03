<script lang="ts" setup>
import {toRefs, onBeforeMount, ref} from 'vue'
import type { Ref } from 'vue'
import useFetch from '@/composables/useFetch'
import useNotification from '@/components/notification/useNotification'
import type {IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:profile', option: any): void
}>()
const props = defineProps<{
  pageTitle?:string
}>()
// DATA
const initialized = ref(false);
const currentUser = ref({})
const { notifications, setNotification, getNotifications } = useNotification()

// COMPUTED
// METHODS
async function getUserProfile(): Promise<void> {
  setNotification({})
  try {
    currentUser.value = await useFetch('/users/editProfile', {method: 'GET', timeout: 100})
    // TODO:: add 'me' action to users_controller.php to handle the endpoint instead of 'editProfile'
    // currentUser.value = await useFetch('/users/me', {method: 'GET', timeout: 100})
    initialized.value = true
  } catch (err: Error | any) {
    initialized.value = false
    setNotification({message: err.message, type: 'error'});
  }
}
// WATCH
// LIFECYCLE
onBeforeMount(async () => await getUserProfile())
if (import.meta.env.MODE !== 'production') {
  console.log('[mode]', import.meta.env.MODE, import.meta.env.VITE_DEV_URL)
}
</script>

<script lang="ts">
export default{
  inheritAttrs:false
}
</script>
<template>
  <template v-if="initialized">
    <router-view name="banner" v-slot="{ Component, pageTitle }" >
      <component :is="Component" />
    </router-view>
    <router-view name="navigation" v-slot="{ Component, pageTitle }" >
      <component :is="Component" :current-user="currentUser" :page-title="pageTitle" />
    </router-view>
    <router-view class="content" v-slot="{ Component, pageTitle }">
      <component :is="Component" :current-user="currentUser" :page-title="pageTitle" @update:profile="$emit('update:profile')" />
    </router-view>
  </template>
  <router-view name="footer" v-slot="{ Component }">
    <component :is="Component"/>
  </router-view>
  <router-view name="notification" v-slot="{ Component }">
    <component :is="Component" :notifications="notifications" :key="notifications" />
  </router-view>
</template>
