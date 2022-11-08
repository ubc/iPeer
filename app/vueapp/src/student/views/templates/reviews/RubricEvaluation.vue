<script lang="ts" setup>
import UserCard from '@/student/components/UserCard.vue'

import type { IRubricEvaluation, IRubricResponse, IUser} from '@/types/typings'
interface Props {
  members: IUser[]
  self: string
  rubric: IRubricEvaluation
  response: IRubricResponse
  currentUser: IUser
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'fetch:evaluation', option: string): void
}>()
const props = defineProps<Props>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="evaluation-reviews">
    <h1 class="mb-64"><span class="text-red-500 text-lg">work in-progress </span><span class="text-gray-300">Rubric Evaluation Reviews</span></h1>
    
    <div class="datatable debugger" v-for="question of props.rubric?.data?.rubrics_criteria" :key="question?.id">
      <div class="debugger">rubric_id: {{question.rubric_id}} --- criteria_num: {{question.criteria_num}}</div>
      <div class="question text-lg text-slate-700 font-normal font-serif my-2">
        {{ question.criteria_num }}. {{question.criteria }} ----
        <template v-if="question.show_marks">{{  }} / {{ question?.multiplier }} marks</template>
      </div>

      <table class="standardtable center">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-normal leading-4">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 20%">
            <div class="text-sm font-semibold leading-4">
              <div class="">Not Acceptable /</div>
              <div class="">Entirely Absent</div>
            </div>
          </th>
          <th style="width: 20%">
            <div class="text-sm font-semibold leading-4">
              <div class="">Needs Improvement</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 20%">
            <div class="text-sm font-semibold leading-4">
              <div class="">Satisfactory</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 20%">
            <div class="text-sm font-semibold leading-4">
              <div class="">Excellent</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{first_name: 'Peers', last_name: 'Said'}" class="font-medium" /></td>
          <td><ul></ul></td>
          <td><ul></ul></td>
          <td><ul class="flex justify-center space-x-1"><li>X</li><li>X</li></ul></td>
          <td><ul><li>X</li></ul></td>
        </tr>
        <tr v-if="parseInt(props.self)">
          <td><UserCard :member="{first_name: 'You', last_name: 'Said'}" class="font-medium" /></td>
          <td></td>
          <td></td>
          <td></td>
          <td><ul><li>X</li></ul></td>
        </tr>
        </tbody>
      </table>

    </div>



    <div class="datatable debugger">

      <div class="question text-lg text-slate-700 font-normal font-serif my-2">
        {{ props.rubric?.data?.rubrics_criteria?.length+1 }}. {{ 'Please provide overall comments about each peer.' }}
      </div>

      <table class="standardtable center">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-normal leading-4">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 80%">
            <div class="text-sm font-semibold leading-4">
              <div class="">Comments About You</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{first_name: 'Peers', last_name: 'Said'}" class="font-medium" /></td>
          <td>
            <ul class="text-left text-sm text-slate-700 space-y-3">
              <li v-for="question of props.response?.data" :key="question?.id">{{ question.comment }}</li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.self)">
          <td><UserCard :member="{first_name: 'You', last_name: 'Said'}" class="font-medium" /></td>
          <td>
            <ul>
              <li></li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>

    </div>








    <ul
        class="my-4 visually-hidden"
        v-for="question of props.rubric?.data?.rubrics_criteria" :key="question?.id">
      <pre>rubric_id: {{ question.rubric_id }}</pre>
      <pre>criteria_num: {{ question.criteria_num }}</pre>
      <pre>show_marks: {{ question.show_marks }}</pre>

      <li>

        <div class="question text-slate-700 font-normal  my-2">{{ question.criteria_num }}. {{question.criteria }}</div>

      </li>

      <hr/>
    </ul>





    <ul
        class="my-4 visually-hidden"
        v-for="question of props.response?.data" :key="question?.id">

      <li>

        <ul>
          <li v-for="detail of question.details" :key="detail.id">
            <pre>id: {{ detail.id }}</pre>
            <pre>criteria_number: {{ detail.criteria_number }}</pre>
            <pre>selected_lom: {{ detail.selected_lom }}</pre>
            <pre>criteria_comment: {{ detail.criteria_comment }}</pre>
          </li>
        </ul>


      </li>

      <h2>General Comments</h2>
      <hr/>
      <li>{{ question.comment }}</li>

    </ul>

  </div>
</template>