<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';

import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconNewspaper } from '@/components/icons'
import TakeBreak from "@/student/components/TakeBreak.vue";

import type {IEvaluation, IUser} from '@/types/typings'
interface Props {
  evaluation: IEvaluation
  currentUser: IUser
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
  switch (props.evaluation?.template) {
    case 'SimpleEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/reviews/SimpleEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
      })
    case 'RubricEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/reviews/RubricEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
      })
    case 'MixedEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/reviews/MixedEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
      })
  }
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
      :self="props.evaluation?.self_eval"
      :rubric="props.evaluation?.rubric"
      :response="props.evaluation?.response"
      :current-user="props.currentUser"
      :disabled="props.disabled"
  ></component>

  <TakeBreak />
</template>
