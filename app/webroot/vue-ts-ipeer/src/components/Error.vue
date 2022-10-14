<script lang="ts" setup="">
import { ref, reactive, watch, computed, onMounted } from 'vue';

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  error: string | object
}>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="error p-4 m-4 border border-red-500 bg-red-50 text-center">{{ JSON.stringify(error, null, 2) }}</div>
</template>

<style lang="scss" scoped>

</style>
