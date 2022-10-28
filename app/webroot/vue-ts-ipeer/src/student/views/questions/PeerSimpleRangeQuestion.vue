<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef, onBeforeMount} from 'vue';
import {findIndex, forEach, map, reduce} from "lodash";

import UserCard from '@/student/components/UserCard.vue'
import { CustomRangeField } from '@/components/fields'

import type { EvaluationReviewResponse, User } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
  members: User[]
  initialState: EvaluationReviewResponse
  remaining: string | number
  name: string
  label?: string
  question?: string
  description?: string
  placeholder?: string
  disabled?: boolean | false
}>();
// DATA
const showScore       = ref(false)
const response        = toRef(props, 'initialState')
const total_points    = ref(props.remaining ?? props.members.length * 100)
const student_slider  = ref([])
const slider_sum      = ref(0)
const student_scores  = ref([])
// COMPUTED
// METHODS
function updateStudentSlider(event: {target:string, key:string, value: string}) {
  const {target, key, value} = event;

  const oldValue = student_slider.value[key]
  slider_sum.value += parseInt(value) - oldValue

  if (slider_sum.value <= 0){
    student_scores.value = map(student_scores.value, () => total_points.value / student_scores.value.length)
  } else {
    student_slider.value[key] = parseInt(value);
    student_scores.value = map(student_slider.value, (sliderValue) => Math.round(total_points.value * sliderValue / slider_sum.value))
  }
  distributeDecimalRemainder()
  response.value.data.points = student_scores.value
}
// LIFECYCLE
onBeforeMount(() => {
  const initialPoints: number[] | undefined = response.value?.data?.points
  if (initialPoints?.length) {
    student_scores.value = [...initialPoints]
    student_slider.value = map(initialPoints, (val, index) => {
      const studentScoreValue = parseInt(val)
      const studentPercentValue = studentScoreValue / total_points.value
      const defaultStudentSliderValue = 100 * studentPercentValue
      slider_sum.value += defaultStudentSliderValue;
      return defaultStudentSliderValue
    })
  } else {
    const defaultStudentSliderValue = Math.round(total_points.value / props.members.length)
    student_slider.value = map(props.members, () => defaultStudentSliderValue)
    slider_sum.value = defaultStudentSliderValue * props.members?.length
    student_scores.value = map(props.members, () => total_points.value / props.members.length)
  }
})
// WATCH
function distributeDecimalRemainder() {
  const studentScoreSum = reduce(student_scores.value, (acc, val) => acc += val, 0);
  const remainder = total_points.value - studentScoreSum
  const lowestScore = remainder > 0 ? Math.min(...student_scores.value) : Math.max(...student_scores.value)
  const memberIdx = findIndex(student_scores.value, (val) => val === lowestScore)
  student_scores.value[memberIdx] += remainder
}
</script>

<template>
  <div class="datatable">
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
        <th style="width: 80%">
          <div class="text-center leading-4">
            <div class="font-normal">Relative Contribution</div>
            <div class="text-sm font-thin"></div>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td style="width: 20%"><UserCard :member="member" @click="showScore = !showScore" /></td>
        <td style="width: 80%">
        <input type="hidden" :name="`${name}[${memberIdx}]`" :value="response?.data && response?.data[name] ? response?.data[name][memberIdx] : ''" />
        <CustomRangeField
            :text="['Less', 'More']"
            :label="'A fair amount'"
            :name="`percent[${memberIdx}]`"
            :value="student_slider[memberIdx]"
            :response="response?.data.points"
            :points="response?.data && response?.data?.points ? response?.data?.points[memberIdx] : ''"
            :placeholder="placeholder"
            :disabled="props.disabled"
            @update:input="updateStudentSlider({target:name, key:memberIdx, value: $event.target.value})"
        />
          <div v-if="showScore" class="text-sm text-red-400">{{ response?.data && response?.data?.points ? response?.data?.points[memberIdx] : '' }}</div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
