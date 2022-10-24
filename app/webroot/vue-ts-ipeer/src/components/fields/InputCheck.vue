<script lang="ts" setup>
import {toRefs} from 'vue';
import {useField} from 'vee-validate'
// REFERENCES
const emit = defineEmits<{
  (e: 'change:input', value: string): void
}>()
const props = defineProps<{
  reverse: boolean
  name: string
  value: string
  modelValue: string | number
  label: string
  type?: string | 'checkbox'
}>()
// DATA
const { name, value, selected } = toRefs(props)
const { checked, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'radio',
  checkedValue: props.value,
  validateOnValueUpdate: true
})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="form-group-check" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <div class="form-check" :class="{'form-check-inline': props.label}">
      <input
          class="form-check-input"
          type="checkbox"
          :id="value"
          :name="name"
          :value="modelValue"
          :data-value="value"
          @input="$emit('change:input', $event)"
      />
      <label class="form-check-label" v-if="props.label" :for="value">{{ props.label }}</label>
    </div>
  </div>
</template>
