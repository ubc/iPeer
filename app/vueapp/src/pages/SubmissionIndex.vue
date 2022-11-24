<script lang="ts" setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from '@/stores/main'
import { useAuthStore } from '@/stores/auth'
import { useReviewsStore } from '@/stores/reviews'
// import { useEvaluationStore } from '@/stores/evaluation'

import Loader from '@/components/Loader.vue'
import PageHeading from '@/components/PageHeading.vue'
import ViewHeading from '@/student/components/ViewHeading.vue'
import { IconTwoUsers } from '@/components/icons'
import { NotAvailable } from '@/components/messages'

import type {IUser, IEvaluation, IReview} from '@/types/typings'

// TODO:: Bring in Pinia Store for currentUser, evaluation and members
interface Props {}
// REFERENCES
const emit                = defineEmits<{}>()
const props               = defineProps<Props>()
const route               = useRoute()
const store               = useStore()
const authStore           = useAuthStore()
const reviewsStore        = useReviewsStore()
// DATA
const event_id            = ref<number|any>(route.params.event_id)
const group_id            = ref<number|any>(route.params.group_id)

const state = reactive({
  loading: computed<boolean>(() => reviewsStore.loading),
  error: computed<boolean|any>(() => reviewsStore.error),
  hasError: computed<boolean>(() => reviewsStore.hasError),
  reviews: computed<IReview|null>(() => reviewsStore.reviews),
  members: computed<IUser[]|null>(() => reviewsStore.getGroupMembers)
})

// COMPUTED
const currentUser         = computed<IUser|null>(() => authStore.getCurrentUser)
const isDisabled          = computed<boolean>(() => route.name === 'submission.view')

// METHODS
// WATCH
// LIFECYCLE
onMounted(async () => await reviewsStore.fetchReviews(event_id.value, group_id.value))
</script>

<template>
  <template v-if="state.loading">
    <Loader />
  </template>
  <template v-else-if="!state.loading && state.hasError && !state.reviews">
    <NotAvailable :data="state.error" />
  </template>
  <template v-else-if="!state.loading && !state.hasError && state.reviews">
    <PageHeading :title="state.reviews?.event?.title" :settings="{title: `View ${state.reviews?.event?.title}`}">
      <ViewHeading
          :due-date="state.reviews?.event?.due_date"
          :submitted-date="state.reviews?.simple?.date_submitted"
          :is-result-released="state.reviews?.event?.is_result_released"
          :penalties="state.reviews?.penalty"
          :group="state.reviews?.group"
          :course="state.reviews?.course"
          :icon="{src: IconTwoUsers, size: '6rem'}"
      />
    </PageHeading>

    <router-view
        class="tab-pane fade show active"
        :current-user="currentUser"
        :members="state.members"
        :reviews="state.reviews"
        :is-disabled="isDisabled"
    ></router-view>
  </template>
  <template v-else-if="!state.loading && state.hasError && !state.reviews">
    <NotAvailable status="custom" :data="state.error" />
  </template>
</template>
