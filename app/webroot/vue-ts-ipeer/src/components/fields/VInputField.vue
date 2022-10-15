<script setup lang="ts">
import { toRef } from 'vue'
import { useField } from 'vee-validate'

const props = defineProps({
  type: {type: String, default: 'text'},
  value: {type: String, default: ''},
  name: {type: String, required: true},
  label: {type: String, required: true},
  placeholder: {type: String, default: ''},
  disabled: {type: Boolean, default: false}
});

const name = toRef(props, 'name');

const { value: inputValue, errorMessage, handleBlur, handleChange, meta } = useField(name, undefined, {
  initialValue: props.value,
});
</script>

<template>
  <div class="form-group" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <label class="form-label" :for="name">{{ label }}</label>
    <div class="form-control">
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
      <span class="form-text text-muted" v-show="errorMessage || meta.valid">{{ errorMessage }}</span>
    </div>
  </div>
</template>
