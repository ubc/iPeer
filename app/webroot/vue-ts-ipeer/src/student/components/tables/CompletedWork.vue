<script lang="ts" setup>
import {reactive, computed, ref, toRef, watch, onMounted} from 'vue'
import { filter, map, cloneDeep } from 'lodash'
import { isEmpty } from 'lodash'
import {
  longDateFormat,
  shortDateFormat,
  isOverDue,
  isDueTomorrow,
  compareEntries,
  filterEntries,
  unique, paginateEntries,
} from '@/helpers'
import Debugger from "@/components/Debugger.vue";

import { CheckField, SelectField } from '@/components/fields'

import { IconSelector, IconCheckmark, IconTimes } from '@/components/icons'

import type { Event, Course, Group, Penalty } from '@/types/typings'
interface Entry {
  event: Event
  course: Course
  group: Group
  penalties: Penalty[]
}
interface Props {
  entries: Entry[]
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
    direction: 'asc'
  },
  filter: {
    timeframe: 'all',
    limit: [],
    search: ''
  },
  paginate: {
    page: 1,
    limit: 10,
    pages: computed<number>(() => Math.ceil(entries.value?.length / Number(state.paginate.limit)) || 1),
    offset: computed<number>(() => (Number(state.paginate.page) * Number(state.paginate.limit) - Number(state.paginate.limit))),
    end: computed<number>(() => Number(state.paginate.offset) + Number(state.paginate.limit))
  }
})


// COMPUTED
const start = computed<number>(() => {
  return (Number(state.paginate.page) - 1) * Number(state.paginate.limit)
})
const end = computed<number>(() => {
  return start.value + Number(state.paginate.limit)
})
const offset = computed<number>(() => {
  return (Number(state.paginate.page) * Number(state.paginate.limit) - Number(state.paginate.limit))
})
const filteredEntries = computed(() => {
  let newEntries = entries.value || []

  if(!isEmpty(newEntries)) {

    newEntries = newEntries?.sort(compareEntries(state.sort.key, state.sort.column, state.sort.direction))

    newEntries = Object.entries(state.filter) ? filterEntries(newEntries, state.filter) : newEntries

    newEntries = paginateEntries(newEntries, state.paginate.offset, state.paginate.end)

    // newEntries = paginateEntries(newEntries, start.value, end.value)

    return newEntries
  }
})
const options = computed(() => unique(props.entries, 'course', 'term').sort((a,b) => a.localeCompare(b)))

// METHODS
function handleSortBy(key: string, value: string, direction: string) {
  state.sort.key = key
  state.sort.column = value
  state.sort.direction = direction
}
function updateData(event: HTMLInputElement | any) {
  let { name, value, checked } = event.target
  let dataValue = event.target.dataset.value  // ['data-value']

  if(event.target.type === 'checkbox') {
    const limit: string[] = state.filter[name]

    if(checked) {
      limit.push(dataValue)
    } else {
      const index = limit.indexOf(dataValue)
      limit.splice(index, 1)
    }
  } else {
    state.filter[name] = value
  }
}
function handlePaginationChanges(e) {
  e.preventDefault()
  state.paginate.page = 1
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="mt-4 mb-8">
    <Debugger :form="Object.assign({}, {paginate: state.paginate}, {displaying: state.displaying})" :data="state.sort" :state="state.filter" />

    <div class="flex flex-col text-sm space-y-2 my-4">
      <div class="timeframe flex flex-col items-center md:flex-row md:space-x-12">
        <div class="label">Timeframe:</div>
        <SelectField
            :default="state.filter.timeframe"
            :options="options"
            :name="'timeframe'"
            @change:select="updateData"
        />
      </div>
      <div class="limit flex flex-col items-center md:flex-row md:space-x-16">
        <div class="label">Limit To:</div>
        <div class="flex items-center space-x-4">
          <CheckField name="limit" value="can_edit" label="Work I can still edit" @change:input="updateData" />
          <CheckField name="limit" value="can_view" label="Peer reviews of me I can see" @change:input="updateData" />
        </div>
      </div>
    </div>

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
      </thead>
      <tbody>
      <tr v-for="(row, index) of filteredEntries" :key="index" :data-index="index">
        <td>
          <div class="work">
            <div class="event-title text-base text-slate-900 leading-5 font-normal tracking-wide">{{ row?.event?.title }}</div>
            <div class="group-name text-sm text-slate-700 leading-4 font-light tracking-wide">{{ row?.group?.group_name }}</div>
          </div>
        </td>
        <td>
          <div class="status flex items-center space-x-1 text-sm">
            <component :is="row?.event?.record_status === 'A' ? IconCheckmark : IconTimes" class="w-5 h-5" />
            <span :class="row?.event?.record_status === 'A' ? 'completed' : 'not-done'">{{ row?.event?.record_status === 'A' ? 'Completed' : 'Not Done' }}</span>
          </div>
        </td>
        <td>
          <div class="courses">
            <div class="text-sm text-slate-900 leading-5 font-light">{{ row?.course?.course }}</div>
            <div class="text-sm text-slate-700 leading-4 font-light tracking-wider" v-if="row?.course?.term">({{ row?.course?.term }})</div>
          </div>
        </td>
        <td>
          <div class="due flex justify-start items-center space-x-2">
            <IconClock class="w-6 h-6" />
            <span class="text-sm text-slate-900 leading-4 font-light">{{ shortDateFormat(row?.event?.due_date) }}</span>
          </div>
        </td>
        <td>
          <!--  NOTE:: calculate the cell width dynamically to maintain the column with aspect ratio  -->
          <div class="flex" style="min-width: 157.5px">
            <router-link
                v-if="row?.event?.is_submitted === '1' && row?.event?.is_result_released"
                :class="`button ${true ? 'submit' : ''} flex-1 text-center btn-lg`"
                :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >See Reviews of Me</router-link>
            <router-link
                v-if="row?.event?.is_submitted === '1' && !row?.event?.is_result_released"
                :class="`button ${true ? 'submit' : ''} flex-1 text-center btn-lg`"
                :to="{ name: 'evaluation.edit', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >Edit My Response</router-link>
            <span v-if="
            row?.event?.is_ended &&
            (new Date(row?.event?.result_release_date_begin).toLocaleDateString('en-CA') >= new Date().toLocaleDateString('en-CA'))
            " class="text-sm text-slate-700 leading-4 font-light tracking-wide">Peers' reviews of you will be available starting {{ shortDateFormat(row?.event?.result_release_date_begin) }}</span>
          </div>
        </td>
      </tr>
      </tbody>
    </table>


    <div class="pagination flex justify-between items-center text-sm text-slate-700 leading-relaxed mt-4">
      <div class="displaying text-slate-700">
        Displaying
        <!--    NOTE:: Reset the pagination to start from the beginning as the per_page changes to keep track...    -->
        <!--    NOTE:: Keep in mind to double check the button active state as well    -->
        <select v-model="state.paginate.limit" @change="handlePaginationChanges">
          <option :value="1">1</option>
          <option :value="2">2</option>
          <option :value="3">3</option>
          <option :value="4">4</option>
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="25">25</option>
        </select>
        from {{ state.paginate.offset+1 }} - {{ Number(state.paginate.end) > Number(entries?.length) ? entries?.length : state.paginate.end }} of {{ entries?.length }}
      </div>
      <div class="paginate text-slate-700">
        <ul class="flex">
          <li>
            <button
                :class="`paginate ${state.paginate.page === 1 ? 'disabled' : ''}`"
                :disabled="state.paginate.page === 1"
                @click="state.paginate.page--"
            >prev</button>
          </li>
          <li v-for="(_, index) of state.paginate.pages" :key="index">
            <button
                :class="`paginate ${state.paginate.page === (index+1) ? 'active' : ''}`"
                @click="state.paginate.page = (index + 1)"
            >{{ index + 1 }}</button>
            <!-- NOTE:: due-to-styling issue i'm not adding :disabled="state.paginate.page === index+1" on the button -->
          </li>
          <li>
            <button
                :class="`paginate ${state.paginate.page === state.paginate.pages ? 'disabled' : ''}`"
                :disabled="state.paginate.page === state.paginate.pages"
                @click="state.paginate.page++"
            >next</button>
          </li>
        </ul>
      </div>
    </div>

  </div>
</template>
