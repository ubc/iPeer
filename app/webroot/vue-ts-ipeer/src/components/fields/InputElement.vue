<script setup lang="ts">
import { toRef } from 'vue'
import { useField } from 'vee-validate'

const props = defineProps<{
  type?: string | 'text'
  value?: string | ''
  name: string
  placeholder?: string | ''
  disabled?: boolean | false
}>();

const name = toRef(props, 'name');

const { value: inputValue, errorMessage, handleBlur, handleChange, meta } = useField(name, undefined, {
  initialValue: props.value,
});
</script>

<template>
  <input class="form-input"
         :name="name"
         :id="name"
         :type="type"
         :value="inputValue"
         :placeholder="placeholder"
         @input="handleChange"
         @blur="handleBlur"
         :disabled="disabled"
  />
</template>
