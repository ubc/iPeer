<script lang="ts" setup>
import {ref, reactive, watch, computed, onMounted, defineAsyncComponent, toRef} from 'vue';
// import { useForm } from 'vee-validate'
import { map } from 'lodash'
import useFetch from '@/composables/useFetch'
//
import Debugger from '@/components/Debugger.vue'
//
import type {Evaluation, User} from '@/types/typings'
import swal from "sweetalert";
// REFERENCES
const emit = defineEmits<{
  (e: 'on:save', option: () => {}): void
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  form: object
  evaluation: Evaluation
  currentUser: User
}>()

// DATA

// COMPUTED

// METHODS
// function onInvalidSubmit({ values, errors, results }: any) {
//   console.log(values); // current form values
//   console.log(errors); // a map of field names and their first error message
//   console.log(results); // a detailed map of field names and their validation results
// }
// const { handleSubmit } = useForm({
//   // initialValues: props.evaluation?.review?.response || { points: [], comments: [] }
//   initialValues: props.form || { points: [], comments: [] }
// });

/**
const onSubmit2 = handleSubmit(async (values) => {
  // const formData = new FormData(values)
  alert(JSON.stringify(values, null, 2));

  // const profileSearchParams: any = new URLSearchParams()
  // for (const pair of Object.entries(values)) {
  //   profileSearchParams.append(pair[0], pair[1])
  // }
  // `${import.meta.env.VITE_BASE_URL}/users/editProfile/${currentUser.value?.id}`,
  try {
    const response = await useFetch(
        `${import.meta.env.VITE_BASE_URL}/evaluations/makeEvaluation/${props.evaluation?.id}/${props.evaluation?.group?.id}`,
        {
          method: 'POST',
          timeout: 300,
          body: JSON.stringify(values)
        }
    )
    await swal({text: response.message, icon: response.statusText})

  } catch (err) {
    await swal({text: err.message, icon: err.statusText})
  }

}, onInvalidSubmit);
*/
async function onSubmit(e: HTMLFormElement | any) {
  e.preventDefault()
  // NOTE::Collect formData
  const formData = new FormData(e.target)
  formData.append('action', 'Submit')
  // NOTE::
  const searchParams = new URLSearchParams()
  // NOTE::Iterating the search parameters
  for (const pair of formData) {
    searchParams.append(pair[0], pair[1])
  }

  try {
    const response = await useFetch(
        `${import.meta.env.VITE_BASE_URL}/evaluations/makeEvaluation/${props.evaluation?.id}/${props.evaluation?.group?.id}`,
        {
          method: 'POST',
          timeout: 300,
          body: searchParams
        }
    )
    await swal({text: response.message, icon: response.statusText})

  } catch (err) {
    await swal({text: err.message, icon: err.statusText})
  }
}


/**
const onSave = handleSubmit(values => {
  alert(JSON.stringify(values, null, 2));
  const formRef = document.getElementById('evaluation_form');
  const formData = new FormData(formRef)
  formData.append('_method', 'PUT')
  formData.append('action', 'Save')
  // NOTE::
  const searchParams = new URLSearchParams()
  // NOTE::Iterating the search parameters
  for (const pair of formData) {
    searchParams.append(pair[0], pair[1])
  }
  alert(JSON.stringify(searchParams, null, 2));
  //useFetch('POST', `/evaluations/makeEvaluation/${props.form.event_id}/${props.form.group_id}`, searchParams)
})
*/
// WATCH

// LIFECYCLE

</script>

<template>
  <Debugger :title="`EvaluationForm::${props.evaluation?.template}`" :state="props.currentUser" :form="props.form" :data="{}" />
  <form @submit.prevent="onSubmit" class="evaluation-form">
    <slot name="header"></slot>
    <slot name="main" :form="props.form"></slot>
    <slot name="footer"></slot>
    <slot name="action" :on-save="onSave"></slot>
  </form>
</template>