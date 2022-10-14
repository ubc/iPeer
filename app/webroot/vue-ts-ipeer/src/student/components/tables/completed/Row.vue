<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { shortDateFormat } from '@/helpers'
import { IconClock, IconTimes, IconCheckmark } from "@/components/icons";
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  row: object
}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <tr>
    <td>
      <div class="work">
        <div class="event-title text-base text-slate-900 leading-5 font-normal tracking-wide">{{ row?.event?.title }}</div>
        <div class="group-name text-sm text-slate-700 leading-4 font-light tracking-wide">{{ row?.group?.group_name }}</div>
      </div>
    </td>
    <td>
      <div class="status flex items-center space-x-1 text-sm">
        <component :is="row?.event?.record_status === 'A' ? IconCheckmark : IconTimes" class="w-5 h-5" />
        <span :class="row?.event?.record_status === 'A' ? 'completed' : 'not-done'">{{ row?.event?.record_status === 'A' ? 'Completed' : 'Not Done' }}</span>
      </div>
    </td>
    <td>
      <div class="courses">
        <div class="text-sm text-slate-900 leading-5 font-light">{{ row?.course?.course }}</div>
        <div class="text-sm text-slate-700 leading-4 font-light tracking-wider" v-if="row?.course?.term">({{ row?.course?.term }})</div>
      </div>
    </td>
    <td>
      <div class="due flex justify-start items-center space-x-2">
        <IconClock class="w-6 h-6" />
        <span class="text-sm text-slate-900 leading-4 font-light">{{ shortDateFormat(row?.event?.due_date) }}</span>
      </div>
    </td>
    <td>
      <!--  NOTE:: calculate the cell width dynamically to maintain the column width aspect ratio  -->
      <div class="flex" style="min-width: 157.5px">
        <router-link
            v-if="row?.event?.is_submitted === '1' && row?.event?.is_result_released"
            :class="`button ${true ? 'submit' : ''} flex-1 text-center btn-lg`"
            :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >See Reviews of Me</router-link>
        <router-link
            v-if="row?.event?.is_submitted === '1' && !row?.event?.is_result_released"
            :class="`button ${true ? 'submit' : ''} flex-1 text-center btn-lg`"
            :to="{ name: 'evaluation.edit', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >Edit My Response</router-link>
        <span v-if="
            row?.event?.is_ended &&
            (new Date(row?.event?.result_release_date_begin).toLocaleDateString('en-CA') >= new Date().toLocaleDateString('en-CA'))
            " class="text-sm text-slate-700 leading-4 font-light tracking-wide">Peers' reviews of you will be available starting {{ shortDateFormat(row?.event?.result_release_date_begin) }}</span>
      </div>
    </td>
  </tr>
</template>
