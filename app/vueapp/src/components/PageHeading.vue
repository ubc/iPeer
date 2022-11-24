<script lang="ts" setup>
import type { IPageHeading } from '@/types/typings'
const props = defineProps<{
  title?: string
  settings: IPageHeading
}>()
</script>

<template>
  <div class="page-heading">
    <h1 v-if="props.settings?.title" class="page-title">{{ props.settings?.title }}</h1>
    <div v-if="props.settings?.description || props.settings?.icon" :class="`flex items-center space-x-4 my-4 ${props.settings?.icon?.position}`">
      <component :is="props.settings?.icon.src" class="w-64" />
      <p v-html="props.settings?.description"></p>
    </div>
    <slot></slot>
  </div>
</template>
