<script lang="ts" setup>
import CustomCheckField from '@/components/fields/CustomCheckField.vue'
interface Props {
  label?: string
  options?: object[]
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:event', option: string): void
}>()
const props = defineProps<Props>()
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
          :disabled="props.disabled"
          @update:event="$emit('update:event', $event)"
      />
    </div>
  </div>
</template>
