<script lang="ts" setup>
  import { useRoute } from 'vue-router'
  const route = useRoute()
</script>

<template>
  <h1 class="page-title">{{ route.meta.title }}</h1>
  <p class="description text-sm text-gray-600 my-4">{{ route.meta.description }}</p>
  <router-view></router-view>
</template>
