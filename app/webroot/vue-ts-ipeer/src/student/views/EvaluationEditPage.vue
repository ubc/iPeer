<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';
import { useRoute, useRouter } from 'vue-router'

import TakeNote from '@/student/components/TakeNote.vue'
import IconSpinner from '@/components/icons/IconSpinner.vue'

import type {Evaluation, User} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
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
  if(props.evaluation?.status === null || props.evaluation?.status == '0') {
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
        :evaluation="props.evaluation"
        :currentUser="props.currentUser"
        @fetch:evaluation="$emit('fetch:evaluation')"
    >
      <template v-slot:header></template>
      <template v-slot:main></template>
      <template v-slot:footer><TakeNote /></template>
      <template v-slot:cta="{ onSave, isSubmitting }">
        <div class="cta">
          <router-link :to="{ name: 'dashboard' }" class="button default with-icon" >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            <span>Back</span>
          </router-link>
          <button type="button" class="button submit" @click="onSave">{{ 'Save Changes' }}</button>
          <button type="submit" v-if="props.evaluation?.review?.response?.submitted !== '1'" class="button submit flex items-center">
            <IconSpinner v-if="isSubmitting" />
            <span>Submit Peer Review</span>
          </button>
        </div>
      </template>
    </component>
  </div>
</template>
