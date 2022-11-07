<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent, onErrorCaptured, toRef} from 'vue'
import useFetch from '@/composables/useFetch'
import CurrentWork from '@/student/components/tables/CurrentWork.vue'
import CompletedWork from '@/student/components/tables/CompletedWork.vue'
import Loader from '@/components/Loader.vue'
import Error from '@/components/Error.vue'
import PageHeading from '@/components/PageHeading.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import {IconNotepad, IconCheckedBox} from '@/components/icons'
import type { IUser, IPageHeading } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  currentUser: IUser
  settings: IPageSetting
}>()
// DATA
const error = ref<object | null>(null)
// COMPUTED
// METHODS
function onErrorCaptured(event: any) {
  error.value = event
}
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="events to layout">
    <!-- TODO:: to be added to notification component-->
    <!-- <div v-if="error" class="events error">{{ JSON.stringify(error, null, 2) }}</div>-->
    <PageHeading :settings="props.settings" />

    <section class="current-work">
      <SectionTitle title="Current Work To Do" />
      <SectionSubtitle subtitle="Do the work assigned to you" :icon="{src: IconCheckedBox, size: '3.5rem'}">
        <p class="mx-4">Instructors will set specific timeframes for reviewing your group. When work is available, it will appear here until you complete it or the final due date passes.</p>
      </SectionSubtitle>

      <Suspense>
        <template #default>
          <CurrentWork :current-user="currentUser" />
        </template>
        <template #fallback>
          <Loader />
        </template>
      </Suspense>
    </section>

    <section class="completed-work">
      <SectionTitle title="Closed or Completed Work"></SectionTitle>
      <SectionSubtitle subtitle="See past work and reviews of your teamwork" :icon="{src: IconNotepad, size: '3.25rem'}">
        <p class="mx-4">Below you can find previously assigned work that you have completed or that has closed. After a peer review closes, your instructor may or may not let you see how your peers evaluated you. If any reviews of you are available, they will be linked below.</p>
      </SectionSubtitle>

      <Suspense>
        <template #default>
          <CompletedWork :current-user="currentUser" />
        </template>
        <template #fallback>
          <Loader />
        </template>
      </Suspense>
    </section>
  </div>
</template>
