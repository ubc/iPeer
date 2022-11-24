<script lang="ts" setup>
  import { computed } from 'vue'
  import { isEmpty } from 'lodash'
  import { longDateFormat, isOverDue, isDueTomorrow } from '@/helpers'
  import { IconClock, IconWarning } from '@/components/icons'
  interface Props {
    row: object
    count: number
  }
  // REFERENCES
  const emit = defineEmits<{}>()
  const props = defineProps<Props>()
  // DATA
  // COMPUTED
  const is_empty = computed(() => !props.row || isEmpty(props.row))
  // METHODS
  // WATCH
  // LIFECYCLE
</script>

<template>
  <tr v-if="is_empty">
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
      <div class="course-course">
        <div class="course">{{ row?.course?.course }}</div>
      </div>
    </td>
    <td>
      <div class="due-date">
        <template v-if="isOverDue(row?.event?.due_date)">
          <div class="overdue">
            <span class="heading">OVERDUE</span>
            <span class="text">
              <IconWarning class="flex-none w-6 h-6 pt-0.5" />
              <span>Hurry! Late evaluations are being allowed for a limited time.</span>
            </span>
          </div>
        </template>
        <template v-else>
          <div class="due-tomorrow" v-if="isDueTomorrow(row?.event?.due_date)">
            <span class="heading">Due tomorrow</span>
          </div>
          <span class="date" v-if="!isOverDue(row?.event?.due_date)">
            <IconClock class="icon" />
            <span class="text">{{ longDateFormat(row?.event?.due_date) }}</span>
          </span>
        </template>
      </div>
    </td>
    <td>
      <div class="action">
        <router-link
            :class="`button ${row?.event?.is_submitted==='0' ? 'default' : 'primary'} flex-1 text-center`"
            :to="{ name: 'evaluation.make', params: { event_id: row?.event?.id, group_id: row?.group?.id } }">
          {{ row?.event?.is_submitted==='0' ? 'Continue Eval.' : 'Evaluate Peers' }}
        </router-link>
      </div>
    </td>
  </tr>
</template>
