<script lang="ts" setup>
import { ref, unref, toRef, reactive, watch, computed, onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map } from 'lodash'

import TakeNote from '@/student/components/TakeNote.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerSimpleRangeQuestion from '@/student/views/questions/PeerSimpleRangeQuestion.vue'
import PeerSimpleCommentQuestion from '@/student/views/questions/PeerSimpleCommentQuestion.vue'
import { CustomHiddenField } from '@/components/fields'

import type { User, Evaluation, SimpleResponse } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  members: User[]
  currentUser: User
  evaluation: Evaluation
}>()
const route = useRoute()
const router = useRouter()
// DATA
const evaluation_form = ref()
const evaluation      = toRef(props, 'evaluation')
let form              = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  rubric_id: computed(() => evaluation.value?.rubric_id),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => evaluation.value?.members?.length),
  member_ids: computed<string[]>(() => map(evaluation.value?.members, member => member.id))
})
/**
const initialState    = computed<SimpleResponse | any>(() => {
  if(evaluation.value?.response && !isEmpty(evaluation.value?.response)) {
    return unref(evaluation.value?.response)
  } else {
    return {
      submitter_id: evaluation.value?.id,
      submitted: null,
      date_submitted: '',
      data: {
        points: [],
        comments: []
      }
    }
  }
})*/
const initialState      = ref<SimpleResponse|any>({})
function getInitialState() {
  return {
    id: '',
    submitter_id: evaluation.value?.id,
    submitted: null,
    date_submitted: '',
    data: {
      points: [],
      comments: []
    }
  }
}
function setInitialState() {}

const questions = reactive({
  points: {
    title: '1. Please rate each peer\'s relative contribution.',
    description: 'Section description'
  },
  comments: {
    title: '2. Please provide overall comments about each peer.',
    description: 'Section description'
  }
})
// COMPUTED
const isDisabled = computed(() => {
  if(route.path === 'submissions') {
    return true
  }
  return false // TODO:: determined by the evaluation settings
  // return new Date().toLocaleDateString('en-CA', {}) >= new Date(props.evaluation?.due_date).toLocaleDateString('en-CA', {})
})
// METHODS
// WATCH
// LIFECYCLE
onBeforeMount(() => {
  if(evaluation.value?.response && !isEmpty(evaluation.value?.response)) {
    initialState.value = Object.assign(getInitialState(), unref(evaluation.value?.response))
  } else {
    initialState.value = getInitialState()
  }
})
</script>

<template>
  <EvaluationForm
      @submit="onSubmit"
      :initial-state="initialState"
      :evaluation="props.evaluation"
      v-slot="{ onSave, errors, values, isSubmitting, evaluationRef }"
  >
    <slot name="header">
      <CustomHiddenField type="hidden" name="event_id" :value="props.evaluation?.id" />
      <CustomHiddenField type="hidden" name="group_id" :value="props.evaluation?.group?.id" />
      <CustomHiddenField type="hidden" name="course_id" :value="props.evaluation?.course?.id" />
      <CustomHiddenField type="hidden" name="data[Evaluation][evaluator_id]" :value="props.currentUser?.id" />
      <CustomHiddenField type="hidden" name="evaluateeCount" :value="props.evaluation?.members?.length" />
      <CustomHiddenField type="hidden" name="memberIDs[]" v-for="(m,i) of form?.member_ids" :key="i" :value="m" />
    </slot>

    <slot name="main">
      <PeerSimpleRangeQuestion
          :members="evaluation?.members"
          :remaining="evaluation?.remaining"
          :initialState="initialState"
          :name="`points`"
          :question="questions.points.title"
          :description="questions.points.description"
          :disabled="isDisabled"
      />
      <PeerSimpleCommentQuestion
          :members="evaluation?.members"
          :initialState="initialState"
          :name="'comments'"
          :disabled="isDisabled"
          :question="questions.comments.title"
          :description="questions.comments.description"
      />
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>

  </EvaluationForm>
</template>
