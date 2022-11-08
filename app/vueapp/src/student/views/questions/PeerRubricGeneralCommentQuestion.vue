<script lang="ts" setup>
import {ref, reactive} from 'vue'
import { useRoute } from 'vue-router'
import { find } from 'lodash'
import { validateParagraph } from '@/helpers/rules'
import CustomTextField from '@/components/fields/CustomTextField.vue'
import UserCard from '@/student/components/UserCard.vue'
import type { IUser, IRubricResponse} from '@/types/typings'
interface Props {
  index: string|number
  members: IUser[]
  initialState: IRubricResponse
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:initialState', option: object): void
}>()
const props = defineProps<Props>()
const route = useRoute()
// DATA
const disabled = ref(route.name === 'submission.view' ? true : false)
const settings = reactive({
  question: {
    title: 'Please provide overall comments about each peer.',
    description: ''
  }
})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
function getGeneralComment(target: string, key: string): string|any {
  const state = props.initialState
  if(state) {
    const detail = find(state?.data, { evaluatee: key})
    if(detail) {
      return detail[target]
    }
  }
}
</script>

<template>
  <div class="datatable">
    <div v-if="settings?.question?.title" class="question text-base text-slate-900 tracking-wide">{{ props.index }}. {{ settings.question.title }}</div>
    <div v-if="settings?.question?.description" class="description text-sm text-slate-700 ml-5 mb-2 tracking-wider">{{ settings.question.description }}</div>

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
            <div class="flex-1 font-medium">General Comments</div>
            <small class="flex-1 text-sm font-normal"></small>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td><UserCard :member="member" /></td>
        <td>
          <div v-if="props.disabled" class="quotes text-sm text-slate-700 font-light tracking-wider" v-html="getGeneralComment('comment', member.id)" />
          <CustomTextField v-else
              :name="`${member.id}gen_comment`"
              :value="getGeneralComment('comment', member.id)"
              :rules="validateParagraph"
              :disabled="props.disabled"
              @input="$emit('update:initialState', {
                member_id: member.id,
                criteria_num: '',
                event: {
                  key: 'comment',
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
