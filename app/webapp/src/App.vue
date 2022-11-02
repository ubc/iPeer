<script lang="ts" setup>
import {onBeforeMount, ref} from 'vue'
import useFetch from '@/composables/useFetch'
import type {IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:profile', option: any): void
}>()
const props = defineProps<{
  pageTitle:string
}>()
// DATA
const initialized = ref(false);
const notification = ref({})
const currentUser = ref<IUser|object>({})
// COMPUTED
// METHODS
function setNotification(note) {
  notification.value = note
}
async function getUserProfile(): Promise<void> {
  notification.value = {}
  try {
    currentUser.value = await useFetch('/users/editProfile', {method: 'GET', timeout: 100})
    // currentUser.value = await useFetch('/users/me', {method: 'GET', timeout: 100})
    setNotification({message: 'err.message', type: 'error'});
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
  console.log('[mode]', import.meta.env.MODE, import.meta.env.VITE_BASE_URL)
  if(notification.value) {
    console.log(notification.value?.message)
  }
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
    <component :is="Component" :notification="notification" />
  </router-view>
</template>
