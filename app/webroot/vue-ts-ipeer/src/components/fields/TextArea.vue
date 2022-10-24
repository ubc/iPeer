<script lang="ts" setup>
import { ref, toRef, computed, onMounted, onBeforeUnmount } from 'vue'
import autosize from 'autosize'
import { useField } from 'vee-validate'

import type { FieldContext } from 'vee-validate'

// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  memberIdx?: number
  id?: string
  label?: string
  name: string
  value: string
  rules?: string
  disabled?: boolean | false
}>()
const name = toRef(props, 'name');
const { value: inputValue, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  initialValue: props.value,
  validateOnValueUpdate: true
});

// DATA
const elementRef = ref()
const validationListeners = {
  blur: handleChange,
  change: handleChange,
  input: (e: unknown) => handleChange(e, !!errorMessage.value)
}
// COMPUTED
// METHODS
// const eagerValidation = function (field: FieldContext) {
//   if (!field.errorMessage.value) {
//     return {
//       blur: field.handleChange,
//       change: field.handleChange,
//       input: (event: Event) => field.handleChange(event, false),
//     }
//   }
//
//   return {
//     blur: field.handleChange,
//     change: field.handleChange,
//     input: field.handleChange
//   }
// }
// handleChange(props.value);
// WATCH
// LIFECYCLE
onMounted(() => autosize(elementRef.value))
onBeforeUnmount(() => autosize.destroy(elementRef.value))
</script>

<template>
  <div class="form-group" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <label class="form-label" v-if="props.label" :for="props.id">{{ props.label }}</label>
    <div class="form-control flex flex-col">
      <div class="{{ inputValue }}"></div>
      <textarea ref="elementRef"
          class="form-input"
          :name="props.name"
          :id="props.id"
          :value="inputValue"
          :disabled="disabled"
          @input="handleChange"
          @blur="handleBlur"
          v-on="validationListeners"
      ></textarea>
      <span class="form-text text-muted" v-if="errorMessage || meta.valid">{{ errorMessage }}</span>
      <!--<span class="text-xs text-red-700">{{ meta }}</span>-->
    </div>
  </div>
</template>
