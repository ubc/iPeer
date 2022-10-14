<script lang="ts" setup>

defineProps<{
  height: string
}>()
</script>

<template>
  <div :style="{height: height}" class="spinner-wrapper">
    <div class="spin"></div>
  </div>
</template>
