<script lang="ts" setup>
import { ref } from 'vue'
import { useForm } from 'vee-validate'
import { useAuthStore } from '@/stores/auth'
import type { IUser } from '@/types/typings'
interface Props {
  initialState: IUser
}
// REFERENCES
const emit    = defineEmits<{}>()
const props   = defineProps<Props>()
const auth    = useAuthStore()
// DATA
const userRef = ref(null)
// COMPUTED
const { values, errors, meta, handleSubmit, isSubmitting } = useForm({
  initialValues: props.initialState
})
// METHODS
function onInvalidateSubmit({ values, errors, results }: any) {
  const fieldName = Object.keys(errors)[0]
  const fieldElement = document.getElementById(fieldName)
  fieldElement?.focus?.()
  fieldElement?.scrollIntoView({ behavior: 'smooth' })
}
const onSubmit = handleSubmit(async (values: unknown) => {
  const profileSearchParams: any = new URLSearchParams()
  for (const pair of Object.entries(values)) {
    if(pair[1] === undefined) {
      profileSearchParams.append(pair[0], '')
    } else {
      profileSearchParams.append(pair[0], pair[1])
    }
  }
  await auth.updateCurrentUser(props.initialState?.id, profileSearchParams)

}, onInvalidateSubmit)
// WATCH
// LIFECYCLE
</script>

<template>
  <form novalidate @submit.prevent="onSubmit" id="user_form" class="user-form" ref="user_form">
    <slot :values="values" :errors="errors" :meta="meta" :isSubmitting="isSubmitting" />
  </form>
</template>
