<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router'
import useFetch from '@/composables/useFetch'
import Loader from '@/components/Loader.vue'
import PageTitle from '@/components/PageTitle.vue'
import ViewHeading from '@/student/components/ViewHeading.vue'
import { IconTwoUsers } from '@/components/icons'

import type { IUser, IEvaluation } from '@/types/typings'
// REFERENCES
const emit              = defineEmits<{}>()
const props             = defineProps<{}>()
const route             = useRoute()
// DATA
const event_id          = ref(route.params.event_id)
const group_id          = ref(route.params.group_id)
const status            = ref<string>('')
const message           = ref<object|null>(null)
const reviews           = reactive({'id': 'work in progress'})
let evaluation          = reactive<IEvaluation|any>({})
const members           = ref<IUser[]>([])
// COMPUTED
// METHODS
async function fetchEvaluation() {
  try {
    status.value = 'PENDING'
    const response: Promise<IEvaluation | unknown> = await useFetch(
        `/evaluations/makeEvaluation/${event_id.value}/${group_id.value}`,
        {method: 'GET', timeout: 0})
    await Object.assign(evaluation, response?.data)
  } catch (err) {
    message.value = {text: err, type: 'error'}
  } finally {
    status.value = 'READY'
  }
}
// WATCH
// LIFECYCLE
onMounted(async () => {
  await fetchEvaluation()
  console.log({'evaluation': evaluation})
})
</script>

<template>
  <template v-if="status === 'PENDING'">
    <Loader />
  </template>

  <template v-else>
    <PageTitle :title="evaluation?.title">
      <ViewHeading
          :due-date="evaluation?.due_date"
          :penalties="evaluation?.penalty"
          :group-name="evaluation?.group?.name"
          :course-title="evaluation?.course?.title"
          :icon="{src: IconTwoUsers, size: '6rem'}"
      />
    </PageTitle>
    <Suspense>
      <router-view
          class="tab-pane fade show active"
          id="response"
          role="tabpanel"
          aria-labelledby="response-tab"
          :currentUser="currentUser"
          :members="members"
          :evaluation="evaluation"
          :reviews="reviews"
          @fetch:evaluation="fetchEvaluation">
      </router-view>
    </Suspense>
  </template>
</template>
