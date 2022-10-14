<script lang="ts" setup>
import { reactive } from 'vue';
// REFERENCES
const emit = defineEmits<{
  (e: 'update:sort', option: object): void
}>()
const props = defineProps<{
  columns: object[]
}>()
// DATA
const sort = reactive({
  key: 'event',
  column: 'id',
  direction: 'asc'
})
// COMPUTED
// METHODS
function handleSortBy(key: string, column: string, direction: string) {
  sort.key = key
  sort.column = column
  sort.direction = direction
  emit('update:sort', sort)
}
// WATCH
// LIFECYCLE
</script>

<template>
  <table class="standardtable leftalignedtable">
    <thead>
    <tr>
      <th v-for="(column, index) of columns" :key="column.id" :data-index="index" :style="{width: column.width}">
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
    <tbody><slot /></tbody>
  </table>
</template>
