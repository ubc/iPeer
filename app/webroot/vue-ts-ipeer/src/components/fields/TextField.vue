<script lang="ts" setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import autosize from 'autosize'

interface Props {
  label: string
  name: string
  modelValue: string | number
  disabled: boolean
}

const emit = defineEmits<{
  (e: 'update:modelValue', value: object): void
}>()
const props = defineProps<Props>()

const elem = ref()
onMounted(() => autosize(elem.value))
onBeforeUnmount(() => autosize.destroy(elem.value))
</script>

<template>
  <div class="relative mx-4 mb-2">
    <template v-if="disabled">
      <p class="text-sm text-slate-900 leading-relaxed text-left">"<span v-html="modelValue" />"</p>
    </template>
    <template v-else>
      <label class="w-full flex flex-col items-start md:flex-row md:space-x-12">
        <span v-if="label" class="text-sm text-pink-500">{{ label }}</span>
        <!-- Auto Resize Textarea -->
        <textarea ref="elem"
                  class="block flex-1 appearance-none bg-white
            border border-gray-400 hover:border-gray-500
            px-2 py-2 pr-8
            rounded shadow leading-tight
            focus:outline-none focus:shadow-outline"
                  :name="name"
                  :value="modelValue"
                  :disabled="disabled"
                  @input="emit('update:modelValue', $event)"
        >{{ modelValue }}</textarea>
      </label>
    </template>
  </div>
</template>