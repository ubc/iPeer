<script lang="ts" setup>
  import type { User } from '@/types/typings'
  const emit = defineEmits<{
    (e: 'update:profile'): void
  }>()
  const props = defineProps<{
    currentUser: User
  }>()
</script>

<template>
  <div class="users">
    <router-view :currentUser="props.currentUser" @update:profile="$emit('update:profile')"></router-view>
  </div>
</template>