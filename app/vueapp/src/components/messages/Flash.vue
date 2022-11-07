<script lang="ts" setup>
import { isEmpty } from 'lodash'
// REFERENCES
const props = defineProps<{
  flash: object|null
}>()
const emit = defineEmits<{}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div v-if="!isEmpty(flash)" :class="`flash flash-${flash?.statusText} flash-dismissible fade show`">
    <div :class="`flash-wrapper flash-${flash?.statusText}`">
      <span :class="`flex-1`">{{ flash?.message }}</span>
      <span class="close" @click="flash = null">&times;</span>
    </div>
  </div>
</template>
