<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue'
import { NotAvailable } from '@/components/messages'
import UserCard from '@/student/components/UserCard.vue'
import { IconSquareInfo } from '@/components/icons'
import type { IReview } from '@/types/typings'

interface Props {
  reviews: IReview
  self: string
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
// DATA
// COMPUTED
// METHODS
function getAdditionalComment(score: string|any) {
  score = parseInt(score)
  // console.log(typeof score)
  // switch (score) {
  //   case score >= 0 && score <= 10:
  //     return '0-10'
  //   case score > 10 && score <= 20:
  //     return '11-20'
  //   case score > 20 && score <= 30:
  //     return '21-30'
  //   case score > 30 && score <= 40:
  //     return '31-40'
  //   case score > 40 && score <= 50:
  //     return '41-50'
  //   case score > 50 && score <= 60:
  //     return '51-60'
  //   case score > 60 && score <= 70:
  //     return '61-70'
  //   case score > 70 && score <= 80:
  //     return '71-80'
  //   case score > 80 && score <= 90:
  //     return '81-90'
  //   case score > 90 && score <= 100:
  //     return '91-100'
  //   case score > 100:
  //     return 'exceeded expectation'
  //   default:
  //     break
  // }
  return '??'
}
// WATCH
// LIFECYCLE
</script>

<template>
  <NotAvailable
      v-if="!props.reviews?.event?.is_released"
      release-status="online"
      :release-info="error"
  />
  <div class="evaluation-reviews" v-if="props.reviews?.event?.is_released">
    <NotAvailable
        v-if="Object.keys(props.reviews?.evaluator).length===0"
        release-status="custom"
        :release-info="{title: 'Content Not Found', message: 'Your need to complete your evaluation.'}"
    />
    <div class="datatable" v-if="Object.keys(props.reviews?.evaluator).length>0">
      <div class="question">{{ '1' }}. {{ 'Please rate each peer\'s relative contribution.' }}</div>
      <table class="standardtable center no-v-line bg-gray-100">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">Peers</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 45%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">{{ 'Your Relative Contribution' }}</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 35%">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">{{ 'Your Average Points' }}</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td><UserCard :member="{ first_name: props.reviews?.status?.members_count, last_name: 'Peers' }" /></td>
          <td>
            <ul class="range review text-sm text-left font-light leading-5 tracking-wide">
              <template v-if="props.reviews?.evaluatee?.scores.length">
                <li class="input list-disc list-inside" v-for="(score, index) of props.reviews?.evaluatee?.scores" :key="`${index}_${score}`">
                  1 peer said you contributed {{ getAdditionalComment(score) }}/{{ score }} a fair amount
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
          <td class="">
            <div class="text-sm py-3">
              <div class=""><span class="font-semibold tracking-wider">{{ props.reviews?.status?.average_score }}</span> points</div>
              <div class="flex items-start mt-2">
                <IconSquareInfo class="w-[100px] text-white -mt-1 mr-1" />
                <div class="text-sm text-left font-light leading-4 tracking-wide">100 points is about what each peer would receive, if everyone agreed all team members contributed evenlly</div>
              </div>
            </div>
          </td>
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
        <template v-if="props.reviews?.evaluatee?.scores.length">
        <tr v-for="(comment, index) of props.reviews?.evaluatee?.comments" :key="`${comment}_${index}`">
          <td><UserCard :member="{first_name: 'Peer', last_name: ''}" class="font-medium" /></td>
          <td style="text-align: left">
            <div class="quotes text-sm text-left font-light leading-5 tracking-wide">{{ comment }}</div>
          </td>
        </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="2">
              <div class="text-center font-light">
                <div class="text-lg tracking-wider leading-relaxed">No Comments About Your Teamwork.</div>
              </div>
            </td>
          </tr>
        </template>
        </tbody>
      </table>
    </div>

  </div>
</template>
