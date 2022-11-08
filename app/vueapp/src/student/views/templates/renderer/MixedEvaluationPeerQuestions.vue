<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ErrorComponent from '@/components/ErrorComponent.vue'
import type { IUser, IEvaluation, IMixedResponse, IMixedEvaluationData } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
  (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  members: IUser[],
  currentUser: IUser,
  evaluation: IEvaluation,
  question: IMixedEvaluationData,
  initialState: IMixedResponse
  autosave?: object
}>()
// DATA
// COMPUTED
const peerQuestion = computed(() => {
  switch (props.question?.type) {
    case 'Likert':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/PeerMixedLikertQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Range':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/PeerMixedRangeQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Comment':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/PeerMixedCommentQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Sentence':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/PeerMixedSentenceQuestion.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Paragraph':
      return defineAsyncComponent({
        loader: () => import('@/student/views/questions/PeerMixedParagraphQuestion.vue'),
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
    :is="peerQuestion"
    :question="props.question"
    :evaluation="props.evaluation"
    :members="props.members"
    :initial-state="props.initialState"
    @update:initialState="$emit('update:initialState', $event)"
  />
</template>
