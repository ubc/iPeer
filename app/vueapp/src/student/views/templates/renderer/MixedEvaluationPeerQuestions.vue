<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'
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
      return defineAsyncComponent(() => import('@/student/views/questions/PeerMixedLikertQuestion.vue'))
    case 'Range':
      return defineAsyncComponent(() => import('@/student/views/questions/PeerMixedRangeQuestion.vue'))
    case 'Comment':
      return defineAsyncComponent(() => import('@/student/views/questions/PeerMixedCommentQuestion.vue'))
    case 'Sentence':
      return defineAsyncComponent(() => import('@/student/views/questions/PeerMixedSentenceQuestion.vue'))
    case 'Paragraph':
      return defineAsyncComponent(() => import('@/student/views/questions/PeerMixedParagraphQuestion.vue'))
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
