<script lang="ts" setup>
import { onBeforeMount, ref } from 'vue'
import type { Ref } from 'vue'
import api from '@/services/api'
import type {IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  pageTitle?: string
}>()
// DATA
const initialized   = ref(false);
const currentUser   = ref<IUser|null>(null)
const message       = ref([])
const loading       = ref(false)
// COMPUTED
// METHODS
async function getUserProfile(): Promise<void> {
  loading.value = true
  try {
    let response = await api.get('/users/editProfile', '', {},  {})
    if(response.status === 200 && response.statusText === 'OK') {
      currentUser.value = response.data
      initialized.value = true
    }
  } catch (err) {
    message.value?.push({text: err.response.message, status: err.response.status, type: 'error'})
  } finally {
    loading.value = false
  }
}

function updateProfile(option) {
  currentUser.value = option
}
// async function setMessage(event) {
//   // message.value?.push({text: event.message, status: event.status, type: event.statusText})
//   message.value = {text: event.message, status: event.status, type: event.statusText}
// }
// WATCH
// LIFECYCLE
onBeforeMount(async () => await getUserProfile())
if (import.meta.env.MODE !== 'production') {
  console.log('[mode]', import.meta.env.MODE, import.meta.env.VITE_DEV_URL)
}
</script>

<script lang="ts">
export default{inheritAttrs:false}
</script>
<template>
  <router-view name="banner" v-slot="{ Component, pageTitle }" >
    <component :is="Component" />
  </router-view>

  <router-view name="navigation" v-slot="{ Component, pageTitle }" >
    <component :is="Component" :current-user="currentUser" :page-title="pageTitle" />
  </router-view>

  <router-view class="content" v-slot="{ Component, pageTitle }">
    <component
        :is="Component"
        :current-user="currentUser"
        :page-title="pageTitle"
        @set:message="message=$event"
        @update:profile="updateProfile"
    />
  </router-view>

  <router-view name="toast" v-slot="{ Component }">
    <component :is="Component" :message="message" />
  </router-view>

  <router-view name="footer" v-slot="{ Component }">
    <component :is="Component"/>
  </router-view>
</template>
