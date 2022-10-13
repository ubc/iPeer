<script lang="ts" setup>
  import { User } from '@/types/typings'
  const emit = defineEmits<{
    (e: 'update:profile'): void
  }>()
  const props = defineProps<{
    user: User
  }>()
</script>

<template>
  <div class="users">
    <router-view :user="user" @update:profile="$emit('update:profile')"></router-view>
  </div>
</template>