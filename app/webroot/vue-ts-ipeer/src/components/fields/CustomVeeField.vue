<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef, onBeforeUnmount} from 'vue';
import { Field, ErrorMessage, useField } from 'vee-validate'
import autosize from 'autosize'
import {Props} from "@/components/fields/InputRange.vue";

// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
  (e: 'update:event', option: string): void
}>()
interface Props {
  as?: string
  type?: string
  label?: string
  name: string
  value?: null
  modelValue?: null
  rules?: string|object
  default?: string
  disabled?: boolean
}
const props = withDefaults(defineProps<Props>(), {
  as: 'input',
  type: 'text',
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
  <label class="flex flex-col" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <span v-if="label">{{ label }}</span>
    <template v-if="props.as === 'textarea'">
      <textarea style="resize: none; margin: 0.35em 0.35em;border-radius: 3px;border: 1px solid #999;clear: right;padding: 0.3em 0.5em;"
        ref="elementRef"
        class="form-field bg-gray-500"
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
    </template>
    <template v-if="props.as === 'input' || props.type === 'hidden'">
      <input
          :type="props.type"
          :id="name"
          class="form-field"
          :name="name"
          :value="inputValue"
          :disabled="props.disabled"
          @change="$emit('update:event', $event)"
          @input="handleChange"
          @blur="handleBlur"
          v-on="validationListeners"
          v-bind="$attrs"
      />
    </template>
    <template v-if="props.as === 'select'">
      <select
          class="form-field"
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
    </template>
    <ErrorMessage v-if="props.type !== 'radio'" class="form-text text-muted" :name="name" />
  </label>
</template>
