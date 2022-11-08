<script lang="ts" setup>
import ImgSpinner from '@/assets/img/spinner.gif'
// REFERENCES
const props = defineProps<{
  text?: string
  autosave?: boolean
}>()
</script>

<template>
  <span class="flex justify-between items-center">
    <span class="">{{ props.text }}</span>
    <span :class="`text-xs mx-8 text-${props.autosave?.type}`">
      <img v-if="props.autosave" class="w-4 h-4" :src="ImgSpinner" />
    </span>
  </span>
</template>
