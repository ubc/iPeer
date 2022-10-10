<script lang="ts" setup>
  //...
  import Debugger from "@/components/Debugger.vue";
  import { User } from '@/types/typings'
  const emit = defineEmits<{
    (e: 'get:user-profile', option: string): void
  }>()
  const props = defineProps<{
    currentUser: User
  }>()

</script>

<template>
  <div class="users">
    <Debugger title="UsersIndex" :data="currentUser" />
    <router-view :current-user="currentUser" @get:user-profile="emit('get:user-profile', $event)"></router-view>
  </div>
</template>