<script lang="ts" setup>
import {ref, computed, onMounted, defineAsyncComponent, reactive} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { findIndex } from 'lodash'
import { useStore } from '@/stores/main'
import { useAuthStore } from '@/stores/auth'
import { useEvaluationStore } from '@/stores/evaluation'
import api from '@/services/api'
import Loader from '@/components/Loader.vue'
import { NotAvailable } from '@/components/messages'
import PageHeading from '@/components/PageHeading.vue'
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

import type { IEvaluation, IUser } from '@/types/typings'
interface Props {
  // currentUser: IUser
  // event_id?: string
  // group_id?: string
  // pageTitle?: string
}
// REFERENCES
const emit              = defineEmits<{}>()
const props             = defineProps<Props>()
const route             = useRoute()
const router            = useRouter()
const store             = useStore()
const authStore         = useAuthStore()
const evaluationStore   = useEvaluationStore()
// DATA
const event_id          = ref<string|any>(route.params?.event_id)
const group_id          = ref<string|any>(route.params?.group_id)
const status            = ref<string>('')
const state             = reactive({
  loading: computed(() => evaluationStore.loading),
  error: computed(() => evaluationStore.error),
  hasError: computed(() => evaluationStore.hasError),
  evaluation: computed(() => evaluationStore.evaluation),
  members: computed(() => evaluationStore.getGroupMembers),
})
// const message           = ref<object|null>(null)
// let evaluation          = reactive<IEvaluation | any>({})
// const members           = ref<IUser[]>([])
// COMPUTED
const currentUser = computed(() => authStore.getCurrentUser)
const notification = computed(() => store.getNotification)
// const error       = computed(() => store.isError)
// const loading     = computed(() => store.isLoading)
// const members     = computed(() => evaluationStore.getMembers)
// const evaluation  = computed(() => evaluationStore.getEvaluation)

const evaluationPage = computed(() => {
  // Check if evaluation started
  if(state.evaluation?.is_released) {
    switch (route.name) {
      case 'evaluation.make':
        return defineAsyncComponent(() => import('@/student/views/EvaluationMakePage.vue'))
      case 'evaluation.edit':
        return defineAsyncComponent(() => import('@/student/views/EvaluationEditPage.vue'))
      default:
        break
    }
  } else {
    router.push({ name: 'student.events' })
  }
})
// METHODS
// WATCH
// LIFECYCLE
onMounted(async () => await evaluationStore.fetchEvaluation(event_id.value, group_id.value))
</script>

<template>
  <div class="evaluation-index">
    <template v-if="state.loading">
      <Loader />
    </template>

    <template v-else-if="!state.loading && state.hasError">
      <NotAvailable status="online" :data="{ title: 'evaluation not available', message: 'evaluation message goes here.' }" />
    </template>

    <template v-else-if="!state.loading && !state.hasError && state.evaluation">
      <router-view name="flash" v-slot="{ Component }" >
        <component :is="Component" :notification="notification" />
      </router-view>

      <PageHeading :settings="{ title: state.evaluation?.title }">
        <ViewHeading
            :due-date="state.evaluation?.due_date"
            :penalty="state.evaluation?.penalty"
            :group="state.evaluation?.group"
            :course="state.evaluation?.course"
            :icon="{src: IconTwoUsers, size: '6rem'}"
        />
      </PageHeading>

      <SectionTitle title="About This Peer Review">
        <InstructorSays v-if="state.evaluation?.description" :description="state.evaluation?.description" />
        <ConsiderPoints />
      </SectionTitle>

      <SectionTitle title="Your Response" />
      <SectionSubtitle subtitle="Evaluate your group" :icon="{src: IconWritingHand, size: '3.75rem'}" />

      <router-view
          :current-user="currentUser"
          :members="state.members"
          :evaluation="state.evaluation"
      ></router-view>
    </template>

  </div>
</template>
