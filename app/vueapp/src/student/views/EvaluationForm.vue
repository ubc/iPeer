<script lang="ts" setup>
import { ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import { useEvaluationStore } from '@/stores/evaluation'
import { useForm } from 'vee-validate'
import { cloneDeep, debounce } from 'lodash'
import type {IEvaluation, IMixedResponse, IRubricResponse, ISimpleResponse} from '@/types/typings'

interface Props {
  evaluation: IEvaluation
  initialState: ISimpleResponse | IRubricResponse | IMixedResponse
}

// REFERENCES
const emit = defineEmits<{
  (e: 'on:submit', option: any): void
  (e: 'on:save', option: any): void
}>()
const props = defineProps<Props>()
const router = useRouter()
const {values, errors, meta, handleSubmit, isSubmitting} = useForm({
  initialValues: props.initialState?.data,
})
const evaluationStore = useEvaluationStore()
// DATA
const evaluation_form = ref()
const message = ref<object|null>(null)
const autosave = ref<boolean>(false)
// COMPUTED
// METHODS
function onInvalidateSubmit({errors}: any) {
  const fieldName = Object.keys(errors)[0]
  const fieldElement = document.getElementById(fieldName)
  fieldElement?.focus?.()
  fieldElement?.scrollIntoView({behavior: 'smooth'})
}

const onSubmit = handleSubmit(async () => {
  message.value = null
  const formData = new FormData(evaluation_form.value)
  formData.append('_method', 'POST')
  const searchParams = new URLSearchParams()
  for (const pair of formData) {
    searchParams.append(pair[0], pair[1])
  }
  await evaluationStore.makeEvaluation(searchParams, props.evaluation?.id, props.evaluation?.group?.id)
}, onInvalidateSubmit)

async function onSave() {
  const formData = new FormData(evaluation_form.value)
  formData.append('_method', 'PUT')
  const searchParams = new URLSearchParams()
  for (const pair of formData) {
    searchParams.append(pair[0], pair[1])
  }
  await evaluationStore.editEvaluation(searchParams, props.evaluation?.id, props.evaluation?.group?.id)
}

// WATCH
watch(() => cloneDeep(props.initialState), debounce(async (current, previous) => {
  if(!props.evaluation?.is_result_released) {
    const formData = new FormData(evaluation_form.value)
    formData.append('_method', 'PUT')
    const searchParams = new URLSearchParams()
    for (const pair of formData) {
      searchParams.append(pair[0], pair[1])
    }
    await evaluationStore.autoSaveEvaluation(searchParams, props.evaluation?.id, props.evaluation?.group?.id)
  }
}, 5000), {deep: true})
// LIFECYCLE
</script>

<template>
  <form novalidate @submit.prevent="onSubmit" id="evaluation_form" class="evaluation-form" ref="evaluation_form">
    <slot :values="values"
          :errors="errors"
          :form-meta="meta"
          :is-submitting="isSubmitting"
          :onSave="onSave"
          :evaluation-ref="evaluation_form"/>
  </form>
</template>
