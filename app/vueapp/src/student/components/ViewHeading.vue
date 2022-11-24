<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { longDateFormat } from '@/helpers'

interface Props {
  template?: string
  dueDate?: string
  submittedDate?: string
  isResultReleased?: boolean
  penalty?: object
  group?: string
  course?: string
  icon?: object
}
const props = defineProps<Props>()
const route = useRoute()

const due_date = computed(() => props.dueDate ? longDateFormat(props.dueDate) : null)
const submitted_date = computed(() => props.submittedDate ? longDateFormat(props.submittedDate) : null)
</script>

<template>
  <div class="content-wrapper flex justify-between items-start my-4">
    <div class="flex-col space-y-2">
      <h5 class="page-subtitle">{{ props.course?.course }} {{ props.group?.name }}</h5>
      <div class="flex flex-col leading-5">
        <span class="text-sm tracking-wider space-x-2">
          <span class="due">Due:</span>
          <span class="date" v-if="props.dueDate">{{ due_date }}</span>
          <span class="n-a" v-else>N/A</span>
        </span>
        <span class="text-sm tracking-wider space-x-2" v-if="route.matched[1]['path'] === '/evaluations'">
          <span class="policy">Late Policy:</span>
          <span class="penalty" v-if="props.penalty">Submit up to {{ props.penalty.days_late }} day(s) late, with {{ props.penalty.percent_penalty }}% deducted from your mark.</span>
          <span class="n-a" v-else>N/A</span>
        </span>
        <span class="text-sm tracking-wider space-x-2" v-else-if="route.matched[1]['path'] === '/submissions'">
          <span class="release">
            You Submitted this peer review {{ submitted_date }}
            <router-link v-if="!props.isResultReleased" :to="{ name: 'evaluation.edit' }">Edit ({{ props.isResultReleased }})</router-link>
          </span>
        </span>
      </div>
    </div>
    <component :is="props.icon?.src" class="icon" :style="{'width': props.icon?.size}" />
  </div>
</template>
