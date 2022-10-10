<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import config from 'envConfig'
import axios from "axios";
import useLoader from '@/composables/useLoader'
import useNotifications from '@/composables/useNotifications'

import EventsIndex from "@/pages/EventsIndex.vue";

// REFERENCES
const URL = 'http://localhost:8080/users/editProfile/7'
interface Props {

}
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()
const { loading, setLoading } = useLoader()
const { setNotification } = useNotifications()

// DATA
const current_user  = reactive({})

// COMPUTED

// METHODS
async function getUserProfile() {
  try {
    await setLoading('PENDING')
    let response = await axios({
      method: 'GET',
      url: URL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    Object.assign(current_user, response.data)
  } catch (err: any) {
    await setNotification(err.message, 'error')
    await setLoading('ERROR')
  } finally {
    await setLoading('READY')
  }
}

// WATCH
console.log(config.log.msg)

// LIFECYCLE
onMounted(() => getUserProfile())

</script>

<template>
  <div class="">
    <router-link :to="{ name: 'dashboard' }">Dashboard</router-link>
    <router-link :to="{ name: 'user.profile' }">Profile</router-link>
    <router-view :current-user="current_user" @get:user-profile="getUserProfile"></router-view>
  </div>
</template>
