<script lang="ts" setup>
import { ref, toRef, reactive, watch, computed, onMounted } from 'vue'
import { validateParagraph } from '@/helpers/rules'
import UserCard from "@/student/components/UserCard.vue";
import CustomTextField from '@/components/fields/CustomTextField.vue'

import type { User, ISimpleEvaluation } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
  // (e: 'update:comments', option: string): void
}>()
const props = defineProps<{
  members: IUser[]
  initialState: ISimpleEvaluation
  question?: string
  name: string
  label?: string
  description?: string
  placeholder?: string
  disabled?: boolean | false
}>();
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="datatable" id="PeerSimpleRangeQuestion">
    <div v-if="props.question" class="question">{{ props.question }}</div>
    <div v-if="props.description" class="description text-sm mx-4 mb-2">{{ props.description }}</div>
    <table class="standardtable">
      <thead>
      <tr>
        <th style="width: 20%">
          <div class="text-center leading-4">
            <div class="font-normal">Peer</div>
            <div class="text-sm font-thin"></div>
          </div>
        </th>
        <th style="width: 80%;">
          <div class="text-center leading-4">
            <div class="font-normal">Comments</div>
            <div class="text-sm font-thin"></div>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td style="width: 20%"><UserCard :member="member" /></td>
        <td style="width: 80%">
          <CustomTextField
              :label="label"
              :name="`${name}[${memberIdx}]`"
              :rules="validateParagraph"
              :disabled="props.disabled"
          /><!-- TBD:: :rules="question.required ? validateParagraph : null" -->
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
