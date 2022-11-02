<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef} from 'vue';
import { useField, Field } from 'vee-validate'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
  (e: 'update:event', option: string): void
}>()
const props = defineProps<{
  label?: string
  name: string
  value?: string
  modelValue: string | number
  rules?: string
}>()

// DATA
const name = toRef(props, 'name');
// COMPUTED
const { checked, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'checkbox',
  checkedValue: props.value,  // true
  uncheckedValue: null,       // false
  validateOnValueUpdate: true
})
const validationListeners = {
  blur: handleChange,
  change: handleChange,
  input: (e: unknown) => handleChange(e, !!errorMessage.value)
}
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <label class="space-x-2">
    <input
        type="checkbox"
        class="text-sm"
        :name="name"
        :value="modelValue"
        :data-value="value"
        @change="$emit('update:event', $event)"
        @input="handleChange"
        @blur="handleBlur"
    />
    <span class="text-sm" v-if="label">{{ label }}</span>
  </label>
</template>
