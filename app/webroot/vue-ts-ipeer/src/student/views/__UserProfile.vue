<script lang="ts" setup>
// https://vee-validate.logaretm.com/v4/guide/composition-api/nested-objects-and-arrays/
import { toRef, ref, reactive, onMounted, watchEffect, computed } from "vue";
import { Form, useForm } from 'vee-validate'
import * as Yup from 'yup'

import { InputField } from '@/components/fields'

import { User } from '@/types/typings'
import axios from "axios";
import {sleep} from "@/helpers";
// REFERENCES
const URL = 'http://localhost:8080/users/editProfile/7'
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  current_user: User
}>()

// DATA
const loading = ref(false)
const user = toRef<User>(props, 'current_user') || {}
const form = computed(() => {
  let tmp={};
  for(const u of Object.entries(user.value)) {
    Object.assign(tmp, {[`data[User][${u[0]}]`]: u[1]})
  }
  return tmp
})

console.log({'form': form.value})
const { handleSubmit } = useForm({
  initialValues: form.value
});

const onSubmit = handleSubmit(async values => {
  alert(JSON.stringify(values, null, 2));
  return
  const searchProfileParams = new URLSearchParams()
  // console.log({'_values': values})
  // return
  searchProfileParams.append('_method', 'PUT')
  searchProfileParams.append('action', 'Save')

  for (const pair of Object.entries(values)) {
    searchProfileParams.append(pair[0], pair[1])
  }

  // alert(searchProfileParams);

  try {
    setLoading(true)
    let response = await axios({
      method: 'POST',
      url: URL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', // application/json;
      },
      data: searchProfileParams // JSON.stringify(data)
    })
    setNotification(response.data.response.message, 'success')
  } catch (err: any) {
    await sleep(300)
    setNotification(err.response.data.message, 'error')
    return
  } finally {
    await getUserProfile()
    setLoading(false)
  }
});

function onInvalidSubmit() {
  const submitBtn = document.querySelector('.submit-btn');
  submitBtn.classList.add('invalid');
  setTimeout(() => {
    submitBtn.classList.remove('invalid');
  }, 1000);
}

// Using yup to generate a validation schema
// https://vee-validate.logaretm.com/v4/guide/validation#validation-schemas-with-yup
const schema = Yup.object().shape({
  'data[User][username]': Yup.string().required().label('Username'),
  'data[User][first_name]': Yup.string().required().label('First Name'),
  'data[User][last_name]': Yup.string().required().label('Last Name'),
  'data[User][email]': Yup.string().email().required().label('Email'),
  'data[User][student_no]': Yup.string().required().label('Student Number'),
  'data[User][old_password]': Yup.string().min(6).required(false),
  'data[User][temp_password]': Yup.string().min(6).required(false).label('New Password '),
  'data[User][confirm_password]': Yup.string().min(6).required(false)
      .oneOf([Yup.ref('data[User][temp_password]')], 'New Passwords do not match').label('Your Password '),
});
</script>

<template>
  <div id="users" class="user-profile" :class="loading ? 'bg-pink-100' : ''">
    <h1 class="page-title">Edit Profile</h1>

    <Form @submit="onSubmit" :validation-schema="schema" @invalid-submit="onInvalidSubmit">
      <h2 class="section-title">{{ 'Your Account' }}</h2>
      <h3 class="section-subtitle">{{ 'Update your iPeer profile' }}</h3>
      <p class="text-sm leading-relaxed text-slate-900 mx-4">Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>

      <div class="form-fields">
        <InputField name="data[User][username]" type="text" label="Username" placeholder="Username" />
        <InputField name="data[User][first_name]" type="text" label="First Name" placeholder="Your First Name" />
        <InputField name="data[User][last_name]" type="text" label="Last Name" placeholder="Your Last Name" />
        <InputField name="data[User][email]" type="email" label="E-mail" placeholder="Your Email Address" />
        <InputField name="data[User][student_no]" type="text" label="Student Number" placeholder="Your Student Number" />
      </div>

      <h2 class="section-title">{{ 'Your Password' }}</h2>
      <h3 class="section-subtitle">{{ 'Change your iPeer password' }}</h3>
      <p class="text-sm leading-relaxed text-slate-900 mx-4">Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>

      <div class="form-fields">
        <InputField name="data[User][old_password]" type="password" label="Old Password" placeholder="Your Old Password" />
        <InputField name="data[User][temp_password]" type="password" label="New Password" placeholder="Your New Password" />
        <InputField name="data[User][confirm_password]" type="password" label="Confirm New Password" placeholder="Confirm Your New Password" />
      </div>

      <div class="cta">
        <button type="submit" class="button submit submit-btn btn-lg">{{ isSubmitting }} Save</button>
      </div>
    </Form>
  </div>
</template>

<style lang="scss">
:root {
  --primary-color: #0071fe;
  --error-color: #f23648;
  --error-bg-color: #fddfe2;
  --success-color: #21a67a;
  --success-bg-color: #e0eee4;
}

.form {

  &-fields {
    @apply w-full;
    @apply my-6 mx-auto;

    .submit-btn {
      background: var(--primary-color);
      outline: none;
      border: none;
      color: #fff;
      font-size: 18px;
      padding: 10px 15px;
      display: block;
      width: 100%;
      border-radius: 7px;
      margin-top: 40px;
      transition: transform 0.3s ease-in-out;
      cursor: pointer;

      &.invalid {}
      &:hover   {transform: scale(1.1);}
    }
  }

}

</style>
