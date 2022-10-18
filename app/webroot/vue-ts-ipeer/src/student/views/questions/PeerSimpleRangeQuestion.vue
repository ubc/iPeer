<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, toRef, onBeforeMount} from 'vue';

import UserCard from "@/student/components/UserCard.vue";
import InputElement from '@/components/fields/experimental/InputElement.vue'
import {findIndex, forEach, map, reduce} from "lodash";

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  members: object[]
  form: object
  name: string
  label?: string
  question?: string
  description?: string
  placeholder?: string
  disabled?: boolean | false
}>();

// DATA
const form = toRef(props, 'form')

const total_points = ref(props.evaluation?.review?.remaining ?? props.members.length * 100)

const student_slider = ref([])
const slider_sum = ref(0)

const student_scores = ref([])

// COMPUTED

// WATCH

const theSumThingDelete = ref(0);

function distributeDecimalRemainder() {

  const studentScoreSum = reduce(student_scores.value, (acc, val) => acc += val, 0);
  const remainder = total_points.value - studentScoreSum

  const lowestScore = remainder > 0 ? Math.min(...student_scores.value) : Math.max(...student_scores.value)
  const memberIdx = findIndex(student_scores.value, (val) => val === lowestScore)

  student_scores.value[memberIdx] += remainder

  console.log({remainder})
}

// METHODS
function updateStudentSlider(event) {
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

  theSumThingDelete.value = reduce(student_scores.value, (acc, val) => acc += val, 0)

  form.value.points = student_scores.value
}


// LIFECYCLE

onBeforeMount(() => {
  if (props.form?.points?.length) {
    student_scores.value = [...props.form.points]
    student_slider.value = map(props.form.points, (val, index) => {
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

  theSumThingDelete.value = reduce(student_scores.value, (acc, val) => acc += parseInt(val), 0)
})
</script>

<template>

  <div class="text-xs text-red-500">total_points: {{ total_points }}</div>
  <div class="text-xs text-red-500">slider_sum: {{ slider_sum }}</div>
  <div class="text-xs text-red-500">student_slider: {{ student_slider }}</div>
  <div class="text-xs text-red-500">student_scores: {{ student_scores }}</div>
  <div class="text-xs text-red-500">theSumThingDelete: {{ theSumThingDelete }}</div>


  <div class="datatable">
    <div v-if="props.question" class="question">{{ props.question }}</div>
    <div v-if="props.description" class="description text-sm mx-4 mb-2">{{ props.description }}</div>
    <table class="standardtable">
      <thead>
      <tr>
        <th><div class="">Peer</div></th>
        <th><div class="">Relative Contribution</div></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">

        <td style="width: 20%"><UserCard :member="member" /></td>
        <td style="width: 80%">
          <div class="slider-wrapper flex flex-col py-1 mx-2">
            <div class="amount text-sm text-slate-700 leading-3 mb-4">A fair<br/>amount</div>
            <div class="tick text-slate-500"></div>
            <input type="hidden" :name="`${name}[${memberIdx}]`" :value="props.form[name][memberIdx]" />
<!--            <input type="text"  value="" />-->
            <InputElement
                type="range"
                :label="label"
                :value="student_slider[memberIdx]"
                :name="`test[${memberIdx}]`"
                :placeholder="placeholder"
                :disabled="props.disabled"
                @input="updateStudentSlider({target:name, key:memberIdx, value: $event.target.value})"
            />
<!--                :name="`${name}[${memberIdx}]`"-->
<!--                :name="student_slider[memberIdx]"-->

            <div class="controls flex justify-between items-center">
              <div class="text-sm text-slate-700 leading-3">Less</div>
              <div class="text-sm text-slate-700 leading-3">More</div>
            </div>

            <div class="temp">
              <div class="text-xs text-red-700">{{ props.form[name][memberIdx] }}</div>
            </div>
          </div>

        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
