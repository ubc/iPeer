<script lang="ts" setup>
import {ref, reactive, computed, onMounted, defineAsyncComponent, watch} from 'vue';
import { isEmpty } from 'lodash'
import useFetch from '@/composables/useFetch'
import { compareEntries, filterEntries, paginateEntries, unique } from '@/helpers'

import { Table } from '@/student/components/tables/datatable'
import Pagination from '@/components/Pagination.vue'
import Loader from "@/components/Loader.vue";
import Error from '@/components/Error.vue'
import { InputCheck, SelectOption } from '@/components/fields'

import type { User } from '@/types/typings'

const Row = defineAsyncComponent({
  loader: () => import('@/student/components/tables/completed/Row.vue'),
  LoadingComponent: 'Loading...',
  suspensible: false,
  delay: 2000
})
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
}>()
const props = defineProps<{
 currentUser: User
}>()
// DATA
const loading = ref<boolean>(false)
const error   = ref<object | null>(null)
const columns = reactive<object>([
  {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
  {id: 2, key: 'event', value: 'record_status', name: 'Your Status', sortable: true, width: '18%'},
  {id: 3, key: 'course', value: 'course', name: 'Course', sortable: true, width: '17.5%'},
  {id: 4, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '17.5%'},
  {id: 5, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
])
const entries = ref<object[]>([])
const sort = reactive({
  key: 'event',
  column: 'id',
  direction: 'asc'
})
const filter = reactive({
  timeframe: 'all',
  limit: [],
  search: '', // TODO:: would be part od the admin/instructor list
})
// COMPUTED
const filteredEntries = computed(() => {
  let newEntries = entries.value || []
  if(!isEmpty(newEntries) && Array.isArray(newEntries)) { // newEntries instanceof Array
    newEntries = newEntries?.sort(compareEntries(sort.key, sort.column, sort.direction))
    newEntries = Object.entries(filter) ? filterEntries(newEntries, filter) : newEntries
    newEntries = paginateEntries(newEntries, paginate.offset, paginate.end)
    return newEntries
  }
  return newEntries
})
const options   = computed(() => unique(entries.value, 'course', 'term').sort((a,b) => a.localeCompare(b)))
const paginate  = reactive({
  page: 1,
  limit: 5,
  total: computed(() => entries.value?.length),
  pages: computed<number>(() => Math.ceil(entries.value?.length / Number(paginate.limit)) || 1),
  offset: computed<number>(() => (Number(paginate.page) * Number(paginate.limit) - Number(paginate.limit))), // start
  end: computed<number>(() => Number(paginate.offset) + Number(paginate.limit))
})
// METHODS
function emitSortBy(event) {
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
onMounted(async () => {
  try {
    loading.value = true
    const eventsResponse: Promise<IHttpResponse | any> = await useFetch('/home?work=completed', {method: 'GET', timeout: 300})
    entries.value = eventsResponse.data
  } catch (err: Error | any) {
    error.value = {text: err.message, type: 'error'};
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="completed-work mx-4 my-8">
    <Error v-if="error" class="completed error" :error="error" />
    <div class="flex flex-col my-4 space-y-6">
      <div class="timeframe flex flex-col items-center md:flex-row md:space-x-12">
        <div class="label">Timeframe:</div>
        <SelectField :default="filter.timeframe" :options="options" :name="'timeframe'" @change:select="updateData" />
      </div>
      <div class="limit flex flex-col items-center md:flex-row md:space-x-16">
        <div class="label">Limit To:</div>
        <div class="flex items-center space-x-4">
          <InputCheck class="text-sm" name="limit" value="can_edit" label="Work I can still edit" @change:input="updateData" />
          <InputCheck class="text-sm" name="limit" value="can_view" label="Peer reviews of me I can see" @change:input="updateData" />
        </div>
      </div>
    </div>
    <Table :error="error" :columns="columns" @update:sort="emitSortBy">
      <tr v-if="loading">
        <td :colspan="columns.length">
          <Loader height="200px" />
        </td>
      </tr>
      <tr v-else-if="!loading && isEmpty(filteredEntries)">
        <td :colspan="columns.length">
          <div class="flex justify-center items-center p-8">No Content found!</div>
        </td>
      </tr>
      <template v-else-if="!loading && !isEmpty(filteredEntries)">
        <Row v-for="(row, index) of filteredEntries" :key="`${index}_${row?.event?.id}`" :data-index="index" :row="row" />
      </template>
    </Table>
    <Pagination :paginate="paginate" />
  </div>
</template>
