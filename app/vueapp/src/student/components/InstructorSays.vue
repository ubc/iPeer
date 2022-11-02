<script setup lang="ts">
const props = defineProps<{
  description: string
}>()
</script>

<template>
  <div class="section instructor my-4">
    <h5 class="text-sm leading-relaxed text-slate-900 font-medium">Your Instructor says:</h5>
    <blockquote class="py-2 pl-4 my-2 bg-white border-l-4 border-gray-200">
      <p class="text-sm leading-relaxed text-slate-900 font-normal" v-html="description"></p>
    </blockquote>
  </div>
</template>
