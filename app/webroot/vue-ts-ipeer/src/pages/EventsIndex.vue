<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent, onErrorCaptured, toRef} from 'vue'
import swal from 'sweetalert'
import useFetch from '@/composables/useFetch'
import CurrentWork from '@/student/components/tables/CurrentWork.vue'
import CompletedWork from '@/student/components/tables/CompletedWork.vue'
import Loader from '@/components/Loader.vue'
import Error from '@/components/Error.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import {IconNotepad, IconCheckedBox} from '@/components/icons'
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
// const CurrentWork = defineAsyncComponent({
//   loader: () => import('@/student/components/tables/CurrentWork.vue'),
//   LoadingComponent: Loader,
//   delay: 2000,
//   errorComponent: Error,
//   timeout: 3000,
//   suspensible: false
// })
// const CompletedWork = defineAsyncComponent({
//   loader: () => import('@/student/components/tables/CompletedWork.vue'),
//   LoadingComponent: Loader,
//   delay: 2000,
//   errorComponent: Error,
//   timeout: 3000,
//   // suspensible: false
// })
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  currentUser: User
}>()
// DATA
const error = ref<object | null>(null)
// const error = ref<object | null>(null)
// const columns = reactive<object>({
//   current: [
//     {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
//     {id: 2, key: 'course', value: 'course', name: 'Course', sortable: true, width: '23%'},
//     {id: 3, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '30%'},
//     {id: 4, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
//   ],
//   completed: [
//     {id: 1, key: 'event', value: 'title', name: 'Work', sortable: true, width: '30%'},
//     {id: 2, key: 'event', value: 'record_status', name: 'Your Status', sortable: true, width: '18%'},
//     {id: 3, key: 'course', value: 'course', name: 'Course', sortable: true, width: '17.5%'},
//     {id: 4, key: 'event', value: 'due_date', name: 'Due', sortable: true, width: '17.5%'},
//     {id: 5, key: 'action', value: '', name: 'Action', sortable: false, width: '17%'},
//   ]
// })
// const entries = reactive<object>({})

// COMPUTED
// METHODS
function onErrorCaptured(event) {
  error.value = event
}
// WATCH
// LIFECYCLE

// onMounted(async () => {
//   try {
//     const eventsResponse: Promise<IHttpResponse | any> = await useFetch('/home', {method: 'GET', timeout: 300})
//     const events: IData = eventsResponse.data
//     Object.assign(entries, events);
//   } catch (err: Error | any) {
//     error.value = {text: err.message, type: 'error'};
//   }
// })
</script>

<template>
  <div class="events">
    <div v-if="error" class="events error">{{ JSON.stringify(error, null, 2) }}</div>

    <section>
      <SectionTitle title="Current Work To Do"></SectionTitle>
      <SectionSubtitle subtitle="Do the work assigned to you" :icon="{src: IconCheckedBox, size: '3.5rem'}">
        <p class="mx-4">Instructors will set specific timeframes for reviewing your group. When work is available, it will appear here until you complete it or the final due date passes.</p>
      </SectionSubtitle>

      <Suspense>
        <template #default>
          <CurrentWork :current-user="currentUser" />
        </template>
        <template #fallback>
          <Loader />
        </template>
      </Suspense>
    </section>

    <section>
      <SectionTitle title="Closed or Completed Work"></SectionTitle>
      <SectionSubtitle subtitle="See past work and reviews of your teamwork" :icon="{src: IconNotepad, size: '3.25rem'}">
        <p class="mx-4">Below you can find previously assigned work that you have completed or that has closed. After a peer review closes, your instructor may or may not let you see how your peers evaluated you. If any reviews of you are available, they will be linked below.</p>
      </SectionSubtitle>

      <Suspense>
        <template #default>
          <CompletedWork :current-user="currentUser" />
        </template>
        <template #fallback>
          <Loader />
        </template>
      </Suspense>
    </section>
  </div>
</template>
