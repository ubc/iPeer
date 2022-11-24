<script lang="ts" setup>
import {ref, computed, defineAsyncComponent, onMounted} from 'vue'
import { useRoute } from 'vue-router'
import { localDateOptions } from '@/helpers'

import type { IUser, IReview } from '@/types/typings'

interface Props {
  currentUser: IUser
  members: IUser[]
  reviews: IReview
  isDisabled: boolean
}
// REFERENCES
const emit          = defineEmits<{}>()
const props         = defineProps<Props>()
const route         = useRoute()
// DATA
const event_id      = ref(route.params?.event_id)
const group_id      = ref(route.params?.group_id)
const currentTab    = ref('Response')

// COMPUTED
const template      = computed(() => {
  switch (currentTab.value) {
    case 'Response':
      return defineAsyncComponent(() => import('@/student/views/SubmissionResponse.vue'))
    case 'Reviews':
      return defineAsyncComponent(() => import('@/student/views/SubmissionReviews.vue'))
    default:
      break
  }
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <section class="submission-page">
    <ul class="nav nav-tabs" id="submissions" role="tabs">
      <li class="nav-item" role="presentation">
        <div
            :class="`nav-link ${currentTab==='Response'?'active':''}`"
            :id="`${currentTab.toLowerCase()}-tab`"
            role="tab"
            :aria-selected="`${currentTab==='Response'}`"
            @click="currentTab='Response'">See Your Response</div>
      </li>
      <li class="nav-item" role="presentation">
        <div
            :class="`nav-link ${currentTab==='Reviews'?'active':''}`"
            :id="`${currentTab.toLowerCase()}-tab`"
            role="tab"
            :aria-selected="`${currentTab==='Reviews'}`"
            @click="currentTab='Reviews'">See Reviews of Your Teamwork</div>
      </li>
    </ul>

    <transition name="fade" :duration="{ enter: 500, leave: 800, delay: 100 }">
      <div class="tab-content min-h-[200px]" id="tabs">
        <component
            :is="template"
            :current-user="props.currentUser"
            :members="props.members"
            :reviews="props.reviews"
            :is-disabled="props.isDisabled"
        ></component>
      </div>
    </transition>

    <div class="cta">
      <router-link :to="{ name: 'student.events' }" class="button default with-icon" >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
        <span>Back</span>
      </router-link>
      <router-link
          v-if="currentTab==='Response' && !props.reviews?.event?.is_result_released"
          :to="{ name: 'evaluation.edit', params: { event_id: event_id, group_id: group_id } }"
          class="button submit flex items-center"
      ><span>{{ 'Edit My Response' }}</span>
      </router-link>
    </div>

  </section>
</template>
