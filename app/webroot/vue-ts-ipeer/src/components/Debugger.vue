<script setup lang="ts">
import { ref } from 'vue'
import config from 'envConfig'

interface Props {
  title: string
  state?: object
  form?: object
  data?: object
}
// REFERENCES
const props = defineProps<Props>()
// DATA
const debug = ref(false)
// METHODS
function onDebug() {debug.value = !debug.value}
</script>

<template>
  <div class="debugger">
    <div class="flex justify-between items-center">
      <h3 class="text-teal-600 font-medium">{{ title }}</h3>
      <button @click="onDebug">{{ debug ? 'Hide' : 'Show' }}</button>
    </div>
    <hr/>
    <div class="flex justify-evenly space-x-4" v-show="debug">
      <pre v-if="state" class="debug code flex-1">{{ JSON.stringify(state, null, 2) }}</pre>
      <pre v-if="form" class="debug code flex-1">{{ JSON.stringify(form, null, 2) }}</pre>
      <pre v-if="data" class="debug code flex-1">{{ JSON.stringify(data, null, 2) }}</pre>
    </div>
  </div>
</template>

<style lang="scss" scoped>
  .debug, .debugger, .preview {
    //display: none;
  }
  .preview {
    @apply text-sm flex justify-center text-slate-500 bg-slate-100;
  }
  .debug {
    @apply border shadow py-2 px-4 my-4;
    &.code {
      @apply bg-gray-50 text-sm font-light text-slate-500;
    }
  }
</style>
