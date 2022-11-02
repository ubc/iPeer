<script lang="ts" setup>
import { ref, toRef, reactive, watch, computed, onMounted } from 'vue'
import { isEmpty, find } from 'lodash'
import { FieldArray } from 'vee-validate'
import { validateSentence } from '@/helpers/rules'
import CustomInputField from '@/components/fields/CustomInputField.vue'
import UserCard from '@/student/components/UserCard.vue'
import type {MixedResponse, MixedReviewData, User} from "@/types/typings";
// REFERENCES
const emit = defineEmits<{
  (e: 'update:form', value: object): void
}>()
const props = defineProps<{
  question: MixedReviewData,
  members: User[],
  initialState: MixedResponse,
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
    <table class="standardtable leftalignedtable">
      <thead>
      <tr>
        <th style="width: 20%">
          <div class="text-center leading-4">
            <div class="font-normal">Peer</div>
            <div class="text-sm font-thin"></div>
          </div>
        </th>
        <th style="width: 80%;">
          <div class="text-center leading-4">
            <div class="font-normal">Comments</div>
            <div class="text-sm font-thin"></div>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="member of members" :key="member.id">
        <td><UserCard :member="member" /></td>
        <td>
          <!-- TODO:: fire the ['update:initialState'] every 300~500 ms -->
          <CustomInputField
            :name="`data[${member.id}][EvaluationMixeval][${question.question_num}][question_comment]`"
            :value="getResponseDetails(member.id, question.question_num)"
            :rules="question.required ? validateSentence : null"
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
