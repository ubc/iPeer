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
  type: {type: String, default: text}
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
