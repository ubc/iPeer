<script lang="ts" setup>
import {isEmpty} from 'lodash'
import { shortDateFormat } from '@/helpers'
import { IconClock, IconTimesSolid, IconCheckSolid } from "@/components/icons";
import type { IEvent } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  row: IEvent
  count: number
}>()
</script>

<template>
  <tr v-if="!props.row || isEmpty(props.row)">
    <td :colspan="props.count">
      <div class="no-content-found">No Content found!</div>
    </td>
  </tr>
  <tr v-else>
    <td>
      <div class="work">
        <div class="event-title">{{ row?.event?.title }}</div>
        <div class="group-name">{{ row?.group?.group_name }}</div>
      </div>
    </td>
    <td>
      <div class="your-status">
        <component class="icon" :is="row?.event?.is_submitted === '1' && row?.event?.is_result_released ? IconCheckSolid : IconTimesSolid" />
        <template v-if="row?.event?.is_submitted === '1' && row?.event?.is_result_released">
          <router-link class="text completed" :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
            Completed
          </router-link>
        </template>
        <template v-else>
          <span class="text not-done">Not Done</span>
        </template>
      </div>
    </td>
    <td>
      <div class="course-course">
        <div class="course">{{ row?.course?.course }}</div>
        <div class="term" v-if="row?.course?.term">({{ row?.course?.term }})</div>
      </div>
    </td>
    <td>
      <div class="due-date">
        <div class="date font-normal">
          {{ shortDateFormat(row?.event?.due_date) }}
        </div>
      </div>
    </td>
    <td>
      <div class="action">
        <router-link
            v-if="row?.event?.is_released && row?.event?.is_result_released && !row?.event?.is_ended"
            :class="`button submit flex-1 text-center`"
            :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >
          See Reviews of Me
        </router-link>
        <router-link
            :class="`button submit flex-1 text-center`"
            v-if="row?.event?.is_released && !row?.event?.is_result_released && !row?.event?.is_ended"
            :to="{ name: 'submission.view', params: { event_id: row?.event?.id, group_id: row?.group?.id } }" >
          Edit My Response
        </router-link>
        <span
            class="text"
            v-if="row?.event?.is_released && row?.event?.is_result_released && !row?.event?.is_ended &&
            (new Date(row?.event?.result_release_date_begin).toLocaleDateString('en-CA') >= new Date().toLocaleDateString('en-CA'))">
          Peers' reviews of you will be available starting {{ shortDateFormat(row?.event?.result_release_date_begin) }}
        </span>
      </div>
    </td>
  </tr>
</template>
