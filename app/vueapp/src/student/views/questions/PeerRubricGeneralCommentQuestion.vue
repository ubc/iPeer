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
    <div v-if="settings?.question?.title" class="question">{{ props.index }}. {{ settings.question.title }}</div>
    <div v-if="settings?.question?.description" class="description">{{ settings.question.description }}</div>

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
            <div class="">General Comments</div>
            <small class="small"></small>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td><UserCard :member="member" /></td>
        <td>
          <CustomTextField
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
