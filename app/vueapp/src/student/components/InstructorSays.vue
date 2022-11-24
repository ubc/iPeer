<script setup lang="ts">
const props = defineProps<{
  description: string
}>()
</script>

<template>
  <div v-if="props.description" class="section instructor my-4">
    <h5 class="text-lg leading-relaxed font-medium">Your instructor says:</h5>
    <blockquote
        style="border-left-style: solid;"
        class="pl-4 my-2 border-l-4 border-gray-300 dark:border-gray-600">
      <p class="text-sm leading-relaxed" v-html="props.description"></p>
    </blockquote>
  </div>
</template>
