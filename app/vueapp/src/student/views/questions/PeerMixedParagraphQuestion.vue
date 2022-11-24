<script lang="ts" setup>
import { ref, toRef } from 'vue'
import { isEmpty, find } from 'lodash'
import { validateParagraph } from '@/helpers/rules'
import CustomTextField from '@/components/fields/CustomTextField.vue'
import UserCard from '@/student/components/UserCard.vue'
import type { IMixedResponse, IMixedReviewData, IUser } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', value: object): void
}>()
const props = defineProps<{
  question: IMixedReviewData,
  members: IUser[],
  initialState: IMixedResponse,
}>()

// DATA
const members = toRef(props, 'members')
const question = toRef(props, 'question')
const initialState = toRef(props, 'initialState')
// COMPUTED
// METHODS
function getResponseDetails(member_id:string, question_num:string): string {
  if(initialState.value?.data || !isEmpty(initialState.value?.data)) {
    const response = find(initialState.value?.data, { evaluatee: member_id })
    if(response?.details) {
      const question = find(response?.details, { question_number: question_num })
      return question?.question_comment
    }
  }
  return ''
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div :class="`datatable question_${question.question_num}`">
    <div class="question">{{ question.question_num }}. {{ question.title }} <span class="text-red-500" v-if="question.required">*</span></div>
    <div class="description">{{ question.instructions }}</div>
    <table class="standardtable center no-v-line">
      <thead>
      <tr>
        <th style="width: 20%">
          <div class="flex flex-col">
            <div class="">Peer</div>
            <small class="small"></small>
          </div>
        </th>
        <th style="width: 80%;">
          <div class="flex flex-col">
            <div class="">Comments</div>
            <small class="small"></small>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="member of members" :key="member.id">
        <td><UserCard :member="member" /></td>
        <td>
          <CustomTextField
            :name="`data[${member.id}][EvaluationMixeval][${question.question_num}][question_comment]`"
            :value="getResponseDetails(member.id, question.question_num)"
            :rules="question.required ? validateParagraph : null"
            @input="$emit('update:initialState', {
              member_id: member.id,
              question_num: question.question_num,
              event: {
                key: 'question_comment',
                value: $event.target.value
              }
            })"
          />
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
