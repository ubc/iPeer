<script lang="ts" setup>
// REFERENCES

import type {IUser} from "@/types/typings";
const emit = defineEmits<{
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
  <main class="auth-layout">
    <router-view :current-user="props.currentUser" />
  </main>
</template>
