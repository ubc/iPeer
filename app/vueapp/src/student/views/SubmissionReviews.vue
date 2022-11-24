<script lang="ts" setup>
import { computed, defineAsyncComponent } from 'vue'
import Loader from '@/components/Loader.vue'
import { NotAvailable } from '@/components/messages'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconNewspaper } from '@/components/icons'
import TakeBreak from '@/student/components/TakeBreak.vue'

import type { IUser, IReview } from '@/types/typings'
interface Props {
  currentUser: IUser
  members: IUser[]
  reviews: IReview
  isDisabled?: boolean
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
// DATA
// COMPUTED
const template      = computed(() => {
  switch (props.reviews?.event?.event_template_type_id) {
    case '1':
      return defineAsyncComponent(() => import('@/student/views/submission/reviews/SimpleEvaluation.vue'))
    case '2':
      return defineAsyncComponent(() => import('@/student/views/submission/reviews/RubricEvaluation.vue'))
    case '4':
      return defineAsyncComponent(() => import('@/student/views/submission/reviews/MixedEvaluation.vue'))
    default:
      break
  }
})
// METHODS
// LIFECYCLE
// onMounted(async () => await reviewsStore.fetchReviews(state.eventId, state.groupId))
// WATCH
</script>

<template>
  <SectionTitle title="Reviews of You" />
  <SectionSubtitle subtitle="Read your group’s evaluation" :icon="{src: IconNewspaper, size: '3.75rem'}" />
  <Suspense>
    <template #default>
      <transition name="fade" :duration="{ enter: 500, leave: 800, delay: 500 }">
        <template v-if="props.reviews?.event?.is_result_released">
          <component
           :is="template"
           :current-user="props.currentUser"
           :members="props.members"
           :reviews="props.reviews"
           :is-disabled="props.isDisabled"
          ></component>
        </template>
        <template v-else>
          <NotAvailable release-status="online" :release-date="props.reviews?.event?.result_release_date_begin" />
        </template>
      </transition>
    </template>
    <template #fallback>
      <Loader height="200px" />
    </template>
  </Suspense>
  <TakeBreak v-if="props.reviews?.event?.is_result_released" />
</template>
