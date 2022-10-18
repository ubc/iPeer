<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue'
import { useRoute, useRouter } from 'vue-router'

import type {Evaluation, User} from '@/types/typings'

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  currentUser: User
  evaluation: Evaluation
}>()
const router = useRouter()
// DATA

// COMPUTED
const template = computed(() => {
  if(props.evaluation?.template) {
    return defineAsyncComponent({
      loader: () => import(`@/student/views/templates/${props.evaluation?.template}.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`,
      errorComponent: `<div class="w-full h-128 bg-red-100">E R R O R...</div>`,
      delay: 5000,
      onError: error => "error"
    })
  }
})

/**
const template = computed(() => {
  switch (props.evaluation?.template) {
    case 'SimpleEvaluation':
      return defineAsyncComponent(() => import('@/student/views/templates/SimpleEvaluation.vue'))
    case 'RubricEvaluation':
      return defineAsyncComponent(() => import('@/student/views/templates/RubricEvaluation.vue'))
    case 'MixedEvaluation':
      return defineAsyncComponent(() => import('@/student/views/templates/MixedEvaluation.vue'))
    default:
      return
  }
})
*/
// METHODS

// WATCH

// LIFECYCLE
onMounted(() => {
  if(props.evaluation?.status == '0') {
    router.push({ name: 'evaluation.edit' })
  }
})
</script>

<template>
  <div class="evaluation-make-page bg-green-50">
    <component
      :is="template"
      action="Submit"
      _method="POST"
      :currentUser="currentUser"
      :evaluation="evaluation"
    >
      <template v-slot:cta="{ onSave }">
        <div class="cta">
          <button type="button" class="button default" @click="onSave">Save Draft</button>
          <button type="submit" class="button submit">Submit Peer Review</button>
        </div>
      </template>
    </component>

  </div>
</template>
