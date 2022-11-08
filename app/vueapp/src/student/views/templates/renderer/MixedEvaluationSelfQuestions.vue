<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted, watchEffect, defineAsyncComponent } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ErrorComponent from '@/components/ErrorComponent.vue'
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
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/SelfMixedLikertQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Sentence':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/SelfMixedSentenceQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Paragraph':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/SelfMixedParagraphQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
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
