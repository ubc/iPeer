<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import {
  longDateFormat,
  shortDateFormat,
  isOverDue,
  isDueTomorrow,
  filterEntries,
  compareEntries
} from '@/helpers'
import {
  IconClock,
  IconTimes,
  IconWarning,
  IconCheckmark,
  IconSelector
} from "@/components/icons";

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  row: object
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <tr v-if="!row">
    <td colspan="4">
      <div class="flex justify-center items-center p-8">No Content found!</div>
    </td>
  </tr>
  <tr v-else>
    <td>
      <div class="work">
        <div class="event-title text-base text-slate-900 leading-5 font-normal tracking-wide">{{ row?.event?.title }}</div>
        <div class="group-name text-sm text-slate-700 leading-4 font-light tracking-wide">{{ row?.group?.group_name }}</div>
      </div>
    </td>
    <td>
      <div class="course-course">
        <div class="text-sm text-slate-900 font-light">{{ row?.course?.course }}</div>
      </div>
    </td>
    <td>
      <div class="due-date inline-block flex-col font-light">
        <template v-if="isOverDue(row?.event?.due_date)">
          <span class="w-14 flex items-center text-gray-100 text-xs font-normal bg-gold-600 pt-0.5 pb-0 px-2">OVERDUE</span>
          <span class="flex justify-start items-start space-x-2">
            <IconWarning class="flex-none w-6 h-6 pt-0.5" />
            <span class="text-sm text-slate-900 leading-4">Hurry! Late evaluations are being allowed for a limited time.</span>
          </span>
        </template>
        <span v-if="isDueTomorrow(row?.event?.due_date)" class="inline-block text-sm text-gold-600 font-normal">Due tomorrow</span>
        <span v-if="!isOverDue(row?.event?.due_date)" class="flex justify-start items-center space-x-2">
          <IconClock class="flex-none w-6 h-6" />
          <span class="text-sm text-slate-900 leading-4">{{ longDateFormat(row?.event?.due_date) }}</span>
        </span>
      </div>
    </td>
    <td>
      <div class="flex">
        <router-link
            :class="`button ${row?.event?.is_submitted==='0' ? 'default' : 'submit'} flex-1 text-center`"
            :to="{ name: 'evaluation.make', params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
          {{ row?.event?.is_submitted==='0' ? 'Continue Eval.' : 'Evaluate Peers' }}
        </router-link>
      </div>
    </td>
  </tr>
</template>
