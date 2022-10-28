<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from 'vue'
import useFetch from '@/composables/useFetch'
import DefaultLayout from '@/student/Layout.vue'
import Loader from '@/components/Loader.vue'

import type { User } from '@/types/typings'
// REFERENCES
const emit  = defineEmits<{}>()
const props = defineProps<{
  // pageTitle?: string
}>()
// DATA
const initialized = ref(false);
const currentUser = reactive<User>({})
// COMPUTED
// METHODS
async function getUserProfile(): void {
  try {
    const response = await useFetch('/users/editProfile', { method: 'GET', timeout: 100 })
    Object.assign(currentUser, response)
    initialized.value = true
  } catch (err) {
    error.value = {message: err, type: 'error'};
    initialized.value = false
  }
}
// WATCH
// LIFECYCLE
onMounted(async () => await getUserProfile())
if(import.meta.env.MODE !== 'production') {
  console.log('[mode]', import.meta.env.MODE, import.meta.env.VITE_BASE_URL)
}
</script>

<template>
  <!-- Student/TutorLayout -->
  <template v-if="currentUser?.role_id === '4' || currentUser?.role_id === '5'">
    <DefaultLayout v-if="initialized" :current-user="currentUser" @update:profile="getUserProfile" />
  </template>
  <!-- Admin/InstructorLayout -->
  <template v-else-if="currentUser?.role_id === '2' || currentUser?.role_id === '3'">
    <div class="admin">InstructorLayout</div>
  </template>
  <!-- SuperAdminLayout -->
  <template v-else-if="currentUser?.role_id === '1'">
    <div class="superadmin">SuperAdminLayout</div>
  </template>
  <router-view name="footer" v-slot="{ Component, pageTitle }" >
    <component :is="Component" />
  </router-view>
</template>
