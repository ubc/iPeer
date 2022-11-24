<script lang="ts" setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import PageHeading from '@/components/PageHeading.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import {IconNotepad, IconCheckedBox} from '@/components/icons'
import CurrentWork from '@/student/components/tables/CurrentWork.vue'
import CompletedWork from '@/student/components/tables/CompletedWork.vue'

import type { IUser } from '@/types/typings'
interface Props {currentUser?: IUser}
// REFERENCES
const emit        = defineEmits<{}>()
const props       = defineProps<Props>()
const route       = useRoute()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="events to layout">
    <PageHeading :settings="route.meta" />

    <section class="current-work">
      <SectionTitle title="Current Work To Do" />
      <SectionSubtitle subtitle="Do the work assigned to you" :icon="{src: IconCheckedBox, size: '3.5rem'}">
        <p class="mx-4">Instructors will set specific timeframes for reviewing your group. When work is available, it will appear here until you complete it or the final due date passes.</p>
      </SectionSubtitle>
      <CurrentWork :current-user="props.currentUser" />
    </section>

    <section class="completed-work">
      <SectionTitle title="Closed or Completed Work"></SectionTitle>
      <SectionSubtitle subtitle="See past work and reviews of your teamwork" :icon="{src: IconNotepad, size: '3.25rem'}">
        <p class="mx-4">Below you can find previously assigned work that you have completed or that has closed. After a peer review closes, your instructor may or may not let you see how your peers evaluated you. If any reviews of you are available, they will be linked below.</p>
      </SectionSubtitle>
      <CompletedWork :current-user="props.currentUser" />
    </section>
  </div>
</template>
