<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { isEmpty, find, map, filter } from 'lodash'
import { validateRadio } from '@/helpers/rules'

import UserCard from '@/student/components/UserCard.vue'
import { InputText, InputRadio } from '@/components/fields'

import type {
  EvaluationReviewResponse,
  RubricEvaluationReviewDataCriteria,
  RubricEvaluationReviewDataLom,
  User
} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  members: User[]
  initialState: EvaluationReviewResponse
  rubric_criteria: RubricEvaluationReviewDataCriteria
  rubrics_lom: RubricEvaluationReviewDataLom[]
}>()

// DATA
// COMPUTED
// METHODS
function gradeRoundUp(num: number, precision: number) {
  precision = Math.pow(10, precision)
  return Math.floor(num * precision) / precision
}
function getCriteriaDetail(target: string, key: string, value?: string) {
  const criteria = find(props.initialState.data, { evaluatee: key })
  if (criteria) {
    const detail = filter(criteria?.details, (cri) => {
      if(cri.criteria_number === value) return cri
    })
    return detail[0]
  }
  return ''
}
function handleSelectedLomClick(event: {target: string, key: string, value: string}) {
  const form = document.forms[0];
  const name = `selected_lom_${event.key}_${event.target}`
  const selectElement: HTMLFormElement | any = form.querySelector(`input[name=${name}]`)
  selectElement.setAttribute('value', event.value)
}
// WATCH
// LIFECYCLE

</script>

<template>
  <table class="standardtable leftalignedtable">
    <thead>
    <tr>
      <th style="width: 20%">
        <div class="">
          <div class="text-sm leading-4 font-serif font-medium">{{ props.rubric_criteria?.criteria }}</div>
          <small v-if="parseInt(props.rubric_criteria.show_marks)" class="text-sm font-normal">({{ props.rubric_criteria?.multiplier }} marks)</small>
        </div>
      </th>
      <th :style="'width: '+ 80/props.rubrics_lom.length +'%'"
          v-for="(criteria_lom, criteria_lomIdx) of props.rubrics_lom"
          :key="criteria_lom.id" >
        <div class="flex flex-col text-center">
          <div class="flex-1 font-medium">{{ criteria_lom.lom_comment }}</div>
          <small class="flex-1 text-sm font-normal">
            {{ props.rubric_criteria?.rubrics_criteria_comment[criteria_lomIdx]['criteria_comment'] }}
          </small>
        </div>
      </th>
    </tr>
    </thead>
    <tbody>
    <template v-for="(member, member_idx) of props.members" :key="member.id">
    <InputText type="hidden" :name="`selected_lom_${member.id}_${rubric_criteria.id}`" :value="getCriteriaDetail('criteria_num', member.id, rubric_criteria?.criteria_num)?.selected_lom??''" />
    <tr>
      <td><UserCard :member="member" /></td>
      <td v-for="(rubric_lom, rubric_lom_idx) of props.rubrics_lom" :key="rubric_lom.id">
        <InputRadio class="flex justify-center" ref="selected_lom"
            :member_id="member.id"
            :criteria_num="props.rubric_criteria?.criteria_num"
            :name="`${member.id}criteria_points_${rubric_criteria.id}`"
            :value="rubric_lom.lom_num"
            :rules="validateRadio"
            :checked="parseInt(rubric_lom.lom_num) === parseInt(getCriteriaDetail('criteria_num', member.id, rubric_criteria?.criteria_num)?.selected_lom)"
            @click="handleSelectedLomClick({target: rubric_criteria.id, key: member.id, value: $event.target.value})"
        />
      </td>
    </tr>
    </template>
    </tbody>
  </table>
</template>
