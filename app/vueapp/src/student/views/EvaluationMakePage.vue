<script lang="ts" setup>
import { toRef, computed, onMounted, defineAsyncComponent} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ErrorComponent from '@/components/ErrorComponent.vue'
import IconSpinner from '@/components/icons/IconSpinner.vue'
import TakeNote from '@/student/components/TakeNote.vue'
import type { IUser, IEvaluation } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'fetch:evaluation'): void
  (e: 'set:message', option: object): void
}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
  settings: object
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
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'RubricEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/RubricEvaluation.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    case 'MixedEvaluation':
      return defineAsyncComponent({
        loader: () => import('@/student/views/templates/MixedEvaluation.vue'),
        loadingComponent: LoadingComponent /* shows while loading */,
        errorComponent: ErrorComponent /* shows if there's an error */,
      })
    default:
      break
  }
})
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
      @set:message="$emit('set:message', $event)"
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
