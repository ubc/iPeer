<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import swal from 'sweetalert'
import useFetch from '@/composables/useFetch'

import { jsonToFormData } from '@/helpers'
import Debugger from '@/components/Debugger.vue'

import type {Evaluation, EvaluationReviewResponse} from '@/types/typings'

interface Props {
  initialState: EvaluationReviewResponse
  evaluation: Evaluation
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
// COMPUTED
// METHODS
function onInvalidateSubmit({ errors }) {
  const fieldName = Object.keys(errors)[0]
  const fieldElement = document.getElementById(fieldName)
  fieldElement?.focus?.()
  fieldElement?.scrollIntoView({ behavior: 'smooth' })
}

/** TBD */
const _onSubmit = handleSubmit(() => {
  emit('submit', values)
}, onInvalidateSubmit)

const onSubmit = handleSubmit(async () => {
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
  await swal({text: response.message, icon: response.statusText})
  if(response.status === 200) {
    await router.push({ name: 'dashboard' })
  }
}, onInvalidateSubmit)


async function onSave() {
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
    await swal({text: response.message, icon: response.statusText})
  } catch (err) {
    await swal({text: 'something went wrong...', icon: 'error'})
  }
}
// WATCH
// LIFECYCLE
</script>

<template>
  <form @submit.prevent="onSubmit" ref="evaluation_form" id="evaluation_form" class="evaluation-form">
    <slot :evaluation-ref="evaluationRef" :onSave="onSave" :isSubmitting="isSubmitting" :errors="errors" :values="values" />
  </form>
  <Debugger class="text-sm" :state="values" />
</template>
