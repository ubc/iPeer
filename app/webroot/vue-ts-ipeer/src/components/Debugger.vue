<script setup lang="ts">
import { ref } from 'vue'
import { isEmpty } from 'lodash'

// REFERENCES
interface Props {
  title: string
  state?: object
  form?: object
  data?: object
}
const props = defineProps<Props>()

// DATA
const debug = ref(false)

</script>

<template>
  <div class="debugger">
    <div class="flex justify-between items-center">
      <h3 class="text-teal-600 font-medium">{{ title }}</h3>
      <button @click="debug = !debug">{{ debug ? 'Hide' : 'Show' }}</button>
    </div>
    <hr/>
    <div class="flex justify-evenly space-x-4" v-show="debug">
      <pre v-if="state" class="debug code flex-1">{{ JSON.stringify(state, null, 2) }}</pre>
      <pre v-if="form" class="debug code flex-1">{{ JSON.stringify(form, null, 2) }}</pre>
      <pre v-if="data" class="debug code flex-1">{{ JSON.stringify(data, null, 2) }}</pre>
    </div>
  </div>
</template>
