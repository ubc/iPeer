<script lang="ts" setup>
import { onMounted } from 'vue'
import PageHeading from '@/components/PageHeading.vue'
import Loader from '@/components/Loader.vue'
import type {IUser} from "@/types/typings";

// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  settings: object
  currentUser: IUser
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
onMounted(() =>  {
  window.location.href = 'http://localhost:8080/login'
})
</script>

<template>
  <div class="login">
    <PageHeading :settings="{ title: props.settings.title }" />
    <Loader />
    <div class="redirect w-full flex justify-center items-center text-gray-300">redirect to main login page.</div>
  </div>
</template>
