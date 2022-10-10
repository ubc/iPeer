<script setup lang="ts">

const props = defineProps<{
  title: string, icon: object
}>()

</script>

<template>
  <div class="block">
    <h2 class="section-title">{{ title }}</h2>
    <slot></slot>
  </div>
</template>