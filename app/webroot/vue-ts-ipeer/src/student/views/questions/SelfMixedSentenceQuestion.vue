<script lang="ts" setup>
import { toRef } from 'vue'
import { validateSentence } from '@/helpers/rules'
import { CustomInputField } from '@/components/fields'
import {find, isEmpty} from "lodash";
// REFERENCES
const emit = defineEmits<{
  (e: 'update:form', value: object): void
}>()
const props = defineProps<{
  question: object,
  currentUser: object,
  initialState: object,
}>()
// DATA
const question = toRef(props, 'question')
const currentUser = toRef(props, 'currentUser')
const initialState = toRef(props, 'initialState')
// COMPUTED
// METHODS
function getResponseDetails(member_id:string, question_num:string): string {
  if(initialState.value?.data || !isEmpty(initialState.value?.data)) {
    const response = find(initialState.value?.data, { evaluatee: member_id })
    if(response?.details) {
      const question = find(response.details, { question_number: question_num })
      return question?.question_comment
    }
  }
  return ''
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div :class="`datatable question_${question.question_num} mx-4`">
    <div class="question">{{ question.question_num }}. {{ question.title }} <span class="text-red-500" v-if="question.required">*</span></div>
    <div class="description text-sm text-slate-900 leading-relaxed mx-4 mb-2">{{ question.instructions }}</div>
    <div class="mx-4">
      <!-- TODO:: fire the ['update:initialState'] every 300~500 ms -->
      <CustomInputField
        :name="`data[${currentUser.id}][EvaluationMixeval][${question.question_num}][question_comment]`"
        :value="getResponseDetails(currentUser.id, question.question_num)"
        :rules="question.required ? validateSentence : null"
        @input="$emit('update:initialState', {
          member_id: currentUser.id,
          question_num: question.question_num,
          event: {
            key: 'question_comment',
            value: $event.target.value
          }
        })"
      />
    </div>
  </div>
</template>
