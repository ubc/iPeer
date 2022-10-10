<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

import { longDateFormat, shortDateFormat, isOverDue, isDueTomorrow } from '@/helpers'
import {
  IconClock,
  IconTimes,
  IconWarning,
  IconCheckmark,
  IconSelector
} from "@/components/icons";

interface Props {
  entries: object[]
  columns: object[]
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="mb-8">

    <table class="standardtable leftalignedtable">
      <thead>
      <tr>
        <th :style="{width: column.width}" v-for="(column, index) of props.columns" :key="column.id">
          <div class="flex justify-between items-center">
            <span>{{ column.name }}</span>
            <IconSelector v-if="column.sortable" @click="handleSort" class="text-slate-600 cursor-pointer" />
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, index) of entries" :key="row?.event?.id" :data-index="index">
        <td>
          <div class="work">
            <div class="event-title text-sm text-slate-900 leading-relaxed font-semibold">{{ row?.event?.title }}</div>
            <div class="group-name text-sm text-slate-700 leading-relaxed font-normal">{{ row?.group?.group_name }}</div>
          </div>
        </td>
        <td>
          <div class="course-course">
            <div class="text-sm text-slate-900 font-medium">{{ row?.course?.course }}</div>
          </div>
        </td>
        <td>
          <div class="due-date inline-block flex-col">
            <template v-if="isOverDue(row?.event?.due_date)">
              <span class="w-14 flex items-center text-gray-100 text-xs bg-gold-600 pt-0.5 pb-0.5 px-2">OVERDUE</span>
              <span class="flex justify-start items-start space-x-2">
                <IconWarning class="flex-none w-6 h-6 pt-0.5" />
                <span class="text-sm text-slate-900 leading-4">Hurry! Late evaluations are being allowed for a limited time.</span>
              </span>
            </template>
            <span v-if="isDueTomorrow(row?.event?.due_date)" class="inline-block text-sm text-gold-600">Due tomorrow</span>
            <span v-if="!isOverDue(row?.event?.due_date)" class="flex justify-start items-center space-x-2">
              <IconClock class="flex-none w-6 h-6" />
              <span class="text-sm text-slate-900 leading-4">{{ longDateFormat(row?.event?.due_date) }}</span>
            </span>
          </div>
        </td>
        <td>
          <div class="flex">
            <router-link :class="`button btn-lg ${row?.event?.is_submitted==='0' ? 'default' : 'submit'} flex-1 text-center`" :to="{ name: `evaluation.${row?.event?.is_submitted==='0' ? 'edit' : 'make'}`, params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
              {{ row?.event?.is_submitted==='0' ? 'Continue Eval.' : 'Evaluate Peers' }}
            </router-link>
          </div>
        </td>
      </tr>
      </tbody>
    </table>

  </div>
</template>

<style lang="scss" scoped>

</style>
