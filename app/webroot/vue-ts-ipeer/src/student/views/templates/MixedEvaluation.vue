<script lang="ts" setup>
import { ref, unref, toRef, reactive, watch, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router'
import { isEmpty, map } from 'lodash'

import TakeNote from '@/student/components/TakeNote.vue'
import EvaluationForm from '@/student/views/EvaluationForm.vue'
import {Evaluation, EvaluationReviewResponse, User} from "@/types/typings";

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

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <EvaluationForm>
    <div class="">__MixedEvaluationTemplate__</div>

    <slot name="header"></slot>

    <slot name="main">


      <div class="question">Q01. </div>
    </slot>

    <slot name="footer"></slot>

    <slot name="cta" :on-save="onSave" :is-submitting="isSubmitting" :values="values"></slot>
  </EvaluationForm>
</template>
