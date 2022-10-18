<script lang="ts" setup>
import { ref, toRef } from 'vue'
import type { User } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:profile'): void
}>()
const props = defineProps<{
  currentUser: User
}>()
// DATA
const user              = toRef<User>(props, 'user')
const status            = ref<string>('')
const error             = ref<object | null>(null)
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="layout">
    <router-view name="banner" v-slot="{ Component, pageTitle }" >
      <component :is="Component" />
    </router-view>
    <router-view name="navigation" v-slot="{ Component, pageTitle }" >
      <component :is="Component" :currentUser="currentUser" :page-title="pageTitle" :key="user" />
    </router-view>
    <router-view class="content" v-slot="{ Component }">
      <component :is="Component" :currentUser="currentUser" @update:profile="$emit('update:profile')" />
    </router-view>
  </div>
</template>
