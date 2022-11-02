<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef, onBeforeUnmount} from 'vue';
import { Field, ErrorMessage, useField } from 'vee-validate'
import autosize from 'autosize'

// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
  (e: 'update:event', option: string): void
}>()
interface Props {
  label?: string
  name: string
  value?: null
  modelValue?: null
  rules?: string|object
  default?: string
  disabled?: boolean
}
const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  rules: undefined
})
const name        = toRef(props, 'name');
const { value: inputValue, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: props.type,
  initialValue: props.value,
  validateOnValueUpdate: false
});
// DATA
const elementRef  = ref()
const defaultValue = ref(props.default)
const validationListeners = {
  blur: handleChange,
  change: handleChange,
  input: (e: unknown) => handleChange(e, !!errorMessage.value)
}
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
onMounted(() => autosize(elementRef.value))
onBeforeUnmount(() => autosize.destroy(elementRef.value))
</script>

<template>
  <label class="form-select" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <span class="form-select-label" v-if="label">{{ label }}</span>
    <select
        class="form-select-input"
        :id="name"
        :name="name"
        :disabled="props.disabled"
        @change="$emit('update:event', $event)"
        @input="handleChange"
        v-on="validationListeners"
        v-bind="$attrs"
    >
      <option v-if="defaultValue" :value="defaultValue">{{ defaultValue }}</option>
      <slot />
    </select>
    <ErrorMessage class="form-text text-muted" :name="name" />
  </label>
</template>
