<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

interface Props {
  reverse: boolean
  name: string
  value: string
  modelValue: string | number
  label: string
}
// REFERENCES
const emit = defineEmits<{
  (e: 'change:input', value: string): void
}>()
const props = defineProps<Props>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <label class="text-sm text-slate-900 space-x-2 leading-relaxed" :class="reverse ? 'flex flex-row-reverse' : null">
    <input
        type="checkbox"
        :id="label"
        :type="type"
        :name="name"
        :data-value="value"
        :value="modelValue"
        @input="$emit('change:input', $event)"
    >
    <span v-if="label">{{ label }}</span>
  </label>
</template>

<style lang="scss" scoped>

</style>
