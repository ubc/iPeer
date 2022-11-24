<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { isEmpty, find, map, filter, reduce, isObject, isArray, isNumber, forEach } from 'lodash'
import PeerRubricLikertQuestion from '@/student/views/questions/PeerRubricLikertQuestion.vue'
import PeerRubricCommentQuestion from '@/student/views/questions/PeerRubricCommentQuestion.vue'
import PeerRubricGeneralCommentQuestion from '@/student/views/questions/PeerRubricGeneralCommentQuestion.vue'
import type { IUser, IReview } from '@/types/typings'
import UserCard from "@/student/components/UserCard.vue";
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
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="evaluation-response" v-if="!isEmpty(props.reviews) && props.reviews?.event?.is_released">

    <div class="datatable" v-for="criteria of props.reviews?.rubric?.criterias" :key="criteria.id">
      <div class="question">{{ criteria.criteria_num }}. {{ criteria.criteria }}</div>

      <table class="standardtable center no-v-line">
        <thead>
        <tr>
          <th :style="{width: '20%'}">
            <div class="">
              <div class="">Peer</div>
              <div class=""></div>
            </div>
          </th>
          <th :style="{width: 80 / props.reviews?.rubric?.loms+'%'}" v-for="lom of props.reviews?.rubric?.loms" :key="lom.id">
            <div class="">
              <div class="">{{ lom.lom_comment }}</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="member of props.members" :key="member.id">
          <td><UserCard :member="member" /></td>
          <td v-for="lom of props.reviews?.rubric?.loms" :key="lom.id">
            <ul class="likert response">
              <li class="input">
                <input
                    class="form-check-input"
                    type="radio"
                    :name="`input_${member?.id}_${criteria?.criteria_num}`"
                    :checked="lom?.lom_num === props.reviews?.evaluator?.find(r => r?.evaluatee === member?.id)?.details?.find(c => c?.criteria_number === criteria?.criteria_num)?.selected_lom"
                    disabled="disabled"
                />
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>

      <table class="standardtable center no-v-line">
        <thead>
        <tr>
          <th style="width: 20%">
            <div class="">
              <div class="">Peer</div>
              <small class=""></small>
            </div>
          </th>
          <th style="width: 80%">
            <div class="">
              <div class="">Comments</div>
              <small class=""></small>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(member, memberIdx) of props.members" :key="member.id">
          <td style="width: 20%"><UserCard :member="member" /></td>
          <td style="width: 80%">
            <ul class="sentence response">
              <li class="text quote">
                {{ props.reviews?.evaluator?.find(r => r?.evaluatee === member.id)?.details?.find(c => c?.criteria_number === criteria?.criteria_num)?.criteria_comment }}
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <div class="datatable">
      <div class="question">{{ props.reviews?.rubric?.criterias?.length+1 }}. {{ 'Please provide overall comments about each peer.' }}</div>
      <div class="description"></div>

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
              <div class="">General Comments</div>
              <div class=""></div>
            </div>
          </th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(member, memberIdx) of props.members" :key="member.id">
          <td><UserCard :member="member" /></td>
          <td>
            <ul class="sentence response">
              <li class="text quote">
                {{ props.reviews?.evaluator?.find(r => r?.evaluatee === member?.id)?.comment }}
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>
