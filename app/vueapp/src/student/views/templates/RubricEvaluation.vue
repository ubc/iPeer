<script lang="ts" setup>
import { ref, toRef, unref, reactive, computed, onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { find, isEmpty, map } from 'lodash'

import { CustomHiddenField } from '@/components/fields'
import UserCard from '@/student/components/UserCard.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerRubricLikertQuestion from '@/student/views/questions/PeerRubricLikertQuestion.vue'
import PeerRubricCommentQuestion from '@/student/views/questions/PeerRubricCommentQuestion.vue'
import PeerRubricGeneralCommentQuestion from '@/student/views/questions/PeerRubricGeneralCommentQuestion.vue'

import type {
  IUser,
  IEvaluation,
  IRubricEvaluation,
  IRubricEvaluationData,
  IRubricEvaluationDataLom,
  IRubricEvaluationDataCriteria,
  IRubricResponse,
  IRubricResponseData,
  IRubricResponseDataDetail,
} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
  isDisabled?: boolean
}>()
const route = useRoute()
const router = useRouter()
// DATA
const evaluation_form   = ref(null)
const members           = toRef(props, 'members')
const evaluation        = toRef(props, 'evaluation')
/** TBD:: not sure if I want to have the form populating the hidden input fields values !! */
let form                = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  rubric_id: computed(() => evaluation.value?.rubric_id),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => members.value?.length),
  member_ids: computed<string[]>(() => map(members.value, member => member.id))
})
const initialState      = ref<IRubricResponse|any>({})
function getInitialState() {
  return {
    id: '',
    submitter_id: props.currentUser?.id,
    submitted: null,
    date_submitted: '',
    data: map(members.value, (member: IUser) => ({
        evaluator: props.currentUser?.id,
        evaluatee: member?.id,
        comment: '',
        score: '',
        // details: map(evaluation.value?.rubric?.data, (criteria) => { // HINT
        details: map(evaluation.value?.rubric?.data?.rubrics_criteria, (criteria) => ({
          criteria_number: criteria?.criteria_num,
          criteria_comment: '',
          selected_lom: ''
        }))
      })
    )
  }
}
function setInitialState(data: {member_id: string, criteria_num: string, event: { key: string, value: string }}): void {
  /** Dynamically update question */
  const responseData = !isEmpty(initialState.value) ? initialState.value?.data : {}
  if(responseData) {
    const criteria = find(responseData, { evaluatee: data?.member_id })
    if(criteria) {
      if(data?.event?.key === 'comment') {
        criteria.comment = data?.event?.value
      } else {
        const detail = find(criteria?.details, { criteria_number: data.criteria_num })
        if(detail) {
          detail[data.event.key] = data.event?.value
        }
      }
    }
  }
}
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
onBeforeMount( () => {
  const currentState = getInitialState()
  if(evaluation.value?.response && !isEmpty(evaluation.value.response)) {
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
      v-slot="{ onSave, errors, values, isSubmitting, evaluationRef, message, autosave }"
      @set:message="$emit('set:message', message)"
  >
    <slot name="header">
      <CustomHiddenField type="hidden" name="event_id" :value="form?.event_id" />
      <CustomHiddenField type="hidden" name="group_id" :value="form?.group_id" />
      <CustomHiddenField type="hidden" name="group_event_id" :value="form?.group_event_id" />
      <CustomHiddenField type="hidden" name="course_id" :value="form?.course_id" />
      <CustomHiddenField type="hidden" name="rubric_id" :value="form?.rubric_id" />
      <CustomHiddenField type="hidden" name="data[Evaluation][evaluator_id]" :value="form?.user_id" />
      <CustomHiddenField type="hidden" name="evaluateeCount" :value="form?.evaluatee_count" />
      <CustomHiddenField type="hidden" v-for="(m,i) of form?.member_ids" :key="i" name="memberIDs[]" :value="m" />
    </slot>

    <slot name="main">
      <div class="datatable"
           v-for="(rubric_criteria, criteriaIdx) of evaluation?.rubric?.data?.rubrics_criteria" :key="rubric_criteria.id">
        <div v-if="rubric_criteria.criteria" class="question">{{ rubric_criteria.id }}. {{ rubric_criteria.criteria }}</div>
        <div class="description text-sm text-slate-700 mx-4 mb-2"></div>

        <PeerRubricLikertQuestion
            :members="members"
            :initial-state="initialState"
            :rubric_criteria="rubric_criteria"
            :rubrics_lom="evaluation?.rubric?.data?.rubrics_lom"
            :disabled="props.isDisabled"
            :autosave="autosave"
            @update:initialState="setInitialState"
        />

        <PeerRubricCommentQuestion
            v-show="parseInt(evaluation?.com_req)"
            :members="members"
            :initial-state="initialState"
            :rubric_criteria_idx="criteriaIdx"
            :rubric_criteria="rubric_criteria"
            :disabled="props.isDisabled"
            :autosave="autosave"
            @update:initialState="setInitialState"
        />
      </div>

      <PeerRubricGeneralCommentQuestion
          :index="evaluation?.rubric?.data?.rubrics_criteria?.length+1"
          :members="members"
          :initial-state="initialState"
          :rubric_criteria_idx="criteriaIdx"
          :disabled="props.isDisabled"
          @update:initialState="setInitialState"
      />
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>

  </EvaluationForm>
</template>
