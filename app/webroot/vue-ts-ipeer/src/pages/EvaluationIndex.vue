<script lang="ts" setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router'
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
//
import type { Evaluation } from '@/types/typings'
interface Props {
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
const evaluation        = reactive<Evaluation>({})
// COMPUTED
const submission        = computed(() => {
  if(evaluation?.submission) {
    return evaluation?.submission
  }
  return { points: [], comments: [] }
})
// METHODS
// WATCH
// LIFECYCLE
onMounted(async () => {
  try {
    status.value = 'PENDING'
    const response = await useFetch(
        `evaluations/makeEvaluation/${event_id.value}/${group_id.value}`,
        {method: 'GET', timeout: 300})
    Object.assign(evaluation, response.data)
  } catch (err) {
    message.value = {text: err.messge, type: 'error'}
  } finally {
    status.value = 'READY'
  }
})
</script>

<template>
  <div class="">
    <template v-if="status === 'PENDING'">
      <Loader />
    </template>

    <template v-else>
      <PageTitle :title="evaluation?.event?.title">
        <ViewHeading
            :due-date="evaluation?.event?.due_date"
            :penalties="evaluation?.penalties"
            :group-name="evaluation?.group?.group_name"
            :course-title="evaluation?.course?.title"
            :icon="{src: IconTwoUsers, size: '6rem'}"
        />
      </PageTitle>

      <SectionTitle title="About This Peer Review">
        <InstructorSays v-if="evaluation?.event?.description" :description="evaluation?.event?.description" />
        <ConsiderPoints />
      </SectionTitle>

      <SectionTitle title="Your Response" />
      <SectionSubtitle subtitle="Evaluate your group" :icon="{src: IconWritingHand, size: '3.75rem'}" />

      <pre class="debug">{{ evaluation?.template }}</pre>
    </template>

  </div>
</template>
