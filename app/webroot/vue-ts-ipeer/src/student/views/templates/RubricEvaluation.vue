<script lang="ts" setup>
import {ref, toRef, unref, reactive, computed,} from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map } from 'lodash'

import { CustomHiddenField } from '@/components/fields'
import UserCard from '@/student/components/UserCard.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerRubricLikertQuestion from '@/student/views/questions/PeerRubricLikertQuestion.vue'
import PeerRubricCommentQuestion from '@/student/views/questions/PeerRubricCommentQuestion.vue'
import PeerRubricGeneralCommentQuestion from '@/student/views/questions/PeerRubricGeneralCommentQuestion.vue'

import type {Evaluation, RubricResponse, User} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'fetch:evaluation', option: object): void
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
const evaluation  = toRef(props, 'evaluation')
/** NOTE:: not sure if I want to have the form populating the hidden input fields values !! */
let form          = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  rubric_id: computed(() => evaluation.value?.rubric_id),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => evaluation.value?.members?.length),
  member_ids: computed<string[]>(() => map(evaluation.value?.members, member => member.id))
})
const initialState = computed<RubricResponse|any>(() => {
  if(evaluation.value?.review?.response && !isEmpty(evaluation.value?.review?.response)) {
    return unref(evaluation.value?.review?.response)
  } else {
    return {data: setInitialState()}
  }
})

function getInitialState() {}
function setInitialState() {
  return map(props.evaluation?.members, (member, memberIdx) => {
    return {
      evaluator: props.currentUser.id,
      evaluatee: member.id,
      comment: '',
      score: '',
      details: map(evaluation.value?.review?.data?.rubrics_criteria, (criteria, criteriaIdx) => {
        return {
          criteria_number: criteria.criteria_num,
          criteria_comment: '',
          selected_lom: ''
        }
      })
    }
  })
}
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <EvaluationForm
      @submit="onSubmit"
      :initial-state="initialState"
      :evaluation="props.evaluation"
      v-slot="{ onSave, errors, values, isSubmitting, evaluationRef }"
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
           v-for="(rubric_criteria, criteriaIdx) of props.evaluation?.review?.data?.rubrics_criteria" :key="rubric_criteria.id">
        <div v-if="rubric_criteria.criteria" class="question">{{ rubric_criteria.id }}. {{ rubric_criteria.criteria }}</div>
        <div class="description text-sm text-slate-700 mx-4 mb-2"></div>

        <PeerRubricLikertQuestion
            :members="props.evaluation?.members"
            :initial-state="initialState"
            :rubric_criteria="rubric_criteria"
            :rubrics_lom="props.evaluation?.review?.data?.rubrics_lom"
        />

        <PeerRubricCommentQuestion
            v-show="parseInt(props.evaluation?.com_req)"
            :members="props.evaluation?.members"
            :initial-state="initialState"
            :rubric_criteria_idx="criteriaIdx"
            :rubric_criteria="rubric_criteria"
        />
      </div>

      <PeerRubricGeneralCommentQuestion
          :index="props.evaluation?.review?.data?.rubrics_criteria?.length+1"
          :members="props.evaluation?.members"
          :initial-state="initialState"
          :rubric_criteria_idx="criteriaIdx"
      />
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>

  </EvaluationForm>
</template>
