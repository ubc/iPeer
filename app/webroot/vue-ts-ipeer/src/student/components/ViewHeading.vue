<script setup lang="ts">
import { longDateFormat } from '@/helpers'
import { computed } from 'vue'

interface Props {
  dueDate?: string
  penalties?: string
  groupName?: string
  courseTitle?: string
  icon?: object
}
const props = defineProps<Props>()

const due_date = computed(() => props.dueDate ? longDateFormat(props.dueDate) : null)
</script>

<template>
  <div class="content-wrapper flex justify-between items-start my-4">
    <div class="flex-col space-y-2">
      <h5 class="page-subtitle text-xl leading-relaxed text-slate-900 font-normal">{{ courseTitle }} {{ groupName }}</h5>
      <div class="flex flex-col text-sm leading-5 text-slate-700 font-normal">
        <span class="space-x-2">
          <span class="font-medium">Due:</span>
          <span class="font-normal" v-if="due_date">{{ due_date }}</span>
          <span class="font-normal" v-else>N/A</span>
        </span>
        <span class="space-x-2 ">
          <span class="font-medium">Late Policy:</span>
          <span class="font-normal" v-if="penalties?.length">Submit up to {{ penalties[penalties.length - 1]['days_late'] }} day(s) late, with {{ penalties[penalties.length - 1]['percent_penalty'] }}% deducted from your mark.</span>
          <span class="font-normal" v-else>N/A</span>
        </span>
      </div>
    </div>
    <component :is="icon?.src" class="icon" :style="{'width': icon?.size}" />
  </div>
</template>
