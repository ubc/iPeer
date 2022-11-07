<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  action: object
  notification: object
}>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>
<div class="notification">

</div>
<template>
  <div class="">
    <div class="title">{{ action.default_title }}</div>
    <div class="notification">{{ notification?.message }}</div>
  </div>
</template>
