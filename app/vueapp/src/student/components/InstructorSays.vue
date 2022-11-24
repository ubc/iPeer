<script setup lang="ts">
const props = defineProps<{
  description: string
}>()
</script>

<template>
  <div v-if="props.description" class="section instructor my-4">
    <h5 class="text-sm leading-relaxed text-slate-900 font-medium">Your instructor says:</h5>
    <blockquote
        style="border-left-style: solid;"
        class="pl-4 my-2 bg-white border-l-2 border-gray-300">
      <p class="text-sm leading-relaxed text-slate-900 font-normal" v-html="props.description"></p>
    </blockquote>
  </div>
</template>
