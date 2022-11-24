<script lang="ts" setup>
import { ref, toRef } from 'vue'
import { validateLikert } from '@/helpers/rules'
import { CustomHiddenField, CustomRadioField } from '@/components/fields'
import type { IEvaluation, IUser } from '@/types/typings'
import {find, isEmpty} from "lodash";
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', value: object): void
}>()
const props = defineProps<{
  evaluation: IEvaluation,
  question: object,
  currentUser: IUser,
  initialState: object,
}>()
// DATA
const question      = toRef(props, 'question')
const currentUser   = toRef(props, 'currentUser')
const initialState  = toRef(props, 'initialState')
// COMPUTED
// METHODS
function gradeRoundUp(num: number, precision: number) {
  precision = Math.pow(10, precision)
  return Math.floor(num * precision) / precision
}
function getResponseDetails(member_id:string, question_num:string): string|number {
  if(initialState.value?.data || !isEmpty(initialState.value?.data)) {
    const response = find(initialState.value?.data, { evaluatee: member_id })
    if(response?.details) {
      const question = find(response.details, { question_number: question_num })
      return question?.selected_lom
    }
  }
  return 0
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div :class="`question_${question?.question_num}`">
    <div class="question">{{ question?.question_num }}. {{ question?.title }} <span class="text-red-500" v-if="question?.required">*</span></div>
    <div class="description">{{ question?.instructions }}</div>
    <div class="mx-4">
      <table class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th :style="'width: '+ 100/question?.loms?.length +'%; text-align: center'" v-for="(lom) of question?.loms" :key="lom.id">
            <div class="text-center leading-4">
              <div class="font-normal">{{ lom.descriptor }}</div>
              <div class="text-sm font-thin" v-if="parseInt(question?.show_marks)">mark</div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <CustomHiddenField
              :name="'data['+currentUser.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'"
              :value="getResponseDetails(currentUser.id, question.question_num)" />
          <td style="text-align: center; height: 36px;" v-for="(lom) of question.loms" :key="lom.id">
            <CustomRadioField
              :name="'data['+currentUser.id+'][EvaluationMixeval]['+question.question_num+'][grade]'"
              :value="lom.scale_level"
              :rules="question.required ? validateLikert : null"
              :checked="getResponseDetails(currentUser.id, question.question_num)"
              @change="$emit('update:initialState', {
                member_id: currentUser.id,
                question_num: question.question_num,
                event: {
                  key: 'selected_lom',
                  value: $event.target.value
                }
              })"
            />
            <!-- TBD:: Check if the grade conversion (to percentages) will be handled on the server
            <CustomRadioField
              :value="gradeRoundUp(parseInt(question?.multiplier) / (question?.loms.length - parseInt(evaluation?.review?.zero_mark)) * parseInt(lom?.scale_level), 1)"
            />-->
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
