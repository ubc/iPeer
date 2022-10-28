<script lang="ts" setup>
import { ref, toRef } from 'vue'
import { validateLikert } from '@/helpers/rules'
import { CustomRadioField } from '@/components/fields'
import type { Evaluation, User } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:form', value: object): void
}>()
const props = defineProps<{
  evaluation: Evaluation,
  question: object,
  currentUser: User,
  initialState: object,
}>()
// DATA
const question = toRef(props, 'question')
const currentUser = toRef(props, 'currentUser')
const initialState = toRef(props, 'initialState')
const errorMessage = ref(true)
// COMPUTED
// METHODS
function gradeRoundUp(num: number, precision: number) {
  precision = Math.pow(10, precision)
  return Math.floor(num * precision) / precision
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div :class="`question_${question.question_num} mx-4`">
    <div class="question">{{ question.question_num }}. {{ question.title }} <span class="text-red-500" v-if="question.required">*</span></div>
    <div class="description text-sm text-slate-900 leading-relaxed mx-4 mb-2">{{ question.instructions }}</div>
    <div class="mx-4">
      <table class="standardtable leftalignedtable">
        <thead>
        <tr>
          <th :style="'width: '+ 100/question.loms.length +'%; text-align: center'"
              v-for="(lom, lomIdx) of question.loms" :key="lom.id">
            <div class="font-normal">{{ lom.descriptor }}</div>
            <!--<small v-if="parseInt(question.show_marks)" class="text-sm font-light">
              ({{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) }} mark{{ gradeRoundUp((question.multiplier/question.scale_level)*lom.scale_level, 1) > 1 ? 's' : '' }})
            </small>-->
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <input type="hidden" :name="'data['+currentUser.id+'][EvaluationMixeval]['+question.question_num+'][selected_lom]'" value="4">
          <td style="text-align: center" v-for="(lom, lomIdx) of question.loms" :key="lom.id" :class="{ 'has-error': !!errorMessage }">
            <CustomRadioField
              :name="`data[${currentUser.id}][EvaluationMixeval][${question.question_num}][grade]`"
              :value="gradeRoundUp(parseInt(question?.multiplier) / (question?.loms.length - parseInt(evaluation?.review?.zero_mark)) * parseInt(lom?.scale_level), 1)"
              checked=""
              :rules="validateLikert"
            />
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
