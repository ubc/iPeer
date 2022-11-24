<script lang="ts" setup>
import {ref, shallowRef, reactive, computed, onMounted, defineAsyncComponent, watch} from 'vue';
import { isEmpty } from 'lodash'
import { useStore } from '@/stores/main'
import { useEventsStore } from '@/stores/events'
import { compareEntries, filterEntries, paginateEntries, unique, shortDateFormat } from '@/helpers'

import Loader from '@/components/Loader.vue'
import LimitTo from '@/student/components/LimitTo.vue'
import TimeFrame from '@/student/components/TimeFrame.vue'
import { Table } from '@/student/components/tables/datatable'
import Pagination from '@/components/Pagination.vue'
import { IconClock, IconTimesSolid, IconCheckSolid } from '@/components/icons'

import type { IUser, IEvent } from '@/types/typings'
interface Props {currentUser?: IUser}
// REFERENCES
const emit        = defineEmits<{}>()
const props       = defineProps<Props>()
const store       = useStore()
const eventsStore = useEventsStore()
// DATA
const columns = shallowRef<object>([
  {id: 1, key: 'event', value: 'title', name: 'Work', sortable: false, width: '30%'},
  {id: 2, key: 'event', value: 'is_result_released', name: 'Your Status', sortable: true, width: '18%'}, // record_status
  {id: 3, key: 'course', value: 'course', name: 'Course', sortable: true, width: '17.5%'},
  {id: 4, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '17.5%'},
  {id: 5, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
])
const sort = reactive({
  key: 'event',
  column: 'id',
  direction: 'asc'
})
const filter = reactive({
  timeframe: 'all',
  limit: [],
  search: '', // TODO:: would be part of the admin/instructor list
})
const limit_options = shallowRef([
  {
    name: 'limit',
    value: 'can_edit',
    label: 'Work I can still edit',
  },{
    name: 'limit',
    value: 'can_view',
    label: 'Peer reviews of me I can see',
  }
])
// COMPUTED
const loading     = computed<boolean>(() => eventsStore.loading)
const error       = computed<object|null>(() => eventsStore.error)
const hasError    = computed<boolean>(() => eventsStore.hasError)
const entries     = computed<IEvent[]>(() => eventsStore.getCompletedEvents)
//
const filteredEntries = computed(() => {
  let newEntries: IEvent[] = entries.value || []
  if (!isEmpty(newEntries) && Array.isArray(newEntries)) { // newEntries instanceof Array
    newEntries = newEntries?.sort(compareEntries(sort.key, sort.column, sort.direction));
    // newEntries = Object.entries(filter) ? filterEntries(newEntries, filter) : newEntries;
    newEntries = filter ? filterEntries(newEntries, filter) : newEntries;
    newEntries = paginateEntries(newEntries, paginate.offset, paginate.end);
    return newEntries;
  }
  return newEntries;
}) as unknown as IEvent[]
const options   = computed(() => {
  if(entries.value) {
    return unique(entries.value, 'course', 'term').sort((a,b) => a.localeCompare(b)).filter(x=>x)
  }
  return []
})
const paginate = reactive({
  page: 1,
  limit: 5,
  total: computed(() => entries.value?.length),
  pages: computed<number>(() => Math.ceil(entries.value?.length / Number(paginate.limit)) || 1),
  offset: computed<number>(() => (Number(paginate.page) * Number(paginate.limit) - Number(paginate.limit))), // start
  end: computed<number>(() => Number(paginate.offset) + Number(paginate.limit))
})
// METHODS
function emitSortBy(event: object) {
  Object.assign(sort, event)
}
function updateData(event: HTMLInputElement | any) {
  let { name, value, checked } = event.target
  let dataValue = event.target.dataset.value  // ['data-value']
  if(event.target.type === 'checkbox') {
    const targetKey: string[] = filter[name]
    if(checked) {
      targetKey.push(dataValue)
    } else {
      const index = targetKey.indexOf(dataValue)
      targetKey.splice(index, 1)
    }
  } else {
    filter[name] = value
  }
  if(filter.timeframe === 'all' && !event.target.checked && filter.limit.length === 0) {
    paginate.pages = computed<number>(() => Math.ceil(entries.value?.length / Number(paginate.limit)) || 1)
  } else {
    paginate.pages = computed<number>(() => Math.ceil(filteredEntries.value?.length / Number(paginate.limit)) || 1)
  }
  paginate.page = 1
}
// WATCH
watch([ sort, filter, paginate ], () => {
  filteredEntries.value
}, { deep: true })
// LIFECYCLE
</script>

<template>
  <div class="completed-work mx-4 my-8">
    <div class="flex flex-col my-4 space-y-6">
      <TimeFrame :default="filter.timeframe" :options="options" :name="'timeframe'" @update:event="updateData" />
      <LimitTo label="Limit To:" :options="limit_options" @update:event="updateData" :disabled="isEmpty(entries)" />
    </div>
    <Table :error="error" :columns="columns" @update:sort="emitSortBy" class="no-v-line">
      <tr v-if="loading">
        <td :colspan="columns.length">
          <Loader height="200px" />
        </td>
      </tr>
      <tr v-if="!loading && !error && isEmpty(filteredEntries)">
        <td :colspan="columns.length">
          <div class="no-content-found">No Content found!</div>
        </td>
      </tr>
      <template v-else>
        <tr v-for="(row, index) of filteredEntries" :key="`${index}_${row?.event?.id}`">
          <td>
            <div class="work">
              <div class="event-title">{{ row?.event?.title }}</div>
              <div class="group-name">{{ row?.group?.group_name }}</div>
            </div>
          </td>
          <td>
            <div class="your-status">
              <component class="icon" :is="row?.event?.is_submitted === '1' && row?.event?.is_result_released ? IconCheckSolid : IconTimesSolid" />
              <template v-if="row?.event?.is_submitted === '1' && row?.event?.is_result_released">
                <router-link class="text completed" :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
                  Completed
                </router-link>
              </template>
              <template v-else>
                <span class="text not-done">Not Done</span>
              </template>
            </div>
          </td>
          <td>
            <div class="course-course">
              <div class="course">{{ row?.course?.course }}</div>
              <div class="term" v-if="row?.course?.term">({{ row?.course?.term }})</div>
            </div>
          </td>
          <td>
            <div class="due-date">
              <div class="date font-normal">
                {{ shortDateFormat(row?.event?.due_date) }}
              </div>
            </div>
          </td>
          <td>
            <div class="action">
              <router-link
                  v-if="row?.event?.is_released && row?.event?.is_result_released && !row?.event?.is_ended"
                  :class="`button submit flex-1 text-center`"
                  :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >
                See Reviews of Me
              </router-link>
              <router-link
                  :class="`button submit flex-1 text-center`"
                  v-if="row?.event?.is_released && !row?.event?.is_result_released && !row?.event?.is_ended"
                  :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >
                Edit My Response
              </router-link>
              <span
                  class="text"
                  v-if="row?.event?.is_released && row?.event?.is_result_released && !row?.event?.is_ended && (new Date(row?.event?.result_release_date_begin).toLocaleDateString('en-CA') >= new Date().toLocaleDateString('en-CA'))">
              Peers' reviews of you will be available starting {{ shortDateFormat(row?.event?.result_release_date_begin) }}
            </span>
            </div>
          </td>
        </tr>
      </template>
    </Table>
    <Pagination :paginate="paginate" />
  </div>
</template>
