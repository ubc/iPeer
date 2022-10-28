<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef, onBeforeUnmount} from 'vue';
import { useField, ErrorMessage } from 'vee-validate'
import autosize from 'autosize'
import {Props} from "@/components/fields/InputRange.vue";

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
  <label class="flex flex-col" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <span v-if="label">{{ label }}</span>
    <textarea
      ref="elementRef"
      class="form-text-input"
      style="resize: none; clear: right;padding: 0.3em 0.5em;"
      :id="name"
      :name="name"
      :value="inputValue"
      :disabled="disabled"
      @change="$emit('update:event', $event)"
      @input="handleChange"
      @blur="handleBlur"
      v-on="validationListeners"
      v-bind="$attrs"
    ></textarea>
    <ErrorMessage class="form-text text-muted" v-if="props.type !== 'radio'" :name="name" />
  </label>
</template>
