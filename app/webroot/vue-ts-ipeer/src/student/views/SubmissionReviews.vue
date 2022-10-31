<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';

import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconNewspaper } from '@/components/icons'

import type { IEvaluation } from '@/types/typings'
interface Props {
  evaluation: IEvaluation
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()

// DATA

// COMPUTED
const template = computed(() => {
//   if(evaluation.value?.template) {
//     return defineAsyncComponent({
//       loader: () => import(`@/student/views/templates/${evaluation.value?.template}.vue`),
//       loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
//     })
//   }
})
// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <SectionTitle title="Reviews of You" />
  <SectionSubtitle subtitle="Read your group's evaluation of you" :icon="{src: IconNewspaper, size: '3.75rem'}" />

  <component
      :is="template"
      :members="props.members"
      :evaluation="props.evaluation"
      :currentUser="props.currentUser"
      :disabled="props.disabled"
      @fetch:evaluation="$emit('fetch:evaluation')"
  ></component>
</template>

<style lang="scss" scoped>

</style>
