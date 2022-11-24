<script lang="ts" setup>
import { computed, ref, toRefs } from 'vue'
import { isEmpty, map, find, reduce } from 'lodash'
import UserCard from '@/student/components/UserCard.vue'
import type {IUser, IReview, IReviewEvaluation} from '@/types/typings'
interface Props {
  self: string
  currentUser: IUser
  members: IUser[]
  reviews: IReview
  isDisabled?: boolean
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
// DATA
const name          = ref('View Rubric Evaluation Result')
// const reviews       = toRefs(props, 'reviews')
// COMPUTED
const evaluator = computed(() => props.reviews?.evaluator) as unknown as IReviewEvaluation
const evaluatee = computed(() => props.reviews?.evaluatee) as unknown as IReviewEvaluation
// METHODS
// WATCH
// LIFECYCLE
function calcAverageGrade(criteria_num: string) {
  const data = map(evaluatee.value, review => find(review?.details, {criteria_number: criteria_num}))
  return reduce(map(data, d => d['grade']), (acc, val) => acc + Number(val) / data?.length, 0)
}
</script>

<template>
  <div class="evaluation-reviews" v-if="!isEmpty(props.reviews) && props.reviews?.event?.is_released">

    <div class="datatable" v-for="criteria of props.reviews?.rubric?.criterias" :key="criteria.id">
      <div class="question">{{ criteria.criteria_num }}. {{ criteria.criteria }}
        <span class="text-base text-slate-500" v-if="!isEmpty(evaluatee) && criteria.multiplier > 0">
          — <span class="badge">{{ calcAverageGrade(criteria.criteria_num) }}</span> / {{ criteria.multiplier }} marks
        </span>
      </div>
      <table class="standardtable center no-v-line bg-gray-100">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th :style="{width: (80 / props.reviews?.rubric?.loms?.length)+'%'}" v-for="lom of props.reviews?.rubric?.loms" :key="lom.id">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">{{ lom.lom_comment }}</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{first_name: 'Peers', last_name: 'said'}" class="font-medium" /></td>
          <td :style="{width: (80 / props.reviews?.rubric?.loms?.length)+'%'}" v-for="lom of props.reviews?.rubric?.loms" :key="lom.id">
            <ul class="likert review" v-if="!isEmpty(evaluatee)">
              <li class="input" v-for="(r,i) of map(evaluatee, r => find(r.details, {criteria_number: criteria?.criteria_num}))" :key="i">
                {{ r?.selected_lom === lom?.lom_num ? 'x' : '' }}
              </li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'said'}" class="font-medium" /></td>
          <td :style="{width: (80 / props?.reviews?.rubric.loms.length)+'%'}" v-for="lom of props?.reviews?.rubric.loms" :key="lom.id">
            <ul class="likert review" v-if="!isEmpty(evaluator)">
              <li class="input" v-for="(r,i) of map(evaluator, r => find(r.details, {criteria_number: criteria?.criteria_num}))" :key="i">
                {{ r?.selected_lom === lom?.lom_num ? 'x' : '' }}
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>

      <table class="standardtable center no-v-line bg-gray-100">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 80%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Comments About Your Teamwork</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{first_name: 'Peers', last_name: 'said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="sentence review">
              <li class="text quote" v-for="(r,i) of evaluatee" :key="i">
                <template v-for="(detail, d) of r.details" :key="d">
                  {{ detail.criteria_number === criteria.criteria_num ? detail.criteria_comment : '' }}
                </template>
              </li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="sentence review">
              <li class="text quote" v-for="(r,i) of evaluator" :key="i">
                <template v-for="(detail, d) of r.details" :key="d">
                  {{ detail.criteria_number === criteria.criteria_num ? detail.criteria_comment : '' }}
                </template>
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div class="datatable">
      <div class="question">
        {{props.reviews?.rubric?.criterias?.length+1}}. {{ 'Please provide overall comments about each peer.' }}
      </div>
      <table class="standardtable center no-v-line">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 80%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Comments About Your Teamwork</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{first_name: 'Peers', last_name: 'Said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="paragraph review">
              <li class="text quote" v-for="(r,i) of evaluatee" :key="r?.id+i">{{ r?.comment }}</li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'Said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="paragraph review">
              <li class="text quote" v-for="(r,i) of evaluator" :key="r?.id+i">{{ r?.comment }}</li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
