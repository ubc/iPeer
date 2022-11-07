<script lang="ts" setup>
import { ref, computed } from 'vue'
import CustomSelectField from '@/components/fields/CustomSelectField.vue'

interface Props {
  default?: string,
  label?: string
  name: string
  options?: string[]
  modelValue?: string | number
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:event', option: string): void
}>()
const props = defineProps<Props>()
// DATA
const selected = computed(() => props.default ? props.default : props.options?.length > 0 ? props.options[0] : null)
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="timeframe flex flex-col items-center md:flex-row md:space-x-12">
    <div class="label">Timeframe:</div>
    <CustomSelectField :default="props.default" :name="props.name" @update:event="$emit('update:event', $event)">
      <option v-for="(option, index) of props.options" :key="index" :value="option" :selected="option===selected">{{ option }}</option>
    </CustomSelectField>
  </div>
</template>
