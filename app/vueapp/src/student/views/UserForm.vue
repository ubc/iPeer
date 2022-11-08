<script lang="ts" setup>
import {ref, toRef, watch} from 'vue'
import {useRouter} from 'vue-router'
import { useForm } from 'vee-validate'
import api from '@/services/api'
import type {IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'set:message', option: object): void
  (e: 'update:profile', option: object): void
}>()
const props = defineProps<{
  initialState: IUser
}>()
// DATA
const form    = toRef(props, 'initialState')
const userRef = ref(null)
const message = ref<object|null>(null)
// COMPUTED
const { values, errors, meta, handleSubmit, isSubmitting } = useForm({
  initialValues: form.value,
})
// METHODS
function onInvalidateSubmit({ values, errors, results }: any) {
  const fieldName = Object.keys(errors)[0]
  const fieldElement = document.getElementById(fieldName)
  fieldElement?.focus?.()
  fieldElement?.scrollIntoView({ behavior: 'smooth' })
}
const onSubmit = handleSubmit(async (values) => {
  const profileSearchParams: any = new URLSearchParams()
  for (const pair of Object.entries(values)) {
    profileSearchParams.append(pair[0], pair[1])
  }
  try {
    const response = await api.post('/users/editProfile', props.initialState?.id, profileSearchParams)
    if(response.status === 200 && response.statusText === 'OK') {
      message.value = response.data
      const user = await api.get('/users/editProfile', props.initialState?.id)
      await emit('update:profile', user.data)
    } else {
      // message.value = response.data
      // No response data available for this request
      await useRouter().push({ name: 'user.login' })
    }
  } catch (err: any) {
    message.value = {text: err.response.statusText, status: err.response.status, type: 'error'}
  }
}, onInvalidateSubmit)
// WATCH
watch(message, (event: object) => {
  emit('set:message', event)
}, { deep: true })
// LIFECYCLE
</script>

<template>
  <form novalidate @submit.prevent="onSubmit" id="user_form" class="user-form" ref="user_form">
    <slot :values="values" :errors="errors" :meta="meta" :isSubmitting="isSubmitting" :message="message" />
  </form>
</template>
