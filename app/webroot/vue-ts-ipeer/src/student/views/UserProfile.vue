<script lang="ts" setup>
//...
// https://vee-validate.logaretm.com/v4/guide/composition-api/nested-objects-and-arrays/
import {toRef, ref, reactive, onMounted, watchEffect, computed,} from "vue";
import axios from 'axios'
import {configure, useForm } from 'vee-validate'
import * as Yup from 'yup'

import useLoader from '@/composables/useLoader'
import useNotifications from '@/composables/useNotifications'

import PageTitle from '@/components/PageTitle.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { VeeInputField } from '@/components/fields'
import { IconUser, IconKey, IconSpinner } from '@/components/icons'

import { User } from '@/types/typings'

// REFERENCES
const URL = 'http://localhost:8080/users/editProfile/7'
const emit = defineEmits<{
  (e: 'get:user-profile', option: null): void
}>()
const props = defineProps<{
  currentUser: User
}>()
const { loading, setLoading } = useLoader()
const { setNotification } = useNotifications()

// DATA
const user = toRef<User>(props, 'currentUser') || {}
// const schema = Yup.object().shape({
//   'data[User][username]': Yup.string().required().label('Username'),
//   'data[User][first_name]': Yup.string().required().label('First Name'),
//   'data[User][last_name]': Yup.string().required().label('Last Name'),
//   'data[User][email]': Yup.string().email().required().label('Email'),
//   'data[User][student_no]': Yup.string().required().label('Student Number'),
//   'data[User][old_password]': Yup.string().min(6).required(false),
//   'data[User][temp_password]': Yup.string().min(6).required(false).label('New Password '),
//   'data[User][confirm_password]': Yup.string().min(6).required(false)
//       .oneOf([Yup.ref('data[User][temp_password]')], 'New Passwords do not match').label('Your Password '),
// });

// COMPUTED
const form = computed(() => {
  let tmp={};
  for(const u of Object.entries(user.value)) {
    Object.assign(tmp, {[`data[User][${u[0]}]`]: u[1]})
  }
  return tmp
})
const { handleSubmit, values, errors, meta, isSubmitting } = useForm({
  // keep all values when their fields get unmounted
  // keepValuesOnUnmount: true,
  'invalid-submit': onInvalidSubmit,
  initialValues: form,
  validationSchema: Yup.object({
    'data[User][username]': Yup.string().required('Username is required').label('Username'),
    'data[User][first_name]': Yup.string().required('First name is required').label('First name'),
    'data[User][last_name]': Yup.string().required('Last name is required').label('Last name'),
    'data[User][email]': Yup.string().required('Email is required').email().label('Email'),
    'data[User][student_no]': Yup.string().required('Student Number is required').label('Student Number'),
    'data[User][old_password]': Yup.string().label('Old Password is required').label('Old Password'),
    'data[User][temp_password]': Yup.string().label('New Password is required').label('New Password'),
    'data[User][confirm_password]': Yup.string()
      .test('passwords-match', 'New Passwords do not match', function(value){
        return this.parent['data[User][temp_password]'] === value
      })
  }),
})

// METHODS
function onInvalidSubmit({ errors }) {
  console.log('onInvalidSubmit', errors)
  const fieldName = Object.keys(errors)[0]
  const element = document.querySelector(`input[name="${fieldName}"]`)
  element.scrollIntoView()
}

const onSubmit = handleSubmit(async values => {
  console.log('Submitting...')
  const searchProfileParams = new URLSearchParams()
  searchProfileParams.append('_method', 'PUT')
  searchProfileParams.append('action', 'Save')

  for (const pair of Object.entries(values)) {
    searchProfileParams.append(pair[0], pair[1])
  }

  try {
    await setLoading('PENDING')
    let response = await axios({
      method: 'POST',
      url: URL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', // application/json;
      },
      data: searchProfileParams // JSON.stringify(data)
    })
    await setNotification(response.data.response.message, 'success')
  } catch (err: any) {
    await setNotification(err.response.data.message, 'error')
  } finally {
    await emit('get:user-profile')
    await setLoading('READY')
  }
});

// WATCH

// LIFECYCLE
onMounted(() => {
  configure({
    validateOnBlur: true,
    validateOnChange: true,
    validateOnInput: true,
    validateOnModelUpdate: true
  })
})

</script>

<template>
  <div id="users" class="user-profile" :class="loading === 'PENDING' ? 'bg-slate-50' : ''">
    <PageTitle title="Edit Profile" />
    <!--   novalidate  -->
    <form @submit="onSubmit">
      <div style="display:none;"><input type="hidden" name="_method" value="PUT"></div>

      <div class="form-fields">
        <SectionTitle title="Your Account" class="mt-8" />
        <SectionSubtitle subtitle="Update your iPeer profile" :icon="{src: IconUser, size: '3.5rem'}">
          <p class="text-sm leading-relaxed text-slate-900 mx-4">Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>
        </SectionSubtitle>

        <VeeInputField type="text" name="data[User][username]" label="Username" placeholder="Username" :disabled="true" readonly="readonly" />
        <VeeInputField type="text" name="data[User][first_name]" label="First Name" placeholder="Your First Name" />
        <VeeInputField type="text" name="data[User][last_name]" label="Last Name" placeholder="Your Last Name" />
        <VeeInputField type="email" name="data[User][email]" label="E-mail" placeholder="Your Email Address" />
        <VeeInputField type="text" name="data[User][student_no]" label="Student Number" placeholder="Your Student Number" :disabled="true" readonly="readonly" />
      </div>

      <div class="form-fields">
        <SectionTitle title="Your Password" />
        <SectionSubtitle subtitle="Change your iPeer password" :icon="{src: IconKey, size: '3.5rem'}">
          <p class="text-sm leading-relaxed text-slate-900 mx-4">Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>
        </SectionSubtitle>

        <VeeInputField type="password" name="data[User][old_password]" ref="old_password" label="Old Password" placeholder="Your Old Password" />
        <VeeInputField type="password" name="data[User][temp_password]" ref="temp_password" label="New Password" placeholder="Your New Password" />
        <VeeInputField type="password" name="data[User][confirm_password]" ref="confirm_password" label="Confirm New Password" placeholder="Confirm Your New Password" />
      </div>

      <div class="cta">
        <button type="submit" class="button submit submit-btn btn-lg flex items-center space-x-2" :disabled="!meta.valid === meta.touched">
          <IconSpinner class="w-4 h-4" v-if="isSubmitting" /> Save
        </button>
      </div>

    </form>
  </div>
</template>

<style lang="scss" scoped>

</style>
