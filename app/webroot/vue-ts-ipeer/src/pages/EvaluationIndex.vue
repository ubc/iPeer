<script lang="ts" setup>
import {ref, reactive, computed, onMounted, defineAsyncComponent} from 'vue';
import { useRoute } from 'vue-router'
import { findIndex } from 'lodash'
import useFetch from '@/composables/useFetch'
import Loader from '@/components/Loader.vue'
import PageTitle from '@/components/PageTitle.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import ViewHeading from '@/student/components/ViewHeading.vue'
import InstructorSays from '@/student/components/InstructorSays.vue'
import ConsiderPoints from '@/student/components/ConsiderPoints.vue'
import {
  IconTwoUsers,
  IconNumberOne,
  IconNumberTwo,
  IconNumberThree,
  IconWritingHand,
  IconThinkingFace
} from '@/components/icons'
const EvaluationMakePage = defineAsyncComponent(() => import('@/student/views/EvaluationMakePage.vue'))
const EvaluationEditPage = defineAsyncComponent(() => import('@/student/views/EvaluationEditPage.vue'))
import type { Evaluation, User } from '@/types/typings'
interface Props {
  currentUser: User
  event_id?: string
  group_id?: string
  pageTitle?: string
}
// REFERENCES
const emit              = defineEmits<{}>()
const props             = defineProps<Props>()
const route             = useRoute()
// DATA
const event_id          = ref(route.params.event_id)
const group_id          = ref(route.params.group_id)
const status            = ref<string>('')
const message           = ref<object | null>(null)
let evaluation          = reactive<Evaluation | any>({})
const members           = ref<User[]>([])
// COMPUTED
const evaluationPage = computed(() => {
  // Check if evaluation is started
  switch (useRoute().name) {
    case 'evaluation.make':
      return defineAsyncComponent(() => import('@/student/views/EvaluationMakePage.vue'))
    case 'evaluation.edit':
      return defineAsyncComponent(() => import('@/student/views/EvaluationEditPage.vue'))
    default:
      return
  }
})
// METHODS
async function fetchEvaluation() {
  try {
    status.value = 'PENDING'
    const response: Promise<Evaluation | unknown> = await useFetch(
        `/evaluations/makeEvaluation/${event_id.value}/${group_id.value}`,
        {method: 'GET', timeout: 0})
    await Object.assign(evaluation, response?.data)
    // members.value = [...updateMembersCollection(response?.data?.members, props.currentUser)]
    members.value = updateMembersCollection(response?.data?.members, props.currentUser)
  } catch (err) {
    message.value = {text: err?.messge, type: 'error'}
  } finally {
    status.value = 'READY'
  }
}
function updateMembersCollection(users, user) {
  let tmp = [...users]
  const index = findIndex(tmp, { id: user.id})
  if (index === -1) {
    return users
  } else {
    const spliced = tmp.splice(index, 1)
    return [...tmp, {...spliced[0], first_name: 'Yourself', last_name: ''}]
  }
}
// WATCH
// LIFECYCLE
onMounted(async () => await fetchEvaluation())
</script>

<template>
  <div class="evaluation-index">
    <template v-if="status === 'PENDING'">
      <Loader />
    </template>

    <template v-else>
      <PageTitle :title="evaluation?.title">
        <ViewHeading
            :due-date="evaluation?.due_date"
            :penalty="evaluation?.penalty"
            :group-name="evaluation?.group?.name"
            :course-title="evaluation?.course?.title"
            :icon="{src: IconTwoUsers, size: '6rem'}"
        />
      </PageTitle>

      <SectionTitle title="About This Peer Review">
        <InstructorSays v-if="evaluation?.description" :description="evaluation?.description" />
        <ConsiderPoints />
      </SectionTitle>

      <SectionTitle title="Your Response" />
      <SectionSubtitle subtitle="Evaluate your group" :icon="{src: IconWritingHand, size: '3.75rem'}" />

      <Suspense>
        <router-view
            :currentUser="currentUser"
            :members="members"
            :evaluation="evaluation"
            @fetch:evaluation="fetchEvaluation">
        </router-view>
      </Suspense>
    </template>

  </div>
</template>
