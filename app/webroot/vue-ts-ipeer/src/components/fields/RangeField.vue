<script lang="ts" setup>

interface Props {
  name: string
  modelValue: string | number
  min: string | number
  max: string | number
  disabled: boolean
}
const emit = defineEmits<{
  (e: 'update:modelValue', value: object): void
}>()
const props = defineProps<Props>()

</script>

<template>
  <input type="range" class="slider"
         :min="min"
         :max="max"
         :name="name"
         :value="modelValue"
         :disabled="disabled"
         @input="emit('update:modelValue', $event)"
  />
</template>

<style lang="scss" scoped></style>
