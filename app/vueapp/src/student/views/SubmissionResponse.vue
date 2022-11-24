<script lang="ts" setup>
import { computed, defineAsyncComponent} from 'vue'
import Loader from '@/components/Loader.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconWritingHand } from '@/components/icons'
import type { IUser, IReview } from '@/types/typings'

interface Props {
  currentUser: IUser
  members: IUser[]
  reviews: IReview
  isDisabled?: boolean
}
// REFERENCES
const emit        = defineEmits<{}>()
const props       = defineProps<Props>()

// DATA
// COMPUTED
const template    = computed(() => {
  switch (props.reviews?.event?.event_template_type_id) {
    case '1':
      return defineAsyncComponent(() => import('@/student/views/submission/response/SimpleEvaluation.vue'))
    case '2':
      return defineAsyncComponent(() => import('@/student/views/submission/response/RubricEvaluation.vue'))
    case '4':
      return defineAsyncComponent(() => import('@/student/views/submission/response/MixedEvaluation.vue'))
    default:
      break
  }
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <SectionTitle title="Your Response" />
  <SectionSubtitle subtitle="Evaluate your group" :icon="{src: IconWritingHand, size: '3.75rem'}" />
  <Suspense>
    <template #default>
      <Transition name="fade" :duration="{ enter: 500, leave: 800 }">
        <component
            :is="template"
            :current-user="props.currentUser"
            :members="props.members"
            :reviews="props.reviews"
            :is-disabled="props.isDisabled"
        ></component>
      </Transition>
    </template>
    <template #fallback>
      <Loader height="200px" />
    </template>
  </Suspense>
</template>
