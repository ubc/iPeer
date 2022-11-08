<script lang="ts" setup>
import { ref, computed, defineAsyncComponent} from 'vue'
import { useRoute } from 'vue-router'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ErrorComponent from '@/components/ErrorComponent.vue'
import SubmissionResponse from "@/student/views/SubmissionResponse.vue";
import SubmissionReviews from "@/student/views/SubmissionReviews.vue";

import type {IUser, IEvaluation, ISimpleReview, IRubricReview, IMixedReview} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  currentUser: IUser
  members: IUser[]
  evaluation: IEvaluation
  reviews: ISimpleReview|IRubricReview|IMixedReview
}>()
const route = useRoute()
// DATA
const event_id    = ref(route.params?.event_id)
const group_id    = ref(route.params?.group_id)
const currentTab  = ref('Response')
// COMPUTED
const template = computed(() => {
  switch (currentTab.value) {
    case 'Response':
      return defineAsyncComponent({
        loader: () => import('@/student/views/SubmissionResponse.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'Reviews':
      return defineAsyncComponent({
        loader: () => import('@/student/views/SubmissionReviews.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    default:
      break
  }
})
const isDisabled = computed(() => {
  if(route.name === 'submission.view') {
    return true
  }
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <section class="submission-page mb-16">
    <ul class="nav nav-tabs" id="submissions" role="tabs">
      <li class="nav-item" role="presentation">
        <div
            :class="`nav-link ${currentTab==='Response'?'active':''}`"
            :id="`${currentTab.toLowerCase()}-tab`"
            role="tab"
            :aria-selected="`${currentTab==='Response'}`"
            @click="currentTab='Response'">See My Response</div>
      </li>
      <li class="nav-item" role="presentation">
        <div
            :class="`nav-link ${currentTab==='Reviews'?'active':''}`"
            :id="`${currentTab.toLowerCase()}-tab`"
            role="tab"
            :aria-selected="`${currentTab==='Reviews'}`"
            @click="currentTab='Reviews'">See Reviews of Me</div>
      </li>
    </ul>
    <div class="tab-content" id="tabs">
      <component
          :is="template"
          :members="props.members"
          :evaluation="props.evaluation"
          :current-user="props.currentUser"
          :disabled="isDisabled"
          @fetch:evaluation="$emit('fetch:evaluation')"
      ></component>
    </div>

    <div class="cta">
      <router-link :to="{ name: 'student.events' }" class="button default with-icon" >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        <span>Back</span>
      </router-link>

      <router-link
          v-if="currentTab==='Response'"
          :to="{ name: 'evaluation.edit', params: { event_id: event_id, group_id: group_id } }"
          class="button submit flex items-center"
      ><span>{{ 'Edit My Response' }}</span>
      </router-link>
    </div>

  </section>
</template>
