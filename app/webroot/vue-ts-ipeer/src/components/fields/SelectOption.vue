<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

interface Props {
  default: string,
  label: string
  name: string
  options: string[]
  modelValue: string | number
}
// REFERENCES
const emit = defineEmits<{
  (e: 'change:select', value: string): void
}>()
const props = defineProps<Props>()

// DATA
const defaultValue = ref(props.default)
const selected = computed(() => props.default ? props.default : props.options.length > 0 ? props.options[0] : null)

// COMPUTED

// METHODS
function handleChange(event) {
  emit('change:select', event)
}

// WATCH

// LIFECYCLE

</script>

<template>
  <div :class="label ? 'timeframe flex flex-col items-center md:flex-row md:space-x-12' : ''">
    <label v-if="label">{{ label }}</label>
    <div class="inline-block relative w-64">
      <select
          class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
          :name="name"
          @change="handleChange"
      >
        <option v-if="defaultValue">{{ defaultValue }}</option>
        <option v-for="(option, index) of options" :key="index" :value="option" :selected="option===selected">{{ option }}</option>
      </select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>

</style>
