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
const emit  = defineEmits<{}>()
const props = defineProps<Props>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="evaluation-response" v-if="!isEmpty(props.reviews) && props.reviews?.event?.is_released">

    <div class="peer-evaluation">
      <h2 class="visually-hidden hidden">Peer Evaluation</h2>
      <template v-for="question of props.reviews?.mixed?.questions" :key="question.id">
        <div v-if="!question.self_eval" class="datatable">
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
            <tr v-for="member of props.members" :key="member.id">
              <td><UserCard class="font-medium" :member="member" /></td>
              <td v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <ul class="likert response">
                  <li class="input">
                    <input
                        type="radio"
                        class="form-check-input"
                        :name="`input_${question.id}_${member.id}`"
                        value=""
                        :checked="Number(lom.scale_level) === Number(props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.selected_lom) ? true : false"
                        disabled="disabled"
                    />
                  </li>
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
                  <div class="">Comments</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="member of props.members" :key="member.id">
              <td><UserCard class="font-medium" :member="member" /></td>
              <td>
                <ul class="sentence response">
                  <li class="text quote">
                    {{ props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.question_comment }}
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
                  <div class="">Comments</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="member of props.members" :key="member.id">
              <td><UserCard class="font-medium" :member="member" /></td>
              <td>
                <ul class="paragraph response">
                  <li class="text quote">
                    {{ props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.question_comment }}
                  </li>
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
        <div v-if="question.self_eval" class="px-8 py-2">
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
              <td><UserCard class="font-medium" :member="{first_name: 'Yourself', last_name: ''}" /></td>
              <td v-for="lom of question.desc" :key="lom.id" :style="{width: 80 / question.desc?.length+'%'}">
                <ul class="likert response">
                  <li class="input">
                    <input
                        type="radio"
                        class="form-check-input"
                        :name="`input_${question.id}_${member.id}`"
                        value=""
                        disabled="disabled"
                        :checked="Number(lom.scale_level) === Number(props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.selected_lom) ? true : false"
                    />
                  </li>
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
                  <div class="">Comments</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'Yourself', last_name: ''}" /></td>
              <td>
                <ul class="sentence response">
                  <li class="text quote">
                    {{ props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.question_comment }}
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
                  <div class="">Comments</div>
                  <div class=""></div>
                </div>
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td><UserCard class="font-medium" :member="{first_name: 'Yourself', last_name: ''}" /></td>
              <td>
                <ul class="paragraph response">
                  <li class="text quote">
                    {{ props.reviews?.evaluator?.find(review => review?.evaluatee === member?.id)?.details?.find(detail => detail.question_number === question?.question_num)?.question_comment }}
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
