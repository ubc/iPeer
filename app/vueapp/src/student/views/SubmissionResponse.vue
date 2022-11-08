<script lang="ts" setup>
import { computed, defineAsyncComponent} from 'vue'
import LoadingComponent from '@/components/LoadingComponent.vue'
import ErrorComponent from '@/components/ErrorComponent.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconWritingHand } from '@/components/icons'
import type {IEvaluation, IUser} from '@/types/typings'
interface Props {
  evaluation: IEvaluation
  currentUser: IUser
  disabled?: boolean
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()

// DATA
// COMPUTED
const template = computed(() => {
  switch (props.evaluation?.template) {
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
/**
const template = computed(() => {
  if(props.evaluation?.template) {
    return defineAsyncComponent({
      loader: () => import(`@/student/views/templates/${props.evaluation?.template}.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
    })
  }
})*/
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <SectionTitle title="Your Response" />
  <SectionSubtitle subtitle="Evaluate your group" :icon="{src: IconWritingHand, size: '3.75rem'}" />
  <component
      :is="template"
      :members="props.members"
      :evaluation="props.evaluation"
      :current-user="props.currentUser"
      :disabled="props.disabled"
      @fetch:evaluation="$emit('fetch:evaluation')"
  ></component>
</template>
