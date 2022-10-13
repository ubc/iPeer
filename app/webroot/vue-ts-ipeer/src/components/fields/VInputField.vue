<script setup lang="ts">
import { toRef } from 'vue'
import { useField } from 'vee-validate'

const props = defineProps({
  type: {type: String, default: 'text'},
  value: {type: String, default: ''},
  name: {type: String, required: true},
  label: {type: String, required: true},
  placeholder: {type: String, default: ''},
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
      />
      <span class="form-text text-muted" v-show="errorMessage || meta.valid">{{ errorMessage }}</span>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.form {
  &-group {
    @apply relative w-[90%] mx-[5%] md:w-full md:mx-0;
    @apply flex-col md:flex-row flex items-center;
    @apply mb-0 md:mb-5;

    &.has-error input {
      // @apply text-xs;
      @apply text-red-500;
      @apply bg-red-50;
    }

    &.has-error input:focus,
    &.has-error .help-message {
      @apply text-red-500;
    }
  }

  &-control {
    @apply relative w-full;
    @apply flex;
    @apply mb-4 md:mb-0;
  }

  &-label {
    @apply block w-full md:max-w-[200px];
    @apply text-sm text-left md:text-right;
    @apply font-normal;
    @apply md:mr-4;
    &::after {
      content: ":";
    }
  }

  &-input {
    @apply block w-full md:max-w-[300px];
    //@apply w-full;
    @apply flex-1;
    @apply py-3 px-2;
    @apply bg-gray-50;
    @apply rounded;
    @apply outline-none;
    @apply m-0;
    transition: border-color 0.3s ease-in-out, color 0.3s ease-in-out,
    background-color 0.3s ease-in-out;

    input:focus {
      @apply border border-gray-500;
    }
  }

  &-text {
    @apply absolute w-full;
    @apply -bottom-4 left-0.5; //bottom: calc(-1.5 * 1em);
    @apply text-red-700 text-xs;
    @apply m-0;
  }

  .text-muted {
     @apply text-slate-500;
  }
}
</style>
