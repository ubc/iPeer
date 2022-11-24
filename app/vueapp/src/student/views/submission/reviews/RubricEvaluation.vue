<script lang="ts" setup>
import { computed, ref, toRefs } from 'vue'
import { isEmpty, find, map, filter, reduce, isObject, isArray, isNumber, forEach } from 'lodash'
import { review } from '@/helpers'
import UserCard from '@/student/components/UserCard.vue'
import type { IUser, IReview } from '@/types/typings'
interface Props {
  self: string
  isDisabled?: boolean
  currentUser: IUser
  reviews: IReview
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
// DATA
const name          = ref('View Rubric Evaluation Result')
// const reviews       = toRefs(props, 'reviews')
// COMPUTED
const evaluator = computed(() => props.reviews?.evaluator)
const evaluatee = computed(() => props.reviews?.evaluatee)
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="evaluation-reviews" v-if="!isEmpty(props.reviews) && props.reviews?.event?.is_released">

    <div class="datatable" v-for="criteria of props.reviews?.rubric?.criterias" :key="criteria.id">
      <div class="question">
        {{ criteria.criteria_num }}. {{ criteria.criteria }}
        <span class="text-base text-slate-500" v-if="!isEmpty(evaluatee)">
          — <span class="badge">{{ review.peer(evaluatee).where(criteria.criteria_num).reduce('grade', 'average').result().toFixed(1) }} /
          {{ criteria.multiplier }} marks</span>
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
          <th
              :style="{width: (80 / props.reviews?.rubric?.loms?.length)+'%'}"
              v-for="lom of props.reviews?.rubric?.loms" :key="lom.id"
          >
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
            <ul class="flex justify-center space-x-1" v-if="!isEmpty(evaluatee)">
              <li class="text-xs" v-for="(r, i) of review.peer(evaluatee).where(criteria.criteria_num).result()" :key="r.id+i">
                {{ r.selected_lom === lom.lom_num ? 'x' : '' }}
              </li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'said'}" class="font-medium" /></td>
          <td :style="{width: (80 / props?.reviews?.rubric.loms.length)+'%'}" v-for="lom of props?.reviews?.rubric.loms" :key="lom.id">
            <ul class="flex justify-center space-x-1" v-if="!isEmpty(evaluator)">
              <li class="text-xs" v-for="(criteria, i) of review.self(evaluator).where(criteria.criteria_num).result()" :key="criteria.id">
                {{ criteria.selected_lom === lom.lom_num ? 'x' : '' }}
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
            <ul class="space-y-1">
              <li class="" v-for="criteria of review.peer(evaluatee).where(criteria.criteria_num).result()" :key="criteria.id">
                <span v-if="criteria.criteria_comment" class="quotes text-sm text-slate-700 font-light tracking-wide">{{ criteria.criteria_comment }}</span>
              </li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="space-y-1">
              <li class="" v-for="criteria of review.self(evaluator).where(criteria.criteria_num).result()" :key="criteria.id">
                <span v-if="criteria.criteria_comment" class="quotes text-sm text-slate-700 font-light tracking-wide">{{ criteria.criteria_comment }}</span>
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div class="datatable">
      <div class="question">{{props.reviews?.rubric?.criterias?.length+1}}. {{ 'Please provide overall comments about each peer.' }}</div>
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
            <ul class="space-y-1">
              <li class="" v-for="(review, index) of review.peer(evaluatee).findAll('comment').result()" :key="index">
                <span v-if="review.comment" class="quotes text-sm text-slate-700 font-light tracking-wide">{{ review.comment }}</span>
              </li>
            </ul>
          </td>
        </tr>
        <tr v-if="parseInt(props.reviews?.event?.self_eval)">
          <td><UserCard :member="{first_name: 'You', last_name: 'Said'}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="space-y-1">
              <li class="" v-for="(review, index) of review.self(evaluator).findAll('comment').result()" :key="index">
                <span v-if="review.comment" class="quotes text-sm text-slate-700 font-light tracking-wide">{{ review.comment }}</span>
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
