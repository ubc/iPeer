<script setup lang="ts">

const props = defineProps<{
  title: string,
  icon?: object
}>()

</script>

<template>
  <h1 class="page-title">{{ props.title }}</h1>
  <slot></slot>
</template>