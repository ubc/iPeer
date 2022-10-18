<script setup lang="ts">
import { toRef } from 'vue'
import { useField } from 'vee-validate'

const props = defineProps<{
  type?: string | 'text'
  value?: string | ''
  name?: string
  label?: string,
  placeholder?: string | ''
  disabled?: boolean | false
}>();

const name = toRef(props, 'name');

const { value: inputValue, errorMessage, handleBlur, handleChange, meta } = useField(name, undefined, {
  initialValue: props.value,
});
</script>

<template>
  <div class="form-group" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <label v-if="label" class="form-label" :for="name">{{ label }}</label>
    <div class="form-control flex flex-col">
      <input class="form-input-range flex-1"
             :name="name"
             :id="name"
             :type="type"
             :value="inputValue"
             :placeholder="placeholder"
             @input="handleChange"
             @blur="handleBlur"
             :disabled="disabled"
      />
      <span :class="`form--text text-xs ${meta.dirty ? 'text-red-500' : 'text-green-500'} text-muted`">{{ meta }}</span>
      <span class="form-text text-muted" v-show="errorMessage || meta.valid">{{ errorMessage }}</span>
    </div>
  </div>
</template>
