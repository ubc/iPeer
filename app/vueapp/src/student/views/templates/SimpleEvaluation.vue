<script lang="ts" setup>
import { ref, unref, toRef, reactive, watch, computed, onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map } from 'lodash'

import TakeNote from '@/student/components/TakeNote.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerSimpleRangeQuestion from '@/student/views/questions/PeerSimpleRangeQuestion.vue'
import PeerSimpleCommentQuestion from '@/student/views/questions/PeerSimpleCommentQuestion.vue'
import { CustomHiddenField } from '@/components/fields'

import type { IUser, IEvaluation, ISimpleEvaluation, ISimpleEvaluationData, ISimpleResponse, ISimpleResponseData } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
  isDisabled: boolean
}>()
const route = useRoute()
const router = useRouter()
// DATA
const evaluation_form   = ref()
const members           = toRef(props, 'members')
const evaluation        = toRef(props, 'evaluation')
const form              = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  rubric_id: computed(() => evaluation.value?.rubric_id),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => members.value?.length),
  member_ids: computed<string[]|any>(() => map(members.value, member => member.id))
})
const initialState      = ref<ISimpleResponse|any>({})
function getInitialState() {
  return {
    id: '',
    submitter_id: props.currentUser?.id,
    submitted: null,
    date_submitted: '',
    data: {
      points: [],
      comments: []
    }
  }
}
// points: [map(members.value, (member) => evaluation.value?.simple?.point_per_member)],
function setInitialState() {}
const questions = reactive({
  points: {
    title: '1. Please rate each peer\'s relative contribution.',
    description: ''
  },
  comments: {
    title: '2. Please provide overall comments about each peer.',
    description: ''
  }
})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
onBeforeMount(() => {
  const currentState = getInitialState()
  if(evaluation.value?.response && !isEmpty(evaluation.value?.response)) {
    initialState.value = Object.assign(currentState, unref(evaluation.value?.response))
  } else {
    initialState.value = currentState
  }
})
</script>

<template>
  <EvaluationForm
      @submit="onSubmit"
      :initial-state="initialState"
      :evaluation="evaluation"
      v-slot="{ onSave, errors, values, isSubmitting, evaluationRef, message }"
      @set:message="$emit('set:message', message)"
  >
    <slot name="header">
      <CustomHiddenField type="hidden" name="event_id" :value="evaluation?.id" />
      <CustomHiddenField type="hidden" name="group_id" :value="evaluation?.group?.id" />
      <CustomHiddenField type="hidden" name="course_id" :value="evaluation?.course?.id" />
      <CustomHiddenField type="hidden" name="data[Evaluation][evaluator_id]" :value="currentUser?.id" />
      <CustomHiddenField type="hidden" name="evaluateeCount" :value="members?.length" />
      <CustomHiddenField type="hidden" name="memberIDs[]" v-for="(m,i) of form?.member_ids" :key="i" :value="m" />
    </slot>

    <slot name="main">
      <PeerSimpleRangeQuestion
          :members="members"
          :remaining="evaluation?.simple?.remaining"
          :point_per_member="evaluation?.simple?.point_per_member"
          :initialState="initialState"
          :name="`points`"
          :question="questions.points.title"
          :description="questions.points.description"
          :disabled="isDisabled"
      />
      <PeerSimpleCommentQuestion
          :members="members"
          :initialState="initialState"
          :name="'comments'"
          :question="questions.comments.title"
          :description="questions.comments.description"
          :disabled="isDisabled"
      />
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>

  </EvaluationForm>
</template>

<style lang="scss">
.notification {
  &.success {
    @apply text-green-600;
  }
  &.danger {
    @apply text-red-600;
  }
}
</style>