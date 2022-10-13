<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router'
import useFetch from '@/composables/useFetch'
import Loader from '@/components/Loader.vue'
import PageTitle from '@/components/PageTitle.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import ViewHeading from '@/student/components/ViewHeading.vue'
import { IconTwoUsers } from '@/components/icons'
// REFERENCES
const emit              = defineEmits<{}>()
const props             = defineProps<{}>()
const route             = useRoute()
// DATA
const event_id          = ref(route.params.event_id)
const group_id          = ref(route.params.group_id)
const status            = ref()
const message           = ref()
const submission        = reactive({})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
onMounted(async () => {
  try {
    status.value = 'PENDING'
    const response = await useFetch(
        `evaluations/makeEvaluation/${event_id.value}/${group_id.value}`,
        {method: 'GET', timeout: 300}
    )
    Object.assign(submission, response.data)
  } catch (err) {
    message.value = {text: err.messge, type: 'error'}
  } finally {
    status.value = 'READY'
  }
})
</script>

<template>
  <div class="">
    <template v-if="status === 'PENDING'">
      <Loader />
    </template>

    <template v-else>
      <PageTitle :title="submission?.event?.title">
        <ViewHeading
          :due-date="submission?.event?.due_date"
          :penalties="submission?.penalties"
          :group-name="submission?.group?.group_name"
          :course-title="submission?.course?.title"
          :icon="{src: submission, size: '6rem'}"
        />
      </PageTitle>
      <pre class="debug">Submission::Info {{ event_id }}/{{ group_id }}</pre>
    </template>

  </div>
</template>
