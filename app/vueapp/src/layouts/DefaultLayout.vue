<script lang="ts" setup>
import type { IUser } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'set:message', option: object): void
  (e: 'update:profile', option: object): void
}>()
const props = defineProps<{
  currentUser: IUser
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <main class="default-layout">
    <router-view
        :current-user="props.currentUser"
        @set:message="$emit('set:message', $event)"
        @update:profile="$emit('update:profile', $event)"
    />
  </main>
</template>
