<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import { NavigationItem, DropdownItem } from '@/components/navigation'

interface Props {}
// REFERENCES
const emit  = defineEmits()
const props = defineProps<Props>()
const route = useRoute()
const authStore = useAuthStore()
// DATA
// COMPUTED
const currentUser = computed(() => authStore.getCurrentUser)
const currentRouteName  = computed(() => {
  return route.name || ''
})
const currentRouteTitle = computed(() => {
  return route.meta.title || ''
})
const currentRouteBreadcrumb = computed(() => {
  return route.meta.breadcrumb || ''
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <nav class="navigation flex justify-between items-center bg-slate-50 mx-auto mt-4 mb-8 px-4 py-0 border shadow-md">
    <template v-if="currentUser">
      <div class="flex items-center space-x-2 text-sm text-slate-700 leading-relaxed tracking-wide space-x-4">
        <NavigationItem class="py-1" route-name="student.events" display-name="Dashboard"></NavigationItem>
        <NavigationItem v-if="currentRouteBreadcrumb" class="text-gold-500 font-medium active" :route-name="null" :display-name="currentRouteBreadcrumb"></NavigationItem>
      </div>
      <DropdownItem :current-user="currentUser" />
    </template>
  </nav>
</template>
