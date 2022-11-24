<script lang="ts" setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { find, map, filter } from 'lodash'
import { validateParagraph } from '@/helpers/rules'
import UserCard from '@/student/components/UserCard.vue'
import CustomTextField from '@/components/fields/CustomTextField.vue'
import type { IUser, IRubricResponse, IRubricEvaluationDataCriteria } from '@/types/typings'
interface Props {
  members: IUser[]
  initialState: IRubricResponse
  rubric_criteria_idx: string|number
  rubric_criteria: IRubricEvaluationDataCriteria
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
}>()
const props = defineProps<Props>()
const route = useRoute()
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
  <table class="standardtable center no-v-line">
    <thead>
    <tr>
      <th style="width: 20%">
        <div class="flex flex-col">
          <div class="">Peer</div>
          <small class="small"></small>
        </div>
      </th>
      <th style="width: 80%">
        <div class="flex flex-col">
          <div class="">Comments</div>
          <small class="small"></small>
        </div>
      </th>
    </tr>
    </thead>
    <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td style="width: 20%"><UserCard :member="member" /></td>
        <td style="width: 80%">
          <CustomTextField
              :id="`${member.id}comments[${rubric_criteria?.id}]`"
              :name="`${member.id}comments[${props.rubric_criteria_idx}]`"
              :value="getCriteriaDetail('criteria_comment', member.id, rubric_criteria?.criteria_num)['criteria_comment']"
              :rules="validateParagraph"
              :disabled="props.disabled"
              @input="$emit('update:initialState', {
                member_id: member.id,
                criteria_num: rubric_criteria?.criteria_num,
                event: {
                  key: 'criteria_comment',
                  value: $event.target.value
                }
              })"
          />
        </td>
      </tr>
    </tbody>
  </table>
</template>
