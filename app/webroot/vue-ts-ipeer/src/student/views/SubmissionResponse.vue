<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent} from 'vue';

import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconWritingHand } from '@/components/icons'

import type {IEvaluation, IUser} from '@/types/typings'
import TakeBreak from "@/student/components/TakeBreak.vue";

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

  <TakeBreak />
</template>

<style lang="scss" scoped>

</style>
