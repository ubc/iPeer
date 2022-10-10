<script setup lang="ts">

const props = defineProps<{
  title: string, icon: object
}>()

</script>

<template>
  <h1 class="page-title">{{ title }}</h1>
  <slot></slot>
</template>