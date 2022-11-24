<script lang="ts" setup>
import { toRef, computed, onMounted, defineAsyncComponent} from 'vue'
import { useRouter } from 'vue-router'
import IconSpinner from '@/components/icons/IconSpinner.vue'
import TakeNote from '@/student/components/TakeNote.vue'

import type { IUser, IEvaluation } from '@/types/typings'
// REFERENCES
const emit  = defineEmits<{}>()
const props = defineProps<{
  members: IUser[]
  currentUser: IUser
  evaluation: IEvaluation
  settings: object
}>()
const router = useRouter()
// DATA
const members     = toRef(props, 'members')
const currentUser = toRef(props, 'currentUser')
const evaluation  = toRef(props, 'evaluation')
// COMPUTED
const template = computed(() => {
  switch (evaluation.value?.event_template_type_id) {
    case '1':
      return defineAsyncComponent(() => import('@/student/views/templates/SimpleEvaluation.vue'))
    case '2':
      return defineAsyncComponent(() => import('@/student/views/templates/RubricEvaluation.vue'))
    case '4':
      return defineAsyncComponent(() => import('@/student/views/templates/MixedEvaluation.vue'))
    default:
      break
  }
})
// METHODS
// WATCH
// LIFECYCLE
onMounted(() => {
  console.log({'evaluation': evaluation.value})
  /**
  if(evaluation.value?.is_released) {
    if(evaluation.value?.is_result_released) {
      router.push({ name: 'submission.view' })
    } else {
      if(evaluation.value?.status === '1') {
        router.push({ name: 'evaluation.edit' })
      } else {
        router.push({ name: 'evaluation.make' })
      }
    }
  } else {
    router.push({ name: 'not.found' })
  }*/

  /**
  if(evaluation.value?.is_released && evaluation.value?.is_result_released) {
    router.push({ name: 'submission.view' })
  } else if(evaluation.value?.is_released) {
    if(evaluation.value?.status === '1') {
      router.push({ name: 'evaluation.edit' })
    } else {
      router.push({ name: 'evaluation.make' })
    }
  } else {
    router.push({ name: 'not.found' })
  }*/
})
</script>

<template>
  <div class="evaluation-make-page">
    <component
      :is="template"
      :members="members"
      :evaluation="evaluation"
      :currentUser="currentUser"
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
