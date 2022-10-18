<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';

import type {Evaluation, User} from '@/types/typings'
import {useRouter} from "vue-router";

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  currentUser: User
  evaluation: Evaluation
}>()

// DATA

// COMPUTED
const template = computed(() => {
  if(props.evaluation?.template) {
    return defineAsyncComponent({
      loader: () => import(`@/student/views/templates/${props.evaluation?.template}.vue`),
      loadingComponent: `<div class="w-full h-128 bg-gold-100">L O A D I N G...</div>`,
      errorComponent: `<div class="w-full h-128 bg-red-100">E R R O R...</div>`,
      delay: 5000,
      onError: error => "CAN'T DO"
    })
  }
})
// METHODS

// WATCH

// LIFECYCLE
onMounted(() => {
  if(props.evaluation?.status === null) {
    useRouter().push({ name: 'evaluation.make' })
  }
})
</script>

<template>
  <div class="evaluation-edit-page bg-red-50">
    <component
        :is="template"
        action="Save"
        _method="PUT"
        :currentUser="currentUser"
        :evaluation="evaluation"
    >
      <template v-slot:cta="{ onSave }">
        <div class="cta">
          <router-link :to="{ name: 'dashboard' }" class="button btn-lg default with-icon" >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            <span>Back</span>
          </router-link>
          <button v-if="false" type="button" class="button btn-lg submit" @click="onSave">{{ 'Save Changes' }}</button>
          <button type="submit" class="button btn-lg submit">{{ 'Save Changes' }}</button>
        </div>
      </template>
    </component>
  </div>
</template>
