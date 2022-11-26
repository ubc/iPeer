<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue'
import { useReviewsStore } from '@/stores/reviews'
import { NotAvailable } from '@/components/messages'
import UserCard from '@/student/components/UserCard.vue'
import { IconSquareInfo } from '@/components/icons'
import type { IReview } from '@/types/typings'

interface Props {
  reviews: IReview
  self: string
}
// REFERENCES
const emit            = defineEmits<{}>()
const props           = defineProps<Props>()
const reviewsStore    = useReviewsStore()
// DATA
// COMPUTED
const error     = computed(() => reviewsStore.error)
const hasError  = computed(() => reviewsStore.hasError)
// METHODS
function getAdditionalComment(score: string|any) {
  if(score < 50) {
    return 'less than'
  } else if(score > 51 && score < 70) {
    return ''
  } else if(score > 71 && score < 90) {
    return ''
  } else if(score > 91) {
    return 'more than'
  }
}
// WATCH
// LIFECYCLE
</script>

<template>
  <NotAvailable v-if="!props.reviews?.event?.is_released" status="online" />
  <NotAvailable v-if="hasError" :data="error" />
  <div v-if="props.reviews?.event?.is_released && !hasError" class="evaluation-reviews">
    <div class="datatable" v-if="Object.keys(props.reviews?.evaluator).length>0">
      <div class="question">{{ '1' }}. {{ 'Please rate each peer\'s relative contribution.' }}</div>
      <table class="standardtable center no-v-line bg-gray-100">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="flex flex-col">
              <div class="">Peers</div>
              <small class="small"></small>
            </div>
          </th>
          <th style="width: 80%">
            <div class="flex flex-col">
              <div class="">{{ 'Your Relative Contribution' }}</div>
              <small class="small"></small>
            </div>
          </th>
          <!--
          <th v-if="false" style="width: 35%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">{{ 'Your Average Points' }}</div>
              <div class=""></div>
            </div>
          </th>-->
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{ first_name: props.reviews?.status?.members_count, last_name: 'Peers' }" /></td>
          <td>
            <ul class="range review">
              <template v-if="props.reviews?.evaluatee?.scores.length">
                <li class="input" v-for="(score, index) of props.reviews?.evaluatee?.scores" :key="`${index}_${score}`">
                  1 peer said you contributed {{ getAdditionalComment(score) }} a fair amount
                </li>
              </template>
              <template v-else>
                <div class="text-center">
                  <div class="text-lg tracking-wider leading-relaxed">Your Relative Contribution.</div>
                  <div class="text-md tracking-wider leading-relaxed">Please check at later time.</div>
                </div>
              </template>
            </ul>
          </td>
          <!--
          <td v-if="false" class="">
            <div class="text-sm py-3">
              <div class=""><span class="font-semibold tracking-wider">{{ props.reviews?.status?.average_score }}</span> points</div>
              <div class="flex items-start mt-2">
                <IconSquareInfo class="w-[100px] text-white -mt-1 mr-1" />
                <div class="text-sm text-left font-light leading-4 tracking-wide">100 points is about what each peer would receive, if everyone agreed all team members contributed evenlly</div>
              </div>
            </div>
          </td>-->
        </tr>
        </tbody>
      </table>
    </div>

    <div class="datatable" v-if="Object.keys(props.reviews?.evaluator).length>0">
      <div class="question">{{ '2' }}. {{ 'Please provide overall comments about each peer.' }}</div>
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
              <div class="">Comments About Your Teamwork</div>
              <small class="small"></small>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <template v-if="props.reviews?.evaluatee?.scores.length">
        <tr v-for="(comment, index) of props.reviews?.evaluatee?.comments" :key="`${comment}_${index}`">
          <td><UserCard :member="{first_name: 'Peer', last_name: ''}" class="font-medium" /></td>
          <td style="text-align: left">
            <ul class="sentence review">
              <li class="text quote">{{ comment }}</li>
            </ul>
          </td>
        </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="2">
              <ul class="flex flex-col">
                <li class="no-content-found">No Comments About Your Teamwork.</li>
              </ul>
            </td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>

  </div>
</template>
