<script lang="ts" setup>
// REFERENCES
const props = defineProps<{
  error: object
}>()
</script>

<template>
  <div class="error p-4 m-4 border border-red-500 bg-red-50 text-center">{{ error.text }}</div>
</template>
