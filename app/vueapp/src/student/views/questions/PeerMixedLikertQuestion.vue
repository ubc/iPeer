<script lang="ts" setup>
import { ref, toRef, reactive, watch, computed, onMounted } from 'vue'
import { isEmpty, find } from 'lodash'
import { validateLikert } from '@/helpers/rules'
import UserCard from '@/student/components/UserCard.vue'
import CustomHiddenField from '@/components/fields/CustomHiddenField.vue'
import CustomRadioField from '@/components/fields/CustomRadioField.vue'
import type { IUser, IEvaluation, IMixedResponse, IMixedReviewData } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', value: object): void
}>()
const props = defineProps<{
  members: IUser[],
  evaluation: IEvaluation
  question: IMixedReviewData,
  initialState: IMixedResponse,
  autosave?: object
}>()
// DATA
const members = toRef(props, 'members')
const question = toRef(props, 'question')
const initialState = toRef(props, 'initialState')
// COMPUTED
// METHODS
function calcGradeAndRoundUp(multiplier: string, loms_count: number, zero_mark: string, scale_level: string) {
  const precision = Math.pow(10, 1)
  const score = parseInt(multiplier) / (loms_count - parseInt(zero_mark)) * parseInt(scale_level)
  const grade = Math.floor(score * precision) / precision
  return `(${grade} mark${grade > 1 ? 's' : ''})`
}
// checked: find(find(props.initialState?.data, { evaluatee: member.id }).details, { question_number: question.question_num}).selected_lom
function getResponseDetails(member_id:string, question_num:string): string|number {
  if(initialState.value?.data || !isEmpty(initialState.value?.data)) {
    const response = find(initialState.value?.data, { evaluatee: member_id })
    if(response?.details) {
      const question = find(response.details, { question_number: question_num })
      return question ? question?.selected_lom : ''
    }
  }
  return 0
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
        <th :style="'width: '+ 80/props.question?.loms.length +'%; text-align: center'" v-for="(lom, lomIdx) of props.question?.loms" :key="lom.id">
          <div class="text-center leading-4">
            <div class="font-normal">{{ lom.descriptor }}</div>
            <div class="text-sm font-thin" v-if="parseInt(props.question?.show_marks)">
              {{ calcGradeAndRoundUp(question?.multiplier, question?.loms.length, evaluation?.mixed?.zero_mark, lom?.scale_level) }}
            </div>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of members" :key="member.id">
        <CustomHiddenField
            :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
            :value="getResponseDetails(member.id, question.question_num)" />
        <td><UserCard :member="member" /></td>
        <td style="text-align: center" v-for="lom of question.loms" :key="lom.id" :class="{'has-error': !!error}">
          <CustomRadioField
              :name="'data['+member.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
              :value="lom.scale_level"
              :rules="question.required ? validateLikert : null"
              :checked="getResponseDetails(member.id, question.question_num)"
              @change="$emit('update:initialState', {
                member_id: member.id,
                question_num: question.question_num,
                event: {
                  key: 'selected_lom',
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
