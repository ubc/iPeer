<script lang="ts" setup>
import { ref, reactive, computed, onMounted, defineAsyncComponent } from 'vue'
import { isEmpty } from 'lodash'
import useFetch from '@/composables/useFetch'
import { compareEntries } from '@/helpers'
import { Table } from '@/student/components/tables/datatable'
import Loader from "@/components/Loader.vue";
import Error from '@/components/Error.vue'
import type { User, Event, Course, Group, Penalty } from '@/types/typings'
interface IEventProps {
  event: Event
  course: Course
  group: Group
  penalties: Penalty[]
  late: boolean
  percent_penalty: string
}
interface ICurrentWork {
  current: IEventProps[]
}
interface ICompletedWork {
  completed: IEventProps[]
}
interface IData {
  current: ICurrentWork
  completed: ICompletedWork
}
interface IHttpResponse {
  data: IData
}
const Row = defineAsyncComponent({
  loader: () => import('@/student/components/tables/current/Row.vue'),
  LoadingComponent: '<div>Loading...</div>',
  suspensible: false,
  delay: 2000
})
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  currentUser: User
}>()
// DATA
const loading = ref<boolean>(false)
const error   = ref<object | null>(null)
const columns = reactive<object[]>([
  {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
  {id: 2, key: 'course', value: 'course', name: 'Course', sortable: true, width: '23%'},
  {id: 3, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '30%'},
  {id: 4, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
])
const entries = ref<object[]>([])
const sort = reactive({
  key: 'event',
  column: 'id',
  direction: 'asc'
})
// COMPUTED
const filteredEntries = computed(() => {
  let newEntries = entries.value || []
  if(!isEmpty(newEntries) && Array.isArray(newEntries)) { // newEntries instanceof Array
    newEntries = newEntries?.sort(compareEntries(sort.key, sort.column, sort.direction))
  }
  return newEntries
})
// METHODS
function emitSortBy(event) {
  Object.assign(sort, event)
}
// WATCH
// LIFECYCLE
onMounted(async () => {
  try {
    loading.value = true
    const eventsResponse: Promise<IHttpResponse | any> = await useFetch('/home?work=current', {method: 'GET', timeout: 300})
    entries.value = eventsResponse?.data
  } catch (err: Error | any) {
    error.value = {text: err.message, type: 'error'};
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="current-work mx-4 my-8">
    <Error v-if="error" class="current error" :error="error" />
    <Table :error="error" :columns="columns" @update:sort="emitSortBy">
      <tr v-if="loading">
        <td :colspan="columns.length"><Loader height="200px" /></td>
      </tr>
      <tr v-else-if="!loading && isEmpty(filteredEntries)">
        <td :colspan="columns.length"><div class="flex justify-center items-center p-8">No Content found!</div></td>
      </tr>
      <template v-else-if="!loading && !isEmpty(filteredEntries)">
        <Row v-for="(row, index) of filteredEntries" :key="`${index}_${row?.event?.id}`" :data-index="index" :row="row" />
      </template>
    </Table>
  </div>
</template>
