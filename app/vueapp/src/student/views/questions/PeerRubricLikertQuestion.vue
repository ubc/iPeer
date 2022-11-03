<script lang="ts" setup>
import { ref } from 'vue'
import { find, filter } from 'lodash'
import { validateRadio } from '@/helpers/rules'
import CustomRadioField from '@/components/fields/CustomRadioField.vue'
import CustomHiddenField from '@/components/fields/CustomHiddenField.vue'
import UserCard from '@/student/components/UserCard.vue'
import type { IUser, IRubricResponse, IRubricEvaluationDataLom, IRubricEvaluationDataCriteria } from '@/types/typings'
interface Props {
  members: IUser[]
  initialState: IRubricResponse
  rubric_criteria: IRubricEvaluationDataCriteria
  rubrics_lom: IRubricEvaluationDataLom[]
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
}>()
const props = defineProps<Props>()
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
  // TODO:: Refactor the following to NOT have an event lister in the outer div
  // TEMP
  if(!props.disabled) {
    const form = document.forms[0];
    const name = `selected_lom_${event.key}_${event.target}`
    const selectElement: HTMLFormElement|any = form.querySelector(`input[name=${name}]`)
    selectElement.setAttribute('value', event.value)
  }
}
// WATCH
// LIFECYCLE
</script>

<template>
  <table class="standardtable leftalignedtable">
    <thead>
    <tr>
      <th style="width: 20%">
        <div class="text-center leading-4">
          <div class="text-sm font-normal leading-4 mb-1">{{ props.rubric_criteria?.criteria }}</div>
          <div v-if="parseInt(props.rubric_criteria?.show_marks)" class="text-sm font-thin">({{ props.rubric_criteria?.multiplier }} marks)</div>
        </div>
      </th>
      <th :style="'width: '+ 80/props.rubrics_lom?.length +'%'"
          v-for="(criteria_lom, criteria_lomIdx) of props.rubrics_lom" :key="criteria_lom.id">
        <div class="text-center leading-4">
          <div class="font-normal">{{ criteria_lom.lom_comment }}</div>
          <div class="text-sm font-thin">{{ props.rubric_criteria?.rubrics_criteria_comment[criteria_lomIdx]['criteria_comment'] }}</div>
        </div>
      </th>
    </tr>
    </thead>
    <tbody>
    <template v-for="(member, member_idx) of props.members" :key="member.id">
    <CustomHiddenField v-if="!disabled"
        :name="`selected_lom_${member.id}_${rubric_criteria?.id}`"
        :value="getCriteriaDetail('criteria_num', member.id, rubric_criteria?.criteria_num)?.selected_lom??''" />
    <tr>
      <td><UserCard :member="member" /></td>
      <td v-for="(rubric_lom, rubric_lom_idx) of props.rubrics_lom" :key="rubric_lom.id">
        <CustomRadioField
          ref="selected_lom"
          :member_id="member.id"
          :criteria_num="props.rubric_criteria?.criteria_num"
          :name="`${member.id}criteria_points_${rubric_criteria?.id}`"
          :value="rubric_lom.lom_num"
          :rules="validateRadio"
          :checked="getCriteriaDetail('criteria_num', member.id, rubric_criteria?.criteria_num)?.selected_lom"
          :disabled="props.disabled"
          @click="handleSelectedLomClick({target: rubric_criteria?.id, key: member.id, value: $event.target.value})"
          @input="$emit('update:initialState', {
            member_id: member.id,
            criteria_num: rubric_criteria?.criteria_num,
            event: {
              key: 'selected_lom',
              value: $event.target.value
            }
          })"
        /><!-- TBD:: :rules="question.required ? validateLikert : null" -->
      </td>
    </tr>
    </template>
    </tbody>
  </table>
</template>
