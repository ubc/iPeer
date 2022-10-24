<script lang="ts" setup>
import { ref, unref, toRef, reactive, watch, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map } from 'lodash'

import TakeNote from '@/student/components/TakeNote.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerSimpleRangeQuestion from '@/student/views/questions/PeerSimpleRangeQuestion.vue'
import PeerSimpleCommentQuestion from '@/student/views/questions/PeerSimpleCommentQuestion.vue'
import { InputText } from '@/components/fields'

import type { Evaluation, EvaluationReviewResponse, User } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
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
const evaluation    = toRef(props, 'evaluation')
let form            = reactive({
  event_id: computed(() => evaluation.value?.id),
  group_id: computed(() => evaluation.value?.group?.id),
  course_id: computed(() => evaluation.value?.course?.id),
  group_event_id: computed(() => evaluation.value?.group_event_id),
  rubric_id: computed(() => evaluation.value?.rubric_id),
  user_id: computed(() => props.currentUser?.id),
  evaluatee_count: computed(() => evaluation.value?.members?.length),
  member_ids: computed<string[]>(() => map(evaluation.value?.members, member => member.id))
})
const initialState  = computed<EvaluationReviewResponse | any>(() => {
  if(evaluation.value?.review?.response && !isEmpty(evaluation.value?.review?.response)) {
    return unref(evaluation.value?.review?.response)
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
})
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
  return false // will be determined by the evaluation settings
  // return new Date().toLocaleDateString('en-CA', {}) >= new Date(props.evaluation?.due_date).toLocaleDateString('en-CA', {})
})
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
      <InputText type="hidden" name="event_id" :value="props.evaluation?.id" />
      <InputText type="hidden" name="group_id" :value="props.evaluation?.group?.id" />
      <InputText type="hidden" name="course_id" :value="props.evaluation?.course?.id" />
      <InputText type="hidden" name="data[Evaluation][evaluator_id]" :value="props.currentUser?.id" />
      <InputText type="hidden" name="evaluateeCount" :value="props.evaluation?.members?.length" />
      <InputText type="hidden" name="memberIDs[]" v-for="(m,i) of form?.member_ids" :key="i" :value="m" />
    </slot>

    <slot name="main">
      <!---->
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

<!--
  <EvaluationForm ref="evaluation_form" :currentUser="currentUser" :evaluation="evaluation" :form="form">
    <template v-slot:header>
      <InputElement type="hidden" name="action" :value="props.action" />
      <InputElement type="hidden" name="_method" :value="props._method" />

      <InputElement type="hidden" name="event_id" :value="params?.event_id" />
      <InputElement type="hidden" name="group_id" :value="params?.group_id" />
      <InputElement type="hidden" name="course_id" :value="params?.course_id" />
      <InputElement type="hidden" name="data[Evaluation][evaluator_id]" :value="params?.user_id" />
      <InputElement type="hidden" name="evaluateeCount" :value="params?.evaluatee_count" />
      <InputElement type="hidden" name="memberIDs" :value="params?.member_ids" />
    </template>

    < !--<template v-slot:main="{ params, form }">-- >
    <template v-slot:main>

      <PeerSimpleRangeQuestion
          :members="props.evaluation?.members"
          :form="values"
          :name="`points`"
          question="1. Please rate each peer's relative contribution."
          description="just a points section description"
          :disabled="isDisabled"
      />

      <div class="temp text-left">
        <div class="text-xs text-blue-500">remaining: {{ props.evaluation?.review?.remaining }}</div>
        <div class="text-xs text-blue-500">form.points: {{ form.points }}</div>
        <div class="text-xs text-blue-500">values.points: {{ values.points }}</div>
      </div>


      <PeerSimpleCommentQuestion
          :members="props.evaluation?.members"
          :form="form"
          :name="`comments`"
          question="2. Please provide overall comments about each peer."
          description="just a comments section description"
          :disabled="isDisabled"
      />
      <div class="text-xs text-left text-blue-500 py-2 px-4" v-for="(comment, idx) of values.comments" :key="idx">{{ comment }}</div>

    </template>

    <template v-slot:footer>
      <TakeNote />
    </template>

    <template v-slot:action="{ onSave }">
      <slot name="cta" :on-save="onSave"></slot>
    </template>

  </EvaluationForm>
</template>





< !--<template>-- >
< !--  <div class="simple-valuation-template">-- >
    < !--
    <EvaluationForm
        :params="params"
        :form="form"
        :currentUser="props.currentUser"
        :evaluation="props.evaluation"
    >-- >

  < !--
  <PeerRangeQuestion
      question="1. Please rate each peer's relative contribution."
      title="Relative Contribution"
      :members="props.evaluation?.members"
      :name="''"
      :form="form"
      :remaining="props.evaluation?.review?.remaining"
      :min="0"
      :max="100"
      :disabled="isDisabled"
      />-- >

    < !--
    </EvaluationForm>-- >
< !--  </div>-- >
</template>-->