<script lang="ts" setup>
import { ref, toRef, reactive, watch, computed, onMounted } from 'vue'
import { validateParagraph } from '@/helpers/rules'
import UserCard from '@/student/components/UserCard.vue'
import AutoSpinner from '@/components/AutoSpinner.vue'
import CustomTextField from '@/components/fields/CustomTextField.vue'

import type { IUser, ISimpleEvaluation } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
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
    <div v-if="props.question" class="question relative">{{ props.question }} <AutoSpinner /></div>
    <div v-if="props.description" class="description">{{ props.description }}</div>

    <table class="standardtable center no-v-line">
      <thead>
      <tr>
        <th style="width: 20%">
          <div class="flex flex-col">
            <div class="">Peer</div>
            <small class="small"></small>
          </div>
        </th>
        <th style="width: 80%;">
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
              :label="label"
              :name="`${name}[${memberIdx}]`"
              :rules="validateParagraph"
              :disabled="props.disabled"
          />
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
