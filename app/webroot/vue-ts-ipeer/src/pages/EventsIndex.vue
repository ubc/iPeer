<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';
import axios from 'axios'
import swal from 'sweetalert'
import Loader from '@/components/Loader.vue'

import CurrentWorkComponent from "";
import CompletedWorkComponent from "";
import Debugger from "@/components/Debugger.vue";

import {
  IconNotepad,
  IconCheckedBox,
  IconCollaboratorCircle
} from "@/components/icons";

const CurrentWork = defineAsyncComponent({
  loader: () => import('@/student/components/tables/CurrentWork.vue'),
  CurrentWork: Loader
})
const CompletedWork = defineAsyncComponent({
  loader: () => import('@/student/components/tables/CompletedWork.vue'),
  CompletedWork: Loader
})


// REFERENCES
const props = defineProps<{
  current_user: object
}>()
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()

// DATA
const entries = reactive({})
const columns = reactive({
  current: [
    {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
    {id: 2, key: 'course', value: 'course', name: 'Course', sortable: true, width: '23%'},
    {id: 3, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '30%'},
    {id: 4, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
  ],
  completed: [
    {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
    {id: 2, key: 'event', value: 'record_status', name: 'Your Status', sortable: true, width: '18%'},
    {id: 3, key: 'course', value: 'course', name: 'Course', sortable: true, width: '17.5%'},
    {id: 4, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '17.5%'},
    {id: 5, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
  ]
})

// COMPUTED

// METHODS
async function getEvents() {
  try {
    let response = await axios({
      method: 'GET',
      url: '/home',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    Object.assign(entries, response.data.data)
  } catch (err: any) {
    swal({title: 'Error', text: err.response.message, icon: 'error'})
  }
}

// WATCH

// LIFECYCLE
onMounted(() => {
  getEvents()
})

</script>

<template>
  <div class="">
    <Debugger title="EventsIndex::Current User" :data="current_user" />

    <CurrentWork :columns="columns?.current" :entries="entries?.current" />


    <CompletedWork :columns="columns?.completed" :entries="entries?.completed" />


  </div>


</template>

<style lang="scss" scoped>

</style>
