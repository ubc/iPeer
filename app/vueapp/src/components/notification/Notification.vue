<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  notification: object
}>()

// DATA

// COMPUTED
const notifications = computed(() => {
  return [].push(props.notification)
})
// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="notifications" v-if="props.notifications?.length">
    <div class="notification" v-for="notification of props.notifications" :key="notification">
      {{ notification?.message }}
    </div>
  </div>
</template>
