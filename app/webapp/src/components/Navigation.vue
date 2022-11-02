<script lang="ts" setup>
import {computed, toRef} from 'vue'
import { useRoute, useRouter, useLink } from 'vue-router'
import NavigationItem from '@/components/NavigationItem.vue'

import type { IUser } from '@/types/typings'
// REFERENCES
const props = defineProps<{
  currentUser: IUser
  routeTitle?: string
}>()
// DATA
// COMPUTED
const currentRouteName  = computed(() => {
  return useRoute().name || ''
})
const currentRouteTitle = computed(() => {
  return useRoute().meta.routeTitle || ''
})
// METHODS
function toggleNavDropdown() {console.log('[Todo] Navigation Dropdown List')}
// WATCH
// LIFECYCLE
</script>

<template>
  <nav class="navigation flex justify-between items-center bg-slate-50 mx-auto mt-4 mb-8 px-4 py-0 border shadow-md">
    <div class="flex items-center space-x-2 text-sm text-slate-700 leading-relaxed tracking-wide space-x-4">
      <NavigationItem class="py-1" route-name="dashboard" display-name="Dashboard"></NavigationItem>
      <NavigationItem v-if="currentRouteTitle" class="text-gold-500 font-medium active" :route-name="null" :display-name="currentRouteTitle"></NavigationItem>
    </div>

    <div v-if="currentUser" class="flex items-center text-sm text-slate-700 leading-relaxed tracking-wide space-x-2 ">
      <router-link class="py-1 px-2" :to="{ name: 'user.profile' }">{{ currentUser?.first_name }} {{ currentUser?.last_name }}</router-link>
      <svg @click="toggleNavDropdown" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </div>
  </nav>
</template>
