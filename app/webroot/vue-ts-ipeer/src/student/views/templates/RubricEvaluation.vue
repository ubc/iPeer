<script lang="ts" setup>
import {ref, toRef, unref, reactive, watch, computed, onMounted, watchEffect} from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { useForm } from "vee-validate";
import swal from 'sweetalert'
import { find, forEach, isEmpty, map } from 'lodash'
import useFetch from '@/composables/useFetch'
import { jsonToFormData } from "@/helpers";

import UserCard from '@/student/components/UserCard.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerRubricLikertQuestion from '@/student/views/questions/PeerRubricLikertQuestion.vue'
import PeerRubricCommentQuestion from '@/student/views/questions/PeerRubricCommentQuestion.vue'
import PeerRubricGeneralCommentQuestion from '@/student/views/questions/PeerRubricGeneralCommentQuestion.vue'
import { InputText } from '@/components/fields'

import type {Evaluation, EvaluationReviewResponse, User} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'fetch:evaluation', option: object): void
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
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
const initialState = computed<EvaluationReviewResponse|any>(() => {
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
      <InputText type="hidden" name="event_id" :value="form?.event_id" />
      <InputText type="hidden" name="group_id" :value="form?.group_id" />
      <InputText type="hidden" name="group_event_id" :value="form?.group_event_id" />
      <InputText type="hidden" name="course_id" :value="form?.course_id" />
      <InputText type="hidden" name="rubric_id" :value="form?.rubric_id" />
      <InputText type="hidden" name="data[Evaluation][evaluator_id]" :value="form?.user_id" />
      <InputText type="hidden" name="evaluateeCount" :value="form?.evaluatee_count" />
      <InputText type="hidden" v-for="(m,i) of form?.member_ids" :key="i" name="memberIDs[]" :value="m" />
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


<!--

  <EvaluationForm ref="evaluation_form" :currentUser="currentUser" :evaluation="evaluation" :form="form" :data="'props.evaluation'">
    <template v-slot:header>
      <InputElement type="hidden" name="action" :value="props.action" />
      <InputElement type="hidden" name="_method" :value="props._method" />

      <input type="hidden" name="event_id" :value="params?.event_id" />
      <input type="hidden" name="group_id" :value="params?.group_id" />
      <input type="hidden" name="course_id" :value="params?.course_id" />
      <input type="hidden" name="group_event_id" :value="params?.group_event_id" />
      <input type="hidden" name="rubric_id" :value="params?.rubric_id" />
      <input type="hidden" name="data[Evaluation][evaluator_id]" :value="params?.user_id" />
      <input type="hidden" name="evaluateeCount" :value="params?.evaluatee_count" />
      <input type="hidden" name="memberIDs" :value="params?.member_ids" />
    </template>

    <template v-slot:main>
      <Debugger title="RubricEvaluationTemplate" :state="props.currentUser" :form="form.data" :data="props.evaluation" />

      <div class="datatable"
           v-for="(rubric_criteria, criteriaIdx) of props.evaluation?.review?.data?.rubrics_criteria" :key="rubric_criteria.id">
        <div v-if="rubric_criteria.criteria" class="question">{{ rubric_criteria.id }}. {{ rubric_criteria.criteria }}</div>
        <div class="description text-sm text-slate-700 mx-4 mb-2"></div>

        <PeerRubricLikertQuestion
            :members="props.evaluation?.members"
            :rubrics_lom="props.evaluation?.review?.data?.rubrics_lom"
            :rubric_criteria="rubric_criteria"
            :form="form"
            :criteriaIdx="criteriaIdx"
        />

        <PeerRubricCommentQuestion
            :members="props.evaluation?.members"
            :rubric_criteria="rubric_criteria"
            :form="form"
            :criteriaIdx="criteriaIdx"
        />
      </div>

      <PeerRubricGeneralCommentQuestion
          :members="props.evaluation?.members"
          :form="form"
          :index="props.evaluation?.review?.data?.rubrics_criteria?.length+1"
      />

    </template>

    <template v-slot:footer>
      <TakeNote />
    </template>

    <template v-slot:action="{ onSave }">
      <slot name="cta" :on-save="onSave"></slot>
    </template>

  </EvaluationForm>

-->