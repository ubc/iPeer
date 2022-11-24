<script lang="ts" setup>
import { toRef } from 'vue'
import { useField } from 'vee-validate'

interface Props {
  modelValue: null
  name: string
  value?: string
  label?: string
  checked?: string|number|boolean
  rules?: string
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
}>()
const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  rules: undefined
})
// DATA
const name = toRef(props, 'name')
// COMPUTED
const { checked, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'radio',
  // initialValue: props.value, // will disable the validation on the radio buttons
  checkedValue: props.checked, // without checkedValue the validation will not work on the Likert ::TODO::
  validateOnValueUpdate: false
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
        :checked="Number(props.checked) === Number(props.value)"
        :disabled="props.disabled"
        @input="handleChange"
        @blur="handleBlur"
        v-on="validationListeners"
    />
    <span class="form-check-label text-sm" v-if="label">{{ label }}</span>
    <span class="visually-hidden form-text text-muted" v-if="label" :name="name">{{ errorMessage }}</span>
  </label>
</template>
