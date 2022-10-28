<script lang="ts" setup>
import { toRef } from 'vue';
import { useField, ErrorMessage } from 'vee-validate'

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
interface Props {
  label?: string
  name: string
  value?: null
  modelValue?: null
  rules?: string|object
  disabled?: boolean
}
const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  rules: undefined
})
const name        = toRef(props, 'name');
const { value: inputValue, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  initialValue: props.value,
  validateOnValueUpdate: false
});
// DATA
const validationListeners = {
  blur: handleChange,
  change: handleChange,
  input: (e: unknown) => handleChange(e, !!errorMessage.value)
}
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <label class="flex flex-col" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <span v-if="label">{{ label }}</span>
    <input
      class="form-text-input"
      type="text"
      :id="name"
      :name="name"
      :value="inputValue"
      :disabled="props.disabled"
      @change="$emit('update:event', $event)"
      @input="handleChange"
      @blur="handleBlur"
      v-on="validationListeners"
      v-bind="$attrs"
    />
    <ErrorMessage class="form-text text-muted" v-if="props.type !== 'radio'" :name="name" />
  </label>
</template>
