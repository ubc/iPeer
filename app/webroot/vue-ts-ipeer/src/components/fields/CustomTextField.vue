<script lang="ts" setup>
import { ref, toRef, onMounted, onBeforeUnmount } from 'vue'
import { useField, ErrorMessage } from 'vee-validate'
import autosize from 'autosize'

interface Props {
  label?: string
  name: string
  value?: null
  modelValue?: null
  rules?: string|object
  default?: string
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
  (e: 'update:event', option: string): void
}>()
const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  rules: undefined
})
const name        = toRef(props, 'name');
const { value: inputValue, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'textarea',
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
  <div class="form-group my-2">
    <label class="form-label" v-if="label">{{ label }}</label>
    <div class="form-control">
      <textarea :class="{'has-error': !!errorMessage, success: meta.valid, 'form-textarea': !props.disabled, 'form-muted': props.disabled}"
        ref="elementRef"
        style="resize: none; clear: right;padding: 0.3em 0.5em;"
        :id="name"
        :name="name"
        :value="inputValue"
        @change="$emit('update:event', $event)"
        @input="handleChange"
        @blur="handleBlur"
        v-on="validationListeners"
        v-bind="$attrs"
        :disabled="props.disabled"
      ></textarea>
      <ErrorMessage class="form-text text-muted" v-if="props.type !== 'radio'" :name="name" />
    </div>
  </div>
</template>
