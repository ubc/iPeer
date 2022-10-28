<script lang="ts" setup>
import {ref, unref, toRef, reactive, watchEffect, computed, onMounted, defineAsyncComponent, onBeforeMount} from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map, find, filter, findIndex } from 'lodash'
import { array, object, string, number } from 'yup'

import CustomHiddenField from "@/components/fields/CustomHiddenField.vue";
import { IconThoughtBubble } from '@/components/icons'
import TakeNote from '@/student/components/TakeNote.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerQuestions from '@/student/views/templates/renderer/MixedEvaluationPeerQuestions.vue'
import SelfQuestions from '@/student/views/templates/renderer/MixedEvaluationSelfQuestions.vue'

import type { User, Evaluation, MixedReviewData, MixedResponse } from '@/types/typings'

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
const members           = toRef(props, 'members')
const evaluation        = toRef(props, 'evaluation')
const evaluation_form   = ref(null)
const form              = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  template_id: computed(() => evaluation.value?.template_id),
  member_count: computed(() => evaluation.value?.members?.length),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => evaluation.value?.members?.length),
  member_ids: computed<string[]>(() => map(evaluation.value?.members, member => member.id))
})
const initialState      = ref({})
// COMPUTED
const peer_questions = computed(() => filter(evaluation.value?.review?.data, { self_eval: false }))
const self_questions = computed(() => filter(evaluation.value?.review?.data, { self_eval: true }))
// METHODS
function getInitialState() {
  return map(props.evaluation?.members, (member: User) => {
    return {
      evaluator: props.currentUser.id,
      evaluatee: member.id,
      score: '',
      comment_release: '',
      grade_release: '',
      details: map(evaluation.value?.review?.data, (response: MixedReviewData) => {
        if(response?.self_eval === false) {
          return {
            evaluation_mixeval_id: '',
            question_number: response?.question_num,
            question_comment: null,
            selected_lom: null,
            grade: '',
            comment_release: '0',
            record_status: 'A',
          }
        }
      }).filter(x=>x)
    }
  })
}
/** Dynamically update question values based on keys */
function setInitialState(data: {member_id: string, question_num: string, event: { key: string, value: string }}): void {
  const review = evaluation.value?.review
  const question = find(review?.data, { question_num: data.question_num })
  const response = !isEmpty(initialState.value) ? initialState.value?.data : {}

  const details = find(response, { evaluatee: data.member_id}).details
  const detail = find(details, { question_number: data.question_num })

  const precision = Math.pow(10, 1)
  const grade = parseInt(question?.multiplier) / (question?.loms.length - parseInt(review?.zero_mark)) * parseInt(data.event.value)

  if(data.event.key === 'selected_lom') {
    Object.assign(detail, {
      [data.event.key]: data.event.value,
      grade: Math.floor(grade * precision) / precision,
    })
  } else if(data.event.key === 'question_comment') {
    Object.assign(detail, {
      [data.event.key]: data.event.value
    })
  } else return
  // console.log(JSON.stringify(detail, null, 2))
}
function __setInitialState(event: {member_id: string, question_num: string, value: string}): void {
  const review = evaluation.value?.review
  const question = find(review?.data, { question_num: event.question_num })
  const response = initialState.value?.data

  const details = find(response, { evaluatee: event.member_id}).details
  const detail = find(details, { question_number: event.question_num })

  const precision = Math.pow(10, 1)
  const grade = parseInt(question?.multiplier) / (question?.loms.length - parseInt(review?.zero_mark)) * parseInt(event.value)

  Object.assign(detail, {
    selected_lom: event.value,
    grade: Math.floor(grade * precision) / precision,
  })
  console.log(JSON.stringify(detail, null, 2))
}
// WATCH
// LIFECYCLE
onBeforeMount(() => {
  if(evaluation.value?.response && !isEmpty(evaluation.value?.response)) {
    initialState.value = unref(evaluation.value?.response)
  } else {
    initialState.value = {
      submitter_id: props.currentUser.id,
      submitted: null,
      date_submitted: '',
      data: getInitialState()
    }
  }
})
</script>

<template>
  <EvaluationForm
      @submit="onSubmit"
      :initial-state="initialState"
      :evaluation="props.evaluation"
      v-slot="{ onSave, errors, values, isSubmitting, evaluationRef, formMeta }"
  >
    <slot name="header">
      <CustomHiddenField name="data[data][submitter_id]" :value="form.user_id" />
      <CustomHiddenField name="data[data][event_id]" :value="form.event_id" />
      <CustomHiddenField name="data[data][template_id]" :value="form.template_id" />
      <CustomHiddenField name="data[data][grp_event_id]" :value="form.group_event_id" />
      <CustomHiddenField name="data[data][members]" :value="form.member_count" />
      <template v-if="findIndex(evaluation?.review?.data, q => q.type === 'Likert') !== -1">
        <template v-for="member of evaluation?.members" :key="member.id">
          <CustomHiddenField :name="`data[${member.id}][Evaluation][evaluatee_id]`" :value="member.id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][evaluator_id]`" :value="form.user_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][event_id]`" :value="form.event_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][group_event_id]`" :value="form.group_event_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][group_id]`" :value="form.group_id" />
        </template>
      </template>
      <template v-if="parseInt(evaluation?.self_eval) && parseInt(evaluation?.review?.self_eval) > 0">
        <CustomHiddenField :name="`data[${form.user_id}][SelfEvaluation][evaluatee_id]`" :value="form.user_id" />
        <CustomHiddenField :name="`data[${form.user_id}][SelfEvaluation][evaluator_id]`" :value="form.user_id" />
        <CustomHiddenField :name="`data[${form.user_id}][SelfEvaluation][event_id]`" :value="form.event_id" />
        <CustomHiddenField :name="`data[${form.user_id}][SelfEvaluation][group_event_id]`" :value="form.group_event_id" />
        <CustomHiddenField :name="`data[${form.user_id}][SelfEvaluation][group_id]`" :value="form.group_id" />
      </template>
    </slot>

    <slot name="main">
      <PeerQuestions
          v-for="question of peer_questions" :key="question.id"
          :members="members"
          :question="question"
          :evaluation="evaluation"
          :initial-state="initialState"
          :current-user="props.currentUser"
          @update:initialState="setInitialState"
      />

      <SectionSubtitle
          v-if="parseInt(evaluation?.self_eval) && parseInt(evaluation?.review?.self_eval) > 0"
          subtitle="Evaluate yourself"
          :icon="{src: IconThoughtBubble, size: '3rem'}"
      >
        <div class="self-evaluation my-8 space-y-8">
          <SelfQuestions
              v-for="(question, idx) of self_questions" :key="question.id"
              :idx="idx"
              :question="question"
              :evaluation="evaluation"
              :initial-state="initialState"
              :current-user="props.currentUser"
              @update:initialState="setInitialState"
          />
        </div>
      </SectionSubtitle>
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>
  </EvaluationForm>
</template>
