<script lang="ts" setup>
import { ref } from 'vue';
import { find, map, filter } from 'lodash'
import UserCard from '@/student/components/UserCard.vue'
import CustomTextField from '@/components/fields/CustomTextField.vue'
import { validateParagraph } from '@/helpers/rules'
import type {EvaluationReviewResponse, RubricEvaluationReviewDataCriteria, User} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  rubric_criteria_idx: string|number
  members: User[]
  initialState: EvaluationReviewResponse
  rubric_criteria: RubricEvaluationReviewDataCriteria
}>()
// DATA
// COMPUTED
// METHODS
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
// WATCH
// LIFECYCLE
</script>

<template>
  <table class="standardtable leftalignedtable">
    <thead>
    <tr>
      <th style="width: 20%">
        <div class="flex flex-col text-center">
          <div class="leading-4 flex-1 font-medium">Peer</div>
          <small class="text-sm leading-4 flex-1 font-normal"></small>
        </div>
      </th>
      <th style="width: 80%">
        <div class="flex flex-col text-center">
          <div class="flex-1 font-medium">Comments</div>
          <small class="flex-1 text-sm font-normal"></small>
        </div>
      </th>
    </tr>
    </thead>
    <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td style="width: 20%"><UserCard :member="member" /></td>
        <td style="width: 80%">
          <div class="flex flex-col">
            <CustomTextField class="flex-1"
                :id="`${member.id}comments[${rubric_criteria?.id}]`"
                :name="`${member.id}comments[${props.rubric_criteria_idx}]`"
                :value="getCriteriaDetail('criteria_comment', member.id, rubric_criteria?.criteria_num)['criteria_comment']"
                :rules="validateParagraph"
            /><!-- :rules="question.required ? validateParagraph : null" -->
          </div>
        </td>
      </tr>
    </tbody>
  </table>

</template>
