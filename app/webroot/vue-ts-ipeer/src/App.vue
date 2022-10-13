<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from 'vue'
import useFetch from '@/composables/useFetch'
import Layout from '@/student/Layout.vue'
import Loader from '@/components/Loader.vue'
import type { User } from '@/types/typings'
// REFERENCES
const emit  = defineEmits<{}>()
const props = defineProps<{
  // pageTitle: string
}>()
// DATA
const initialized = ref(false);
const user        = reactive({})
// COMPUTED
// METHODS
async function getUserProfile() {
  try {
    const response = await useFetch(`${import.meta.env.VITE_BASE_URL}/users/editProfile`, { method: 'GET', timeout: 100 })
    Object.assign(user, response)
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
  <Layout v-if="initialized === true" :user="user" @update:profile="getUserProfile" />
</template>
