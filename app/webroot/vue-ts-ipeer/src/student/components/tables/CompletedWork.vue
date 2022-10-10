<script lang="ts" setup>
import { reactive, computed, ref, toRef, watch } from 'vue'
// import { filter, map, cloneDeep } from 'lodash'
import _ from 'lodash'
import {longDateFormat, shortDateFormat, isOverDue, isDueTomorrow, compare, unique} from '@/helpers'
import Debugger from "@/components/Debugger.vue";

import { Event, Course, Group, Penalty } from '@/types/typings'

import { CheckField, SelectField } from '@/components/fields'

import { IconSelector } from '@/components/icons'

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

const data = reactive({
  page: '',
  per_page: '',
  offset: '',

})
const filter = reactive({
  timeframe: 'all',
  limit: [], // filter by []
})
const sort = reactive({
  key: 'event',
  column: 'id',
  direction: 'desc'
})
// const newEntries = toRef(props, 'entries')



// COMPUTED
// const options = computed(() => ['2018 W1', '2019 W1', '2020 W1', '2021 W1', '2022 W1'])
const filteredEntries = computed(() => {
  const newEntries = toRef(props, 'entries')

  // console.log({'sort': sort})

  // return newEntries.value?.sort((a, b) => {
  //   return a[sort.collection][sort.column].localeCompare(b[sort.collection][sort.column])
  // })

  const sortedEntries = newEntries.value?.sort(compare(sort.key, sort.column, sort.direction))

  const filtered = _.filter(sortedEntries, (entry) => entry)

  // return compare(newEntries.value, sort)

  return filtered
})
const options = computed(() => unique(filteredEntries.value, 'course', 'term'))

// METHODS
function handleSortBy(key, value, direction) {
  sort.key = key
  sort.column = value
  sort.direction = direction
}
function updateData(event) {
  let { name, value, checked } = event.target
  // let name = event.target.name
  // let value = event.target.value
  let dataValue = event.target.dataset.value// ['data-value']

  if(event.target.type === 'checkbox') {
    // let isChecked = event.target.checked
    const limit: string[] = filter[name]

    if(checked) {
      limit.push(dataValue)
    } else {
      const index = limit.indexOf(dataValue)
      limit.splice(index, 1)
    }
  } else {
    filter[name] = value
  }
}

// WATCH
// watch(() => cloneDeep(sort), () => {
//   filteredEntries.value
// }, { deep: true })

// LIFECYCLE

</script>

<template>
  <div class="mt-4 mb-8">
    <Debugger :form="data" :data="sort" :state="filter" />

    <div class="flex flex-col space-y-4 mx-4 my-4">
      <div class="timeframe flex flex-col items-center md:flex-row md:space-x-12">
        <div class="label">Timeframe:</div>
        <SelectField
            :default="filter.timeframe"
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
        <th v-for="(column, index) of props.columns" :key="column.id" :style="{width: column.width}">
          <div class="flex justify-between items-center space-x-4">
            <span>{{ column.name }}</span>
            <svg class="w-5 h-5 cursor-pointer text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                 @click="handleSortBy(column.key, column.value, sort.direction === 'asc' ? 'desc' : 'asc')">
              <path v-if="column.sortable && sort.column !== column.value"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
              <path v-else-if="column.sortable && sort.direction === 'desc' && sort.key === column.key"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
              <path v-else-if="column.sortable && sort.direction === 'asc' && sort.key === column.key"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
            </svg>
          </div>
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, idx) of filteredEntries" :key="idx">
        <td>
          <div class="work">
            <div class="event-title text-sm text-slate-900 leading-relaxed font-semibold">{{ row?.event?.id }}. {{ row?.event?.title }}</div>
            <div class="group-name text-sm text-slate-700 leading-relaxed font-normal">{{ row?.group?.group_name }}</div>
          </div>
        </td>
        <td>
          <div class="flex items-center space-x-1 text-sm">
            <component :is="row?.event?.record_status === 'A' ? IconCheckmark : IconTimes" class="w-6 h-6" />
            <span :class="row?.event?.record_status === 'A' ? 'completed' : 'not-done'">{{ row?.event?.record_status === 'A' ? 'Completed' : 'Not Done' }}</span>
          </div>
        </td>
        <td>
          <div class="">
            <div class="text-sm text-slate-900 font-medium">{{ row?.course?.course }}</div>
            <div class="text-sm text-slate-700 font-normal tracking-wider" v-if="row?.course?.term">({{ row?.course?.term }})</div>
          </div>
        </td>
        <td>
          <div class="flex justify-start items-center space-x-2">
            <IconClock class="w-6 h-6" />
            <span class="text-sm text-slate-900 leading-4">{{ shortDateFormat(row?.event?.due_date) }}</span>
          </div>
        </td>
        <td>
          <div class="flex">
            <router-link
                :class="`button ${true ? 'submit' : ''} flex-1 text-center btn-lg`"
                :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >See Reviews of Me</router-link>
          </div>
        </td>
      </tr>
      </tbody>
    </table>


  </div>
</template>

<style lang="scss">
  table.standardtable thead th {
    @apply font-serif font-medium;
    @apply text-lg;
    @apply py-3 pl-4;
  }
  table.standardtable tbody td {
    @apply py-2 px-4;
  }
</style>