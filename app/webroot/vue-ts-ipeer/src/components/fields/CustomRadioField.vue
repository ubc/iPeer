<script lang="ts" setup>
import { toRef } from 'vue';
import { useField } from 'vee-validate'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  modelValue: string | number | null
  label?: string
  name: string
  value?: string
  checked?: string|number|boolean
  rules?: string
}>()

// DATA
const name = toRef(props, 'name');
const value = toRef(props, 'value');
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
        class="form-check-input text-sm"
        :id="name"
        :name="name"
        :value="props.value"
        :checked="parseInt(props.checked) === parseInt(props.value)"
        :data-value="props.checked"
        :data-error="errorMessage"
        @input="handleChange(value)"
        @blur="handleBlur"
    />
    <span class="form-check-label text-sm" v-if="label">{{ label }}</span>
    <span class="visually-hidden form-text text-muted" v-if="label" :name="name">{{ errorMessage }}</span>
  </label>
</template>
