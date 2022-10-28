<script lang="ts" setup>
import { toRef } from 'vue';
import { useField } from 'vee-validate'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  modelValue: string | number | null
  name: string
  value?: string
  label?: string
  checked?: string|number|boolean
  rules?: string
}>()

// DATA
const name = toRef(props, 'name');
// COMPUTED
const { checked, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'radio',
  checkedValue: props.value,
  uncheckedValue: null,
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
  <label class="form-check centered space-x-2 flex flex-col" :class="{'flex flex-row': props.label, 'has-error': !!errorMessage, success: meta.valid}">
    <input
        type="radio"
        :id="name"
        class="form-check-input text-sm"
        :name="name"
        :value="props.value"
        :data-value="checked"
        :data-error="errorMessage"
        :checked="props.checked ? parseInt(props.value) === parseInt(props.checked) : checked"
        @input="handleChange(value)"
        @blur="handleBlur"
    />
    <span class="form-check-label text-sm" v-if="label">{{ label }}</span>
    <span class="visually-hidden form-text text-muted" :name="name">{{ errorMessage }}</span>
  </label>
</template>
