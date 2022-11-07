<script lang="ts" setup>
import { toRef, computed, onMounted, defineAsyncComponent} from 'vue'
import { useRoute, useRouter } from 'vue-router'

import IconSpinner from '@/components/icons/IconSpinner.vue'
import TakeNote from '@/student/components/TakeNote.vue'

import type { IUser, IEvaluation } from '@/types/typings'
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
/** Uncaught (in promise) TypeError: error loading dynamically imported module
 const template = computed(() => {
  if(evaluation.value?.template) {
    return defineAsyncComponent({
      loader: () => import(`@/student/views/templates/${evaluation.value?.template}.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
    })
  }
})
*/
// METHODS
// WATCH
// LIFECYCLE
onMounted(() => {
  if(evaluation.value?.status === '1') {
    router.push({ name: 'evaluation.edit' })
  } else {
    router.push({ name: 'evaluation.make' })
  }
})
</script>

<template>
  <div class="evaluation-make-page">
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
          <button type="button" class="button default flex items-center" @click="onSave">
            <span>Save Draft</span>
          </button>
          <button type="submit" class="button submit flex items-center">
            <IconSpinner v-if="isSubmitting" />
            <span>Submit Peer Review</span>
          </button>
        </div>
      </template>
    </component>
  </div>
</template>
