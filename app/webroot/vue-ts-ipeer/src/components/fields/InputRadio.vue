<script lang="ts" setup>
import { toRef, toRefs, computed } from 'vue';
import { useField } from 'vee-validate'

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  label: string
  name: string
  value: string
  rules: string
  checked: boolean
  disabled?: boolean | false
}>()
// const name = toRef(props, 'name');
const { name, value } = toRefs(props)
const { checked: inputValue, handleChange, errorMessage, handleBlur, meta } = useField(name, props.rules, {
  type: 'radio',
  checkedValue: props.value,
  validateOnValueUpdate: true
})
// DATA
const validationListeners = {
  blur: handleChange,
  change: handleChange,
  input: (e: unknown) => handleChange(e, !!errorMessage.value)
}
// COMPUTED
// const isChecked = computed(() => {
//   if(selected.value && value.value === selected.value) {
//     return true
//   }
//   return
// })
// METHODS
// handleChange(props.value);
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="form-radio flex-col justify-center items-center text-xs text-pink-500" :class="{'flex flex-row': props.label, 'has-error': !!errorMessage, success: meta.valid}">
    <input type="radio" :id="name" class="form-check-input " :class="{'form-check-input-error': !!errorMessage}"
        v-on="validationListeners"
        :name="name"
        :value="props.value"
        :disabled="disabled"
        :checked="checked"
         @input="handleChange"
         @blur="handleBlur"
    />
    <label class="form-radio-label" v-if="props.label" :for="name">{{ props.label }}</label>
  </div>
</template>
