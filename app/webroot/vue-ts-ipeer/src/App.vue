<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from 'vue'
import useFetch from '@/composables/useFetch'
import DefaultLayout from '@/student/Layout.vue'
import Loader from '@/components/Loader.vue'
import type { User } from '@/types/typings'
// REFERENCES
const emit  = defineEmits<{}>()
const props = defineProps<{
  // pageTitle: string
}>()
// DATA
const initialized = ref(false);
const currentUser = reactive({})
// COMPUTED
// METHODS
async function getUserProfile() {
  try {
    const response = await useFetch(`${import.meta.env.VITE_BASE_URL}/users/editProfile`, { method: 'GET', timeout: 100 })
    Object.assign(currentUser, response)
    initialized.value = true;
  } catch (err) {
    error.value = {message: err, type: 'error'};
    initialized.value = false;
  }
}
// WATCH
// LIFECYCLE
onMounted(async () => await getUserProfile())
</script>

<template>
  <!-- Student/TutorLayout -->
  <template v-if="currentUser?.role_id === '4' || currentUser?.role_id === '5'">
    <DefaultLayout v-if="initialized === true" :currentUser="currentUser" @update:profile="getUserProfile" />
  </template>
  <!-- Admin/InstructorLayout -->
  <template v-else-if="currentUser?.role_id === '2' || currentUser?.role_id === '3'" :currentUser="currentUser" @update:profile="getUserProfile">
    <div class="admin">InstructorLayout</div>
  </template>
  <!-- SuperAdminLayout -->
  <template v-else-if="currentUser?.role_id === '1'" :currentUser="currentUser" @update:profile="getUserProfile">
    <div class="admin">SuperAdminLayout</div>
  </template>
  <router-view name="footer" v-slot="{ Component, pageTitle }" >
    <component :is="Component" />
  </router-view>
</template>
