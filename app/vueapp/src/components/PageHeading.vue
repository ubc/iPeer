<script lang="ts" setup>
import PageTitle from '@/components/PageTitle.vue'
const props = defineProps<{
 page: object
}>()
</script>

<template>
  <PageTitle :title="page.title">
    <div class="flex items-center space-x-4 my-4">
      <component :is="page.icon.src" class="w-64" />
      <p v-html="page.description"></p>
    </div>
  </PageTitle>
</template>
