<script lang="ts" setup>
import { toRef, computed } from 'vue';
import { useField } from 'vee-validate'

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  type: string | 'text'
  label: string
  name: string
  value: string
  rules: string
  disabled?: boolean | false
}>()
const name = toRef(props, 'name');
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
// handleChange(props.value);
// WATCH
// LIFECYCLE
</script>

<template>
  <template v-if="type === 'hidden'"><input :type="type" :name="props.name" v-model="inputValue" /></template>
  <template v-else>
    <div class="form-group" :class="{ 'has-error': !!errorMessage, success: meta.valid }">
      <label class="form-label" v-if="props.label" :for="props.name">{{ props.label }}</label>
      <div class="form-control md:max-w-[320px]">
        <input v-bind="$attrs"
            class="form-input flex-1"
            :id="props.name"
            v-on="validationListeners"
            :type="props.type"
            :name="props.name"
            v-model="inputValue"
            :placeholder="placeholder"
            :disabled="disabled"
            @input="handleChange"
            @blur="handleBlur"
        />
        <!-- :value="inputValue" -->
        <!--<span class="text-xs text-red-700">{{ meta }}</span>-->
        <span class="form-text text-muted" v-if="errorMessage || meta.valid">{{ errorMessage }}</span>
      </div>
    </div>
  </template>
</template>



<!--


<script lang="ts" setup>
import {} from 'vue';
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
  type: string | 'text'
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <template v-if="type === 'radio' || type === 'checkbox'">
    <label class="form-check flex items-center mx-1 space-x-2" :class="reverse ? 'flex flex-row-reverse' : null">
      <input class="form-check-input w-4 h-4" :type="type" :name="name" :value="modelValue" :data-value="value" @input="$emit('change:input', $event)" />
      <span class="form-check-label" v-if="label">{{ label }}</span>
    </label>
  </template>
  <template v-else>
    <label class="form-group space-x-4" :class="reverse ? 'flex flex-row-reverse' : null">
      <span class="form-label" v-if="label">{{ label }}</span>
      <div class="form-control">
        <input class="form-input" :type="type" :value="modelValue" :data-value="value" @input="$emit('change:input', $event)" />
        <span class="form-text"></span>
      </div>
    </label>
  </template>
</template>



-->