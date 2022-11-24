<script lang="ts" setup>
import { ref, shallowRef, reactive, computed, onMounted, defineAsyncComponent } from 'vue'
import { isEmpty } from 'lodash'
import { useStore } from '@/stores/main'
import { useEventsStore } from '@/stores/events'
import {  longDateFormat, isOverDue, isDueTomorrow, compareEntries, sleep } from '@/helpers'
import Loader from '@/components/Loader.vue'
import { Table } from '@/student/components/tables/datatable'
import { IconClock, IconWarning } from '@/components/icons'
import type { IUser, IEvent } from '@/types/typings'
interface Props {currentUser?: IUser}
// REFERENCES
const emit        = defineEmits<{}>()
const props       = defineProps<Props>()
const store       = useStore()
const eventsStore = useEventsStore()
// DATA
const columns     = shallowRef<object[]>([
  {id: 1, key: 'event', value: 'title', name: 'Work', sortable: false, width: '30%'},
  {id: 2, key: 'course', value: 'course', name: 'Course', sortable: true, width: '23%'},
  {id: 3, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '30%'},
  {id: 4, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
])
const sort        = reactive({
  key: 'event',
  column: 'id',
  direction: 'asc'
})
// COMPUTED
const loading     = computed<boolean>(() => eventsStore.loading)
const error       = computed<object|null>(() => eventsStore.error)
const hasError    = computed<boolean>(() => eventsStore.hasError)
const entries     = computed<IEvent[]>(() => eventsStore.getCurrentEvents)
//
const filteredEntries = computed(() => {
  let newEntries: IEvent[] = entries.value || []
  if (!isEmpty(newEntries) && Array.isArray(newEntries)) { // newEntries instanceof Array
    newEntries = newEntries?.sort(compareEntries(sort.key, sort.column, sort.direction))
  }
  return newEntries
}) as unknown as IEvent[]
// METHODS
  function emitSortBy(event: object) {
    Object.assign(sort, event)
  }
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="current-work mx-4 my-8">
    <Table :error="error" :columns="columns" @update:sort="emitSortBy" class="no-v-line">
      <tr v-if="loading">
        <td :colspan="columns.length">
          <Loader height="100px" />
        </td>
      </tr>
      <tr v-if="!loading && !error && filteredEntries.length === 0">
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
            <div class="course-course">
              <div class="course">{{ row?.course?.course }}</div>
            </div>
          </td>
          <td>
            <div class="due-date">
              <template v-if="isOverDue(row?.event?.due_date)">
                <div class="badge due" variant="gold">
                  <span class="due-over">OVERDUE</span>
                  <span class="due-date">
                    <IconWarning class="flex-none w-6 h-6 pt-0.5" />
                    <span class="text">Hurry! Late evaluations are being allowed for a limited time.</span>
                  </span>
                </div>
              </template>
              <template v-else>
                <div class="badge due" variant="gold">
                  <div class="due-tomorrow" v-if="isDueTomorrow(row?.event?.due_date)">Due tomorrow</div>
                  <span class="due-date" v-if="!isOverDue(row?.event?.due_date)">
                    <IconClock class="icon" />
                    <span class="text">{{ longDateFormat(row?.event?.due_date) }}</span>
                  </span>
                </div>
              </template>
            </div>
          </td>
          <td>
            <div class="action">
              <router-link
                  :class="`button ${row?.event?.is_submitted==='0' ? 'default' : 'primary'} flex-1 text-center`"
                  :to="{ name: 'evaluation.make', params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
                {{ row?.event?.is_submitted==='0' ? 'Continue Eval.' : 'Evaluate Peers' }}
              </router-link>
            </div>
          </td>
        </tr>
      </template>
    </Table>
  </div>
</template>
