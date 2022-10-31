<script lang="ts" setup>
import { ref, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useForm } from 'vee-validate'
import { cloneDeep, debounce } from 'lodash'
import useFetch from '@/composables/useFetch'
import swal from 'sweetalert'
import Debugger from "@/components/Debugger.vue";

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
const mode            = ref(import.meta.env.MODE === 'development')
const debug           = ref(false)
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
  console.log({'event_id': props.evaluation?.id})
  console.log({'group_id': props.evaluation?.group?.id})
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
    // if(response.status === 200) {
    //   await router.push({ name: 'dashboard' })
    // }
    await swal({text: response.message, icon: response.statusText})
  } catch (err) {
    await swal({text: 'something went wrong...', icon: 'error'})
  }
}
// WATCH
watch(() => cloneDeep(props.initialState), debounce(async (current, previous) => {
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
    // TODO:: add notification component to replace swal
    console.table(response.message, 'auto-saved every 5000ms!', )
  } catch (err) {
    // TODO:: add notification component to replace swal
    console.warn('auto-save error.', err.message)
  }
}, 5000), { deep: true })
// LIFECYCLE
</script>

<template>
  <form novalidate @submit.prevent="onSubmit" id="evaluation_form" class="evaluation-form" ref="evaluation_form">
    <slot :evaluation-ref="evaluation_form" :onSave="onSave" :is-submitting="isSubmitting" :errors="errors" :values="values" :form-meta="meta" />
  </form>

  <button v-if="mode" class="debugger button default" @click="debug=!debug">{{ debug ? 'Hide' : 'Show'}} Debugger</button>
  <div v-if="debug">
    <Debugger title="Form Values/Errors" :state="values" :form="errors" />
    <Debugger title="InitialState" :state="props.initialState" />
    <Debugger title="Evaluation" :state="props.evaluation" />
    <Debugger title="Simple/Rubric/Mixed" :state="props.evaluation?.simple || props.evaluation?.rubric || props.evaluation?.mixed" />
    <Debugger title="Response" :state="props.evaluation?.response" />
  </div>
</template>
