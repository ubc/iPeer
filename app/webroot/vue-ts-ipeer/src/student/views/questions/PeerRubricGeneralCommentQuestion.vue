<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { isEmpty, find } from 'lodash'
import { validateText } from '@/helpers/rules'

import UserCard from '@/student/components/UserCard.vue'

import type { EvaluationReviewResponse, User } from '@/types/typings'
import TextArea from '@/components/fields/TextArea.vue'

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  index: string | number
  members: User[]
  initialState: EvaluationReviewResponse
}>()

// DATA
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
function getGeneralComment(target: string, memberId: string) {
  return find(props.initialState.data, { evaluatee: memberId})[target]
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
          <div class="flex flex-col">
            <TextArea
                class="flex-1"
                :name="`${member.id}gen_comment`"
                :value="getGeneralComment('comment', member.id)"
                :rules="validateText"
            />
          </div>
        </td>
      </tr>
      </tbody>
    </table>

  </div>
</template>
