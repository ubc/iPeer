<script lang="ts" setup="">
import { ref, reactive, watch, computed, onMounted } from 'vue';

import PageTitle from '@/components/PageTitle.vue'

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
 page: object
}>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <PageTitle :title="page.title">
    <div class="flex items-center space-x-4 my-4">
      <component :is="page.icon.src" class="w-64" />
      <p v-html="page.description"></p>
    </div>
  </PageTitle>
</template>
