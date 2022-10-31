<script lang="ts" setup>
  import type { IUser } from '@/types/typings'
  const emit = defineEmits<{
    (e: 'update:profile'): void
  }>()
  const props = defineProps<{
    currentUser: IUser
  }>()
</script>

<template>
  <div class="users">
    <router-view :currentUser="props.currentUser" @update:profile="$emit('update:profile')"></router-view>
  </div>
</template>