<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { isEmpty } from 'lodash'
import UserCard from "@/student/components/UserCard.vue";
import Debugger from "@/components/Debugger.vue";

import type { IUser, IReview } from '@/types/typings'

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
  <div class="evaluation-reviews" v-if="!isEmpty(props.reviews) && props.reviews?.event?.is_released">

    <div class="peer-evaluation">
      <h2 class="hidden visually-hidden">Peer Evaluation</h2>
      <template v-for="question of props.reviews?.mixed?.questions" :key="question.id">
        <div v-if="!question.self_eval" class="datatable">
          <div class="question -ml-4 text-lg font-serif">
            {{ question.question_num }}. {{ question.title }}
            <span class="text-base text-slate-500" v-if="!isEmpty(props.reviews?.evaluatee) && question.multiplier > 0">
               — {{ props.reviews?.evaluatee?.map(review => review?.details?.find(detail => detail?.question_number === question?.question_num))?.reduce((acc, cur) => acc + Number(cur.grade), 0).toFixed(1) / props.members?.length }} / {{ question.multiplier }} marks
            </span>
          </div>
          <!-- Likert Question -->
          <table v-if="question?.type === 'Likert'" class="standardtable center no-v-line">
            <thead>
            <tr>
              <th style="width: 20%">
                <div class="">
                  <div class="">Peer</div>
                  <div class=""></div>
                </div>
              </th>
              <th v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <div class="">
                  <div class="">{{ lom.descriptor }}</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'Peers', last_name: 'said'}" /></td>
              <td v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <ul class="likert review">
                  <li class="input"
                      v-for="detail of props.reviews?.evaluatee?.map(item => item?.details?.find(detail => detail?.question_number === question?.question_num)) "
                      :key="detail.id">{{ lom.scale_level === detail.selected_lom ? 'x' : '' }}</li>
                </ul>
              </td>
            </tr>
            <tr v-if="props.reviews?.event?.self_eval">
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <ul class="likert review">
                  <li class="input"
                      v-for="detail of props.reviews?.evaluator?.map(item => item?.details?.find(detail => detail?.question_number === question?.question_num))"
                      :key="detail.id">{{ lom.scale_level === detail.selected_lom ? 'x' : '' }}</li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Sentence Question -->
          <table v-if="question?.type === 'Sentence'" class="standardtable center no-v-line">
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
                  <div class="">Comments About Your Teamwork</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'Peers', last_name: 'said'}" /></td>
              <td>
                <ul class="sentence review">
                  <li class="text quote"
                      v-for="detail of props.reviews?.evaluatee.map(item => item.details.find(detail => (detail.question_number === question?.question_num)))"
                      :key="detail.id">{{ detail.question_comment }}</li>
                </ul>
              </td>
            </tr>
            <tr v-if="props.reviews?.event?.self_eval">
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td>
                <ul class="sentence review">
                  <li class="text quote"
                      v-for="comment of props.reviews?.evaluator.map(item => item.details.find(detail => (detail.question_number === question?.question_num)))"
                      :key="comment.id">{{ comment.question_comment }}</li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Paragraph Question -->
          <table v-if="question?.type === 'Paragraph'" class="standardtable center no-v-line">
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
                  <div class="">Comments About Your Teamwork</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'Peers', last_name: 'said'}" /></td>
              <td>
                <ul class="paragraph review">
                  <li class="text quote"
                      v-for="comment of props.reviews?.evaluatee.map(item => item.details.find(detail => (detail.question_number === question?.question_num)))"
                      :key="comment.id">{{ comment.question_comment.trim() }}</li>
                </ul>
              </td>
            </tr>
            <tr v-if="props.reviews?.event?.self_eval">
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td>
                <ul class="paragraph review">
                  <li class="text quote"
                      v-for="comment of props.reviews?.evaluator.map(item => item.details.find(detail => (detail.question_number === question?.question_num)))"
                      :key="comment.id">{{ comment.question_comment }}</li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Range Question -->
        </div>
      </template>

      <div class="hidden visually-hidden px-8 py-2">
        <hr />
        <table class="standardtable center no-v-line">
          <thead>
          <tr>
            <th style="width: 20%">
              <div class="">
                <div class="">Peer</div>
                <div class=""></div>
              </div>
            </th>
            <th style="width: 45%">
              <div class="">
                <div class="">Your Relative Contribution</div>
                <div class=""></div>
              </div>
            </th>
            <th style="width: 35%">
              <div class="">
                <div class="">Your Average Points</div>
                <div class=""></div>
              </div>
            </th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td><UserCard class="font-medium" :member="{first_name: '2 Peers', last_name: '+ You'}" /></td>
            <td>--</td>
            <td>--</td>
          </tr>
          </tbody>
        </table>
        <hr />
      </div>
    </div>


    <div class="self-evaluation mt-8" v-if="props.reviews?.event?.self_eval">
      <h2 class="hidden visually-hidden">Self Evaluation</h2>
      <template v-for="question of props.reviews?.mixed?.questions" :key="question.id">
        <div class="px-8 py-2" v-if="question.self_eval">
          <div class="question -ml-4 text-lg font-serif">
            {{ question.question_num }}. {{ question.title }}
            <span class="average-grade" v-if="!isEmpty(evaluatee)">
              — {{ useReview.peer(evaluatee).where(question.question_num).reduce('grade', 'average').result().toFixed(1) }} /
              {{ question.multiplier }} marks
            </span>
          </div>
          <!-- Likert Question -->
          <table v-if="question?.type === 'Likert'" class="standardtable center no-v-line">
            <thead>
            <tr>
              <th style="width: 20%">
                <div class="">
                  <div class="">Peer</div>
                  <div class=""></div>
                </div>
              </th>
              <th v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <div class="">
                  <div class="">{{ lom.descriptor }}</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <ul class="likert review">
                  <li class="input">--</li>

                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Sentence Question -->
          <table v-if="question?.type === 'Sentence'" class="standardtable center no-v-line">
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
                  <div class="">Comments About Your Teamwork</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td>
                <ul class="sentence review">
                  <li class="text quote">
                    {{ props.currentUser?.id }}/
                  </li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Paragraph Question -->
          <table v-if="question?.type === 'Paragraph'" class="standardtable center no-v-line">
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
                  <div class="">Comments About Your Teamwork</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'You', last_name: 'said'}" /></td>
              <td>
                <ul class="paragraph review">
                  <li class="text quote">
                    {{ props.currentUser?.id }}/
                  </li>
                </ul>
              </td>
            </tr>
            </tbody>
          </table>
          <!-- Range Question -->

        </div>
      </template>
    </div>


  </div>
</template>