<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue'
import { NotAvailable } from '@/components/messages'
import UserCard from '@/student/components/UserCard.vue'
import CustomRangeField from '@/components/fields/CustomRangeField.vue'
import type { IUser, IReview } from '@/types/typings'
interface Props {
  members: IUser[]
  reviews: IReview
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="evaluation-reviews" v-if="props.reviews?.event?.is_released">
    <NotAvailable
        v-if="!props.reviews?.event?.is_result_released"
        release-status="custom"
        :release-info="{title: 'Simple Evaluation Response'}"
    />

    <div class="datatable">
      <div class="question">{{ '1' }}. {{ 'Please rate each peer\'s relative contribution.' }}</div>
      <table class="standardtable center no-v-line">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 80%">
            <div class="">
              <div class="">{{ 'Relative Contribution' }}</div>
              <div class=""></div>
            </div>
          </th>
          <!--
          <th v-if="false" style="width: 20%" class="hidden visually-hidden">
            <div class="text-base font-medium leading-4 tracking-wide">
              <div class="">{{ 'Points' }}</div>
              <div class=""></div>
            </div>
          </th>-->
        </tr>
        </thead>
        <tbody>
        <tr v-for="member of props.members" :key="member?.id">
          <td><UserCard :member="member" /></td>
          <td>
            <div class="text-xs text-gray-500 mt-1">Compared to other teammates, how much did this peer contribute?</div>
            <CustomRangeField
                label="An average amount"
                :text="['Less', 'More']"
                min="0"
                :max="props.reviews?.simple?.remaining"
                type="range"
                class="flex-1 bg-transparent border-none"
                :value="props.reviews?.evaluator.find(review => review?.evaluatee === member?.id).score"
                disabled="disabled"
            />
          </td>
          <!--
          <td v-if="false" class="hidden visually-hidden">
            <div class="w-full h-full flex justify-center items-center text-sm">
              <span class="font-semibold mr-1">{{ props.reviews?.evaluator.find(review => review?.evaluatee === member?.id).score }}</span> points
            </div>
          </td>-->
        </tr>
        </tbody>
      </table>
    </div>

    <div class="datatable">
      <div class="question">{{ '2' }}. {{ 'Please provide overall comments about each peer.' }}</div>
      <table class="standardtable center no-v-line">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th style="width: 80%">
            <div class="">
              <div class="">{{ 'Comments' }}</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="member of props.members" :key="member?.id">
          <td><UserCard :member="member" /></td>
          <td>
            <div class="sentence response">
              <p class="text quote">
                {{ props.reviews?.evaluator.find(review => review?.evaluatee === member?.id).comment }}
              </p>
            </div>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
