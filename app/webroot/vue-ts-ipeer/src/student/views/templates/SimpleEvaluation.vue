<script lang="ts" setup>
import { ref, unref, toRef, reactive, watch, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router'
import { useForm } from 'vee-validate'
import { map } from 'lodash'

import EvaluationForm from '@/student/views/EvaluationForm.vue'
import PeerSimpleRangeQuestion from '@/student/views/questions/PeerSimpleRangeQuestion.vue'
import PeerSimpleCommentQuestion from '@/student/views/questions/PeerSimpleCommentQuestion.vue'


// import PeerRangeQuestion from "@/student/views/questions/PeerRangeQuestion.vue";
// import PeerCommentQuestion from "@/student/views/questions/PeerCommentQuestion.vue";
import InputElement from '@/components/fields/InputElement.vue'
import VFormInput from '@/components/fields/VFormInput.vue'
import VFormText from '@/components/fields/VFormText.vue'
import TakeNote from "@/student/components/TakeNote.vue";


import type { Evaluation, User } from '@/types/typings'
interface SimpleEvaluation {
  id: string
  submitter_id: string
  submitted: string
  date_submitted: string
  points: string[]
  comments: string[]
}
interface Form {
  points: string[]
  comments: string[]
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
  // (e: 'update:comments', option: string): void
}>()
const props = defineProps<{
  action: string
  _method: string
  currentUser: User
  evaluation: Evaluation
}>()
const route = useRoute()

// DATA
const settings = reactive({
  questions: [
    {
      id: '1',
      title: 'Please rate each peer\'s relative contribution.',
      description: 'points:: some description'
    },
    {
      id: '2',
      title: 'Please provide overall comments about each peer.',
      description: 'comments:: some description'
    }
  ]
})
const evaluation = toRef<any>(props, 'evaluation')
const params = reactive<any>({
  event_id: computed<string | number>(() => evaluation.value?.id),
  group_id: computed<string | number>(() => evaluation.value?.group?.id),
  course_id: computed<string | number>(() => evaluation.value?.course?.id),
  user_id: computed<string | number>(() => props.currentUser?.id),
  evaluatee_count: computed<string | number>(() => evaluation.value?.members?.length),
  member_ids: computed<string | number>(() => map(evaluation.value?.members, member => member.id)),
})
const form2 = computed<SimpleEvaluation>(() => {
  if(evaluation.value?.review?.response.length) {
    return evaluation.value?.review?.response
  } else {
    return {
      points: [],
      comments: []
    }
  }
})

const form = computed<SimpleEvaluation>(() => {
  if(evaluation.value?.review?.response && Object.keys(evaluation.value?.review?.response).length) {
    return unref(props.evaluation?.review?.response)
  }
  return {
    points: [],
    comments: []
  }
})

// const form = toRef<any>(props, 'response')
const { handleSubmit, errors, values } = useForm({
  initialValues: form.value
})

// COMPUTED
const response = computed(() => props.evaluation?.review?.response)

const isDisabled = computed(() => {
  if(route.path === 'submissions') {
    return true
  }
  return false
  // return new Date().toLocaleDateString('en-CA', {}) >= new Date(props.evaluation?.due_date).toLocaleDateString('en-CA', {})
})
// METHODS

// function onInvalidSubmit({ values, errors, results }: any) {
//   console.log(values); // current form values
//   console.log(errors); // a map of field names and their first error message
//   console.log(results); // a detailed map of field names and their validation results
// }

// const onChange = handleChange(values => {
//   alert(JSON.stringify(values, null, 2))
// })



// function updateSliderPoints({target, key, value}) {
//   // console.log(student_scores.value, target, key, value)
//
//   student_slider.value[key] = parseInt(value)
//   slider_sum.value += value - student_slider.value[key]
//
//   // student_scores.value.push(value)
// }



// WATCH

// LIFECYCLE

</script>

<template>
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

    <!--<template v-slot:main="{ params, form }">-->
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
<!--        <div class="text-xs text-red-500">total_points: {{ total_points }}</div>-->
<!--        <div class="text-xs text-red-500">slider_sum: {{ slider_sum }}</div>-->
<!--        <div class="text-xs text-red-500">student_slider: {{ student_slider }}</div>-->
<!--        <div class="text-xs text-red-500">student_scores: {{ student_scores }}</div>-->
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





<!--<template>-->
<!--  <div class="simple-valuation-template">-->
    <!--
    <EvaluationForm
        :params="params"
        :form="form"
        :currentUser="props.currentUser"
        :evaluation="props.evaluation"
    >-->
<!--      // PARAMS GOES HERE-->
<!--      <pre class="text-xs text-gray-500">&#45;&#45;{{ form }}&#45;&#45;</pre>-->
  <!--
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
      />-->

    <!--
    </EvaluationForm>-->
<!--  </div>-->
<!--</template>-->
