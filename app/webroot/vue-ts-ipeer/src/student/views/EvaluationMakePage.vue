<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue'
import { useRoute, useRouter } from 'vue-router'

import TakeNote from '@/student/components/TakeNote.vue'
import IconSpinner from '@/components/icons/IconSpinner.vue'

import type { Evaluation, User, EvaluationReviewResponse } from '@/types/typings'

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: object): void
  (e: 'fetch:evaluation'): void
}>()
const props = defineProps<{
  currentUser: User
  evaluation: Evaluation
}>()
const route = useRoute()
const router = useRouter()
// DATA
// COMPUTED
const template = computed(() => {
  if(props.evaluation?.template) {
    return defineAsyncComponent({
      loader: () => import(`@/student/views/templates/${props.evaluation?.template}.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`
    })
  }
})
// METHODS
// WATCH
// LIFECYCLE
onMounted(() => {
  if(props.evaluation?.status === '1') {
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
      :evaluation="props.evaluation"
      :currentUser="props.currentUser"
      @fetch:evaluation="$emit('fetch:evaluation')"
    >
      <template v-slot:header></template>
      <template v-slot:main></template>
      <template v-slot:footer><TakeNote /></template>
      <template v-slot:cta="{ onSave, isSubmitting }">
        <div class="cta">
          <button type="button" class="button default flex items-center" @click="onSave">
            <IconSpinner v-if="isSubmitting" /> <span>Save Draft</span>
          </button>
          <button type="submit" class="button submit flex items-center">
            <!--<IconSpinner v-if="meta.touched && isSubmitting" />-->
            <span>Submit Peer Review</span>
          </button>
        </div>
      </template>
    </component>
  </div>
</template>
