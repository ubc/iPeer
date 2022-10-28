<script lang="ts" setup>
import CustomCheckField from '@/components/fields/CustomCheckField.vue'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:event', option: string): void
}>()
const props = defineProps<{
  options: object[]
  label?: string
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="limit flex flex-col items-center md:flex-row md:space-x-16">
    <div class="label" v-if="props.label">{{ props.label }}</div>
    <div class="flex items-center space-x-8">
      <CustomCheckField
          v-for="option of props.options"
          :key="option"
          :name="option.name"
          :value="option.value"
          :label="option.label"
          @update:event="$emit('update:event', $event)"
      />
    </div>
  </div>
</template>
