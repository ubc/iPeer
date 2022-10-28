<script lang="ts" setup>
const props = defineProps<{ height?: string|'auto' }>()
</script>

<template>
  <div :style="{height: props.height}" class="spinner-wrapper">
    <div class="spin"></div>
  </div>
</template>
