<script lang="ts" setup>
import { toRef } from 'vue'
import { validateSentence } from '@/helpers/rules'
import { CustomInputField } from '@/components/fields'
// REFERENCES
const emit = defineEmits<{
  (e: 'update:form', value: object): void
}>()
const props = defineProps<{
  question: object,
  currentUser: object,
  initialState: object,
}>()
// DATA
const question = toRef(props, 'question')
const currentUser = toRef(props, 'currentUser')
const initialState = toRef(props, 'initialState')
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div :class="`datatable question_${question.question_num} mx-4`">
    <div class="question">{{ question.question_num }}. {{ question.title }} <span class="text-red-500" v-if="question.required">*</span></div>
    <div class="description text-sm text-slate-900 leading-relaxed mx-4 mb-2">{{ question.instructions }}</div>
    <div class="mx-4">
      <CustomInputField
          :name="`data[${currentUser.id}][EvaluationMixeval][${question.question_num}][question_comment]`"
          :rules="validateSentence"
      />
    </div>
  </div>
</template>
