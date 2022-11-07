<script lang="ts" setup>
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { cloneDeep, debounce } from 'lodash'
import useFetch from '@/composables/useFetch'

import type {IEvaluation, IMixedResponse, IRubricResponse, ISimpleResponse} from '@/types/typings'
interface Props {
  evaluation: IEvaluation
  initialState: ISimpleResponse | IRubricResponse | IMixedResponse
}
// REFERENCES
const emit = defineEmits<{
  (e: 'submit', option: any): void
  (e: 'save', option: any): void
}>()
const props = defineProps<Props>()
const router = useRouter()
const { values, errors, meta, handleSubmit, isSubmitting } = useForm({
  initialValues: props.initialState?.data,
})
// DATA
const evaluation_form = ref()
const message         = ref({})
// COMPUTED
// METHODS
function onInvalidateSubmit({ errors }: any) {
  const fieldName = Object.keys(errors)[0]
  const fieldElement = document.getElementById(fieldName)
  fieldElement?.focus?.()
  fieldElement?.scrollIntoView({ behavior: 'smooth' })
}
const onSubmit = handleSubmit(async () => {
  setNotification({})
  try {
    const formData = new FormData(evaluation_form.value)
    formData.append('_method', 'POST')
    const searchParams = new URLSearchParams()
    for (const pair of formData) {
      searchParams.append(pair[0], pair[1])
    }
    const response = await useFetch(`/evaluations/makeEvaluation/${props.evaluation?.id}/${props.evaluation?.group?.id}`, {
      method: 'POST',
      timeout: 300,
      body: searchParams
    })
    await setNotification({message: response.message, type: response.statusText})
    if(response.status === 200) {
      await router.push({ name: 'dashboard' })
    }
  } catch (err) {
    setNotification({message: err.message, type: 'error'})
  }
}, onInvalidateSubmit)
async function onSave() {
  setNotification({})
  try {
    const formData = new FormData(evaluation_form.value)
    formData.append('_method', 'PUT')
    const searchParams = new URLSearchParams()
    for (const pair of formData) {
      searchParams.append(pair[0], pair[1])
    }
    const response = await useFetch(`/evaluations/makeEvaluation/${props.evaluation?.id}/${props.evaluation?.group?.id}`, {
      method: 'POST',
      timeout: 300,
      body: searchParams
    })
    await setNotification({message: response.message, type: response.statusText})
  } catch (err) {
    await setNotification({message: 'something went wrong...', type: 'error'})
  }
}
function setNotification(event) {
  message.value = event
}
// WATCH
watch(() => cloneDeep(props.initialState), debounce(async (current, previous) => {
  setNotification({})
  try {
    const formData = new FormData(evaluation_form.value)
    formData.append('_method', 'PUT')
    const searchParams = new URLSearchParams()
    for (const pair of formData) {
      searchParams.append(pair[0], pair[1])
    }
    const response = await useFetch(`/evaluations/makeEvaluation/${props.evaluation?.id}/${props.evaluation?.group?.id}`, {
      method: 'POST',
      timeout: 300,
      body: searchParams
    })
    setNotification({message: response.message, type: 'success'})
  } catch (err) {
    setNotification({message: err.message, type: 'error'})
  }
}, 5000), { deep: true })
// LIFECYCLE
</script>

<template>
  <form novalidate @submit.prevent="onSubmit" id="evaluation_form" class="evaluation-form" ref="evaluation_form">
    <slot :evaluation-ref="evaluation_form" :onSave="onSave" :is-submitting="isSubmitting" :errors="errors" :values="values" :form-meta="meta" :message="message" />
  </form>
</template>
