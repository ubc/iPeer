<script lang="ts" setup>
import { toRef, reactive, watch, computed, onMounted } from 'vue';

import Debugger from "@/components/Debugger.vue";
import {longDateFormat, shortDateFormat, isOverDue, isDueTomorrow, filterEntries, compareEntries} from '@/helpers'
import {
  IconClock,
  IconTimes,
  IconWarning,
  IconCheckmark,
  IconSelector
} from "@/components/icons";

import type {Course, Event, Group, Penalty} from '@/types/typings'
interface Entries {
  event: Event
  course: Course
  group: Group
  penalties: Penalty[]
}
interface Props {
  entries: Entries
  columns: object[]
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()

// DATA
const entries = toRef(props, 'entries')
const columns = toRef(props, 'columns')
const state = reactive({
  sort: {
    key: 'event',
    column: 'id',
    direction: 'desc'
  }
})

// COMPUTED
const filteredEntries = computed(() => {
  let newEntries = entries.value
  return newEntries?.sort(compareEntries(state.sort.key, state.sort.column, state.sort.direction))
})
// METHODS
function handleSortBy(key: string, value: string, direction: string) {
  state.sort.key = key
  state.sort.column = value
  state.sort.direction = direction
}

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="mb-8">
    <Debugger :form="state.paginate" :data="state.sort" :state="state.filter" />
    <table class="standardtable leftalignedtable">
      <thead>
      <tr>
        <th v-for="(column, index) of props.columns" :key="column.id" :data-index="index" :style="{width: column.width}">
          <div class="flex justify-between items-center space-x-4">
            <span>{{ column.name }}</span>
            <svg class="w-5 h-5 cursor-pointer text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                 @click="handleSortBy(column.key, column.value, state.sort.direction === 'asc' ? 'desc' : 'asc')">
              <path v-if="column.sortable && state.sort.column !== column.value"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
              <path v-else-if="column.sortable && state.sort.direction === 'desc' && state.sort.key === column.key"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
              <path v-else-if="column.sortable && state.sort.direction === 'asc' && state.sort.key === column.key"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
            </svg>
          </div>
        </th>
      </tr>
      <!--<tr>
        <th v-for="(column, index) of props.columns" :key="column.id" :style="{width: column.width}">
          <div class="flex justify-between items-center">
            <span>{{ column.name }}</span>
            <IconSelector v-if="column.sortable" @click="handleSort" class="text-slate-600 cursor-pointer" />
          </div>
        </th>
      </tr>-->
      </thead>
      <tbody>
      <tr v-for="(row, index) of filteredEntries" :key="row?.event?.id" :data-index="index">
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
