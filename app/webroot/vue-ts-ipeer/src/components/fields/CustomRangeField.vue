<script lang="ts" setup>
import { toRefs } from 'vue';
import { useField } from 'vee-validate'

export interface Props {
  label?: string
  name: string
  value: string
  response: object
  points: string | number
  rules?: string
  disabled?: boolean
  placeholder?: string
  min?: string | number
  step?: string | number
  max?: string | number
  text?: string[]
}

// REFERENCES
const emit = defineEmits<{
  (e: 'update:input', option: object): void
}>()
const props = withDefaults(defineProps<Props>(), {
  min: 0,
  step: 1,
  max: 100,
  text: () => ['min', 'max'],
  placeholder: '',
  disabled: false
})

const { name, value } = toRefs(props);
const { value: inputValue, errorMessage, handleBlur, handleChange, meta } = useField(name, props.rules, {
  initialValue: props.value,
  validateOnValueUpdate: true
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
  <div class="block block-slider" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <label class="form-range form-range-inline flex flex-col">
      <span class="form-range-label text-slate-500">{{ props.label }}</span>
      <span class="tick"></span>
      <input class="form-range-input" type="range"
             :min="props.min" :step="props.step" :max="props.max"
             :name="name"
             :value="inputValue"
             :disabled="disabled"
             v-on="validationListeners"
             @input="$emit('update:input', $event)"
             @blur="handleBlur"
      />
    </label>
    <div v-if="props.text" class="flex justify-between items-center">
      <div v-if="props.text[0]" class="min text-sm text-slate-500 font-normal">{{ props.text[0] }}</div>
      <div v-if="props.text[1]" class="max text-sm text-slate-500 font-normal">{{ props.text[1] }}</div>
    </div>
  </div>
</template>