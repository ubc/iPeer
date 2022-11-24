<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted, watchEffect, defineAsyncComponent } from 'vue'
import type { IUser, IEvaluation, IMixedResponse, IMixedEvaluationData } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  currentUser: IUser,
  evaluation: IEvaluation,
  question: IMixedEvaluationData,
  initialState: IMixedResponse
  autosave?: object
}>()

// DATA
// COMPUTED
const selfQuestion = computed(() => {
  switch (props.question?.type) {
    case 'Likert':
      return defineAsyncComponent(() => import('@/student/views/questions/SelfMixedLikertQuestion.vue'))
    case 'Sentence':
      return defineAsyncComponent(() => import('@/student/views/questions/SelfMixedSentenceQuestion.vue'))
    case 'Paragraph':
      return defineAsyncComponent(() => import('@/student/views/questions/SelfMixedParagraphQuestion.vue'))
    default:
      break
  }
})
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
