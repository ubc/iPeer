<script lang="ts" setup>
import { toRef, computed, onMounted, defineAsyncComponent} from 'vue';
import { useRoute, useRouter } from 'vue-router'

import IconSpinner from '@/components/icons/IconSpinner.vue'
import TakeNote from '@/student/components/TakeNote.vue'

import type {IEvaluation, IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
}>()
const route = useRoute()
const router = useRouter()
// DATA
const members     = toRef(props, 'members')
const currentUser = toRef(props, 'currentUser')
const evaluation  = toRef(props, 'evaluation')
// COMPUTED
const template = computed(() => {
  switch (evaluation.value?.template) {
    case 'SimpleEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/SimpleEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 flex justify-center items-center bg-gray-50">L O A D I N G...</div>`
      })
    case 'RubricEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/RubricEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 flex justify-center items-center bg-gray-50">L O A D I N G...</div>`
      })
    case 'MixedEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/MixedEvaluation.vue'),
        loadingComponent: `<div class="w-full h-128 flex justify-center items-center bg-gray-50">L O A D I N G...</div>`
      })
    default:
      break
  }
})
// METHODS
// WATCH
// LIFECYCLE
onMounted(() => {
  if(evaluation.value?.status === null || evaluation.value?.status == '0') {
    router.push({ name: 'evaluation.make' })
  } else {
    router.push({ name: 'evaluation.edit' })
  }
})
</script>

<template>
  <div class="evaluation-edit-page">
    <component
        :is="template"
        :members="members"
        :evaluation="evaluation"
        :currentUser="currentUser"
        @fetch:evaluation="$emit('fetch:evaluation')"
    >
      <template v-slot:header></template>
      <template v-slot:main></template>
      <template v-slot:footer><TakeNote /></template>
      <template v-slot:cta="{ onSave, isSubmitting }">
        <div class="cta">
          <router-link :to="{ name: 'student.events' }" class="button default with-icon" >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            <span>Back</span>
          </router-link>
          <button type="submit" class="button submit flex items-center">
            <IconSpinner v-if="isSubmitting" />
            <span>{{ 'Save Changes' }}</span>
          </button>
        </div>
      </template>
    </component>
  </div>
</template>
