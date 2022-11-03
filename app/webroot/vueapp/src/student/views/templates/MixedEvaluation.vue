<script lang="ts" setup>
import {ref, unref, toRef, reactive, computed, onBeforeMount} from 'vue';
import { useRoute, useRouter } from 'vue-router'
import {isEmpty, map, find, filter, findIndex, merge} from 'lodash'

import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerQuestions from '@/student/views/templates/renderer/MixedEvaluationPeerQuestions.vue'
import SelfQuestions from '@/student/views/templates/renderer/MixedEvaluationSelfQuestions.vue'
import CustomHiddenField from "@/components/fields/CustomHiddenField.vue";
import { IconThoughtBubble } from '@/components/icons'
import TakeNote from '@/student/components/TakeNote.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'

import type { IUser, IEvaluation, IMixedResponseData, IMixedResponseDataDetail } from '@/types/typings'

// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
}>()
const route = useRoute()
const router = useRouter()
// DATA
const evaluation_form   = ref(null)
const members           = toRef(props, 'members')
const evaluation        = toRef(props, 'evaluation')
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
const initialState      = ref<IMixedResponseData|any>({})
// COMPUTED
const peer_questions = computed(() => filter(evaluation.value?.mixed?.data, { self_eval: false }))
const self_questions = computed(() => filter(evaluation.value?.mixed?.data, { self_eval: true }))
// METHODS
function getInitialState() {
  return {
    id: '',
    submitter_id: evaluation.value?.id,
    submitted: null,
    date_submitted: '',
    data: map(props.evaluation?.members, (member: IUser) => ({
        evaluator: props.currentUser?.id,
        evaluatee: member.id,
        score: '',
        comment_release: '',
        grade_release: '',
        details: map(evaluation.value?.mixed?.data, (question: IMixedEvaluationData) => ({
          evaluation_mixeval_id: '',
          question_number: question?.question_num,
          question_comment: null,
          selected_lom: null,
          grade: '',
          comment_release: '0',
          record_status: 'A',
        }))
      }))
  }
}
function setInitialState(data: {member_id: string, question_num: string, event: { key: string, value: string }}): void {
  /** Dynamically update question state */
  const mixed = evaluation.value?.mixed
  if(mixed) {
    const question = find(mixed?.data, { question_num: data?.question_num })
    if(question) {
      const response = !isEmpty(initialState.value) ? initialState.value?.data : {}
      if(response) {
        const details = find(response, { evaluatee: data.member_id}).details
        const detail = !isEmpty(details) ? find(details, { question_number: data.question_num }) : {}
        switch (data?.event?.key) {
          case 'selected_lom':
            const precision = Math.pow(10, 1)
            const grade = Number(question?.multiplier) / (question?.loms?.length - Number(mixed?.zero_mark)) * Number(data.event.value)
            merge(detail, {'selected_lom': data.event.value, grade: Math.floor(grade * precision) / precision})
            break
          case 'question_comment':
            merge(detail, {'question_comment': data.event.value})
            break
          default:
            break
        }
      }
    }
  }
}
// WATCH
// LIFECYCLE
onBeforeMount(() => {
  const currentState = getInitialState()
  if(evaluation.value?.response && !isEmpty(evaluation.value?.response)) {
    initialState.value = merge(currentState, unref(evaluation.value?.response))
  } else {
    initialState.value = currentState
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
      <template v-if="findIndex(evaluation?.mixed?.data, q => q.type === 'Likert') !== -1">
        <template v-for="member of evaluation?.members" :key="member.id">
          <CustomHiddenField :name="`data[${member.id}][Evaluation][evaluatee_id]`" :value="member.id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][evaluator_id]`" :value="form.user_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][event_id]`" :value="form.event_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][group_event_id]`" :value="form.group_event_id" />
          <CustomHiddenField :name="`data[${member.id}][Evaluation][group_id]`" :value="form.group_id" />
        </template>
      </template>
      <template v-if="parseInt(evaluation?.self_eval) && parseInt(evaluation?.mixed?.self_eval) > 0">
        <CustomHiddenField :name="`data[${form.user_id}][Self-Evaluation][evaluatee_id]`" :value="form.user_id" />
        <CustomHiddenField :name="`data[${form.user_id}][Self-Evaluation][evaluator_id]`" :value="form.user_id" />
        <CustomHiddenField :name="`data[${form.user_id}][Self-Evaluation][event_id]`" :value="form.event_id" />
        <CustomHiddenField :name="`data[${form.user_id}][Self-Evaluation][group_event_id]`" :value="form.group_event_id" />
        <CustomHiddenField :name="`data[${form.user_id}][Self-Evaluation][group_id]`" :value="form.group_id" />
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
          v-if="parseInt(evaluation?.self_eval) && parseInt(evaluation?.mixed?.self_eval) > 0"
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
