<script lang="ts" setup>
import { toRef } from 'vue'
import { useField } from 'vee-validate'

interface Props {
  disabled?: boolean
  type: string
  value: string
  name: string
  label?: string
  successMessage?: string
  placeholder?: string
}

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  value: {
    type: String,
    default: '',
  },
  name: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  successMessage: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

// use `toRef` to create reactive references to `name` prop which is passed to `useField`
// this is important because vee-validte needs to know if the field name changes
// https://vee-validate.logaretm.com/v4/guide/composition-api/caveats
const name = toRef(props, 'name');

// we don't provide any rules here because we are using form-level validation
// https://vee-validate.logaretm.com/v4/guide/validation#form-level-validation
const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
  meta,
} = useField(name, undefined, {
  initialValue: props.value,
});
</script>

<template>
  <div class="Text--Input form-field" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
    <label class="form-label" :for="name">{{ label }}</label>
    <div class="form-input">
      <input
          :name="name"
          :id="name"
          :type="type"
          :value="inputValue"
          :placeholder="placeholder"
          @input="handleChange"
          @blur="handleBlur"
          :class="{
            'is-valid': meta.valid && meta.touched,
            'is-invalid': !meta.valid && meta.touched
          }"
          :disabled="disabled"
      />
      <span class="input-message" v-show="errorMessage || meta.valid">{{ errorMessage || successMessage }}</span>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.form-field {
  position: relative;
  margin-bottom: calc(1em * 1.5);
  width: 100%;
}
input[type="text"], input[type="email"], input[type="file"], input[type="password"], textarea, select, button {
  @apply mx-1.5 my-1.5 ;          //margin: 0.35em 0.35em;
  @apply px-1.5 py-2 ;            //padding: 0.3em 0.5em;
  @apply rounded;                 //border-radius: 3px;
  @apply border border-[#999];    //border: 1px solid #999;
  clear: right;
}
.form {

  &-field {
    @apply flex flex-col items-center md:flex-row space-x-3;

    label {
      @apply block justify-center items-center;
      @apply w-full md:w-[200px];
      @apply text-sm md:text-right;
      @apply font-medium;
      @apply mb-1 md:mb-0;
      @apply ml-6 md:ml-0;

      //display: block;
      //margin-bottom: 4px;
      //width: 100%;
      &::after {
        content: ":";
      }
    }

    //&.has-error input {background-color: var(--error-bg-color);color: var(--error-color);}
    //&.has-error input:focus {border-color: var(--error-color);}
    &.has-error .input-message {
      // color: var(--error-color);
      @apply text-red-700;
    }
    //&.success input {background-color: var(--success-bg-color);color: var(--success-color);}
    //&.success input:focus {border-color: var(--success-color);}
    &.success .input-message {
      //color: var(--success-color);
      @apply text-green-500;
    }
  }

  &-input {
    @apply flex flex-col;
    @apply relative;
    @apply w-full md:w-[350px];

    input {
      @apply rounded;                 // border-radius: 5px;
      // border: 2px solid transparent;
      @apply py-2 px-1.5;             // padding: 15px 10px;
      outline: none;
      @apply bg-slate-100;            // background-color: #f2f5f7;
      @apply flex-1; //w-full;                  // width: 100%;
      transition: border-color 0.3s ease-in-out, color 0.3s ease-in-out, background-color 0.3s ease-in-out;

      &:focus {
        border-color: var(--primary-color);
      }

      &.is-valid {@apply bg-green-50}
      &.is-invalid {@apply bg-red-50}
    }

    .input-message {
      @apply absolute left-0 -bottom-8;
      @apply text-xs;
      @apply mt-1 mb-4;
      @apply ml-2 mr-0;
    }

  }
}





</style>
