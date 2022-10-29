<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted, watchEffect, defineAsyncComponent } from 'vue'

import type {Evaluation, MixedResponse, MixedReviewData, User} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  currentUser: User,
  evaluation: Evaluation,
  question: MixedReviewData,
  initialState: MixedResponse
}>()

// DATA
// COMPUTED
const selfQuestion = computed(() => {
  if(props.question?.type) {
    return defineAsyncComponent({
      loader: () => import(`../../questions/SelfMixed${props.question?.type}Question.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
    })
  }
})
/** _backup for computing selfQuestion above
const selfQuestion = computed(() => {
  switch (props.question?.type) {
    case 'Likert':
      return  defineAsyncComponent(() => import('@/student/views/questions/SelfMixedLikertQuestion.vue'))
    case 'Sentence':
      return  defineAsyncComponent(() => import('@/student/views/questions/SelfMixedSentenceQuestion.vue'))
    case 'Paragraph':
      return  defineAsyncComponent(() => import('@/student/views/questions/SelfMixedParagraphQuestion.vue'))
    default:
      break
  }
})*/
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <component
    v-if="props.question"
    :is="selfQuestion"
    :question="props.question"
    :evaluation="props.evaluation"
    :current-user="props.currentUser"
    :initial-state="props.initialState"
    @update:initialState="$emit('update:initialState', $event)"
  />
</template>
