<script lang="ts" setup>
import {ref, reactive, computed, onMounted, defineAsyncComponent} from 'vue';
import { useRoute } from 'vue-router'
import useFetch from '@/composables/useFetch'
//
import Debugger from '@/components/Debugger.vue'
//
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

import EvaluationForm from "@/student/views/EvaluationForm.vue";

const EvaluationMakePage = defineAsyncComponent(() => import('@/student/views/EvaluationMakePage.vue'))
const EvaluationEditPage = defineAsyncComponent(() => import('@/student/views/EvaluationEditPage.vue'))

import type { Evaluation, User } from '@/types/typings'
interface Props {
  currentUser: User
  event_id: string
  group_id: string
  pageTitle: string
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
const form              = reactive<unknown>({})
const evaluation        = reactive<Evaluation | null>({})
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
// WATCH
// LIFECYCLE
onMounted(async () => {
  try {
    status.value = 'PENDING'
    const response: Promise<Evaluation | unknown> = await useFetch(
        `evaluations/makeEvaluation/${event_id.value}/${group_id.value}`,
        {method: 'GET', timeout: 300})
    console.log({response})
    Object.assign(evaluation, response?.data)
  } catch (err) {
    message.value = {text: err?.messge, type: 'error'}
  } finally {
    status.value = 'READY'
  }
})
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
            :penalty="evaluation?.penalty_final"
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

      <Debugger :title="`EvaluationIndex::Route`" :data="useRoute()" />
      <Debugger :title="`EvaluationIndex::${evaluation?.template}`" :state="evaluation" />

      <router-view :currentUser="currentUser" :evaluation="evaluation"></router-view>
    </template>

  </div>
</template>
