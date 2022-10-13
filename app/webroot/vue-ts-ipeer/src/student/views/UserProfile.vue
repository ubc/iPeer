<script setup lang="ts">
import { toRef } from 'vue'
import { useField, useForm } from 'vee-validate'
import * as Yup from 'yup'
import swal from 'sweetalert'
import useFetch from '@/composables/useFetch'

import Loader from '@/components/Loader.vue'
import PageTitle from '@/components/PageTitle.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import VInputField from '@/components/fields/VInputField.vue'
import { IconUser, IconKey, IconSpinner } from '@/components/icons'

import type { User } from '@/types/typings'
// REFERENCES
const emit              = defineEmits<{
  (e: 'update:profile'): void
}>()
const props = defineProps<{
  user: User
}>()

const currentUser              = toRef(props, 'user')
const { meta, values, errors, handleSubmit, isSubmitting }  = useForm({
  // initialValues: currentUser.value
});

/** TODO:: onInvalidSubmit can scroll to fields with error */
function onInvalidSubmit({ values, errors, results }: any) {
  // console.log(values); // current form values
  // console.log(errors); // a map of field names and their first error message
  // console.log(results); // a detailed map of field names and their validation results
}
const onSubmit = handleSubmit(async (values) => {
  const profileSearchParams: any = new URLSearchParams()
  for (const pair of Object.entries(values)) {
    profileSearchParams.append(pair[0], pair[1])
  }
  try {
    const response = await useFetch(
        `${import.meta.env.VITE_BASE_URL}/users/editProfile/${currentUser.value?.id}`,
        {
          method: 'POST',
          timeout: 300,
          body: profileSearchParams,
        }
    )
    await emit('update:profile')
    await swal({text: response.message, icon: response.statusText})
  } catch (err) {
    await swal({text: err.message, icon: err.statusText})
  }

}, onInvalidSubmit);

const { value: username } = useField('data[User][username]', Yup.string().trim().required().min(2).label('Username'), {
  initialValue: currentUser.value['username']
})
const { value: first_name } = useField('data[User][first_name]', Yup.string().trim().required().min(2).label('First name'), {
  initialValue: currentUser.value['first_name']
})
const { value: last_name } = useField('data[User][last_name]', Yup.string().trim().required().min(2).label('Last name'), {
  initialValue: currentUser.value['last_name']
})
const { value: email } = useField('data[User][email]', Yup.string().required().trim().email().label('Email'), {
  initialValue: currentUser.value['email']
})
const { value: student_no } = useField('data[User][student_no]', Yup.string().trim().required().min(2).label('Student number'), {
  initialValue: currentUser.value['student_no']
})
const { value: old_password } = useField(
    'data[User][old_password]',
    Yup.string()
        .trim(),
    {initialValue: ''}
)
const { value: temp_password } = useField(
    'data[User][temp_password]',
    Yup.string()
        .trim()
        .label('New password'),
    {initialValue: ''}
)
const { value: confirm_password } = useField(
    'data[User][confirm_password]',
    Yup.string()
        .trim()
        .test('passwords-match', 'New Passwords do not match', function(value) {
          return parent['data[User][temp_password]']['value'] === value;
        })
        .label('Confirm password'),
    {initialValue: ''}
)
</script>

<template>
  <PageTitle title="Edit Profile" />
  <form @submit="onSubmit" novalidate class="flex flex-col">
    <div style="display:none;"><VInputField type="hidden" name="_action" value="save" /></div>
    <div style="display:none;"><VInputField type="hidden" name="_method" value="PUT" /></div>

    <div class="form-section">
      <SectionTitle title="Your Account" class="mt-8" />
      <SectionSubtitle subtitle="Update your iPeer profile" :icon="{src: IconUser, size: '3.5rem'}">
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>
      </SectionSubtitle>

      <section class="mt-6 mb-4">
        <VInputField type="text" name="data[User][username]" label="Username" v-model="username" :disabled="true" readonly="readonly" />
        <VInputField type="text" name="data[User][first_name]" label="First name" v-model="first_name" />
        <VInputField type="text" name="data[User][last_name]" label="Last name" v-model="last_name" />
        <VInputField type="text" name="data[User][email]" label="Email" v-model="email" />
        <VInputField type="text" name="data[User][student_no]" label="Student number" v-model="student_no" :disabled="true" readonly="readonly" />
      </section>
    </div>

    <div class="form-section">
      <SectionTitle title="Your Password" />
      <SectionSubtitle subtitle="Change your iPeer password" :icon="{src: IconKey, size: '3.5rem'}">
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>
      </SectionSubtitle>

      <section class="mt-6 mb-4">
        <VInputField type="password" name="data[User][old_password]" label="Old password" v-model="old_password" />
        <VInputField type="password" name="data[User][temp_password]" label="New password" v-model="temp_password" />
        <VInputField type="password" name="data[User][confirm_password]" label="Confirm new password" v-model="confirm_password" />
      </section>
    </div>

    <div class="cta">
      <button type="submit" class="button submit btn-lg flex items-center space-x-2" :disabled="!meta.valid === meta.touched">
        <IconSpinner class="w-4 h-4" v-if="isSubmitting" /> Save
      </button>
    </div>
  </form>
</template>
