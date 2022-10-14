<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
// REFERENCES
const emit = defineEmits<{
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  type: string
  name: string
  modelValue: string | number
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <input :type="type" :name="name" :value="modelValue" @input="$emit('update:modelValue', $event)" />
</template>
