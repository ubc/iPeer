<script lang="ts" setup>
// https://vee-validate.logaretm.com/v4/guide/composition-api/nested-objects-and-arrays/
import {toRef, ref, reactive, onMounted, watchEffect, computed,} from "vue";
import axios from 'axios'
import swal from 'sweetalert'
import { sleep } from '@/helpers'
import {configure, useForm, useField, Field, ErrorMessage} from 'vee-validate'

import { InputField } from '@/components/fields'


import { User } from '@/types/typings'
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
const schema = {}
/**
  const schema = object({
  email: string().required().email().test(
      "email-is-taken",
      "Email is already taken",
      async (value) => !(await existingEmail(value))
  ).label('Email'),
  password: string().required().min(4).label('Your Password'),
  confirmed: string()
      .required().oneOf([yupref('password')], 'Password do not match')
      .label('Your Confirmation Password')
})*/
// const form = useForm({
//   initialValues: toRef(props, 'current_user')
// })
// COMPUTED



const { value: username } = useField('data[User][username]');
const { value: first_name } = useField('data[User][first_name]');
const { value: last_name } = useField('data[User][last_name]');
const { value: email } = useField('data[User][email]');
const { value: title } = useField('data[User][title]');
const { value: student_no } = useField('data[User][student_no]');
const { value: old_password } = useField('data[User][old_password]');
const { value: temp_password } = useField('data[User][temp_password]');
const { value: confirm_password } = useField('data[User][confirm_password]');

const form = computed(() => {
  let tmp={};
  for(const u of Object.entries(user.value)) {
    Object.assign(tmp, {[`data[User][${u[0]}]`]: u[1]})
  }
  return tmp
})
const { handleSubmit } = useForm({
  initialValues: form
});

// console.log({'form': form.value})

// METHODS
// async function existingEmail(value) {
//   alert('checking if email is taken.')
// }
function setLoading(value) {
  loading.value = value
}
function setNotification(message, type) {
  // swal({title: 'Notification', text: message, icon: type})
  swal({text: message, icon: type})
}
async function getUserProfile() {
  try {
    setLoading(true)
    let response = await axios({
      method: 'GET',
      url: URL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    Object.assign(user, response.data)
  } catch (err: any) {
    setNotification(err.response.message, 'error')
  } finally {
    await sleep(1000)
    setLoading(false)
  }
}


const onSubmit = handleSubmit(async values => {
  // alert(JSON.stringify(values, null, 2));

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








async function onSubmit2(values) {
  console.log(JSON.stringify(values, null, 2))
  return

  /** TBD
  let form_data = new FormData()
  form_data.set('values', {
    'username': values.username,
    'first_name': values.first_name,
    'last_name': values.last_name,
    'email': values.email,
    'title': values.title,
    'student_no': values.student_no,
  })
  const searchProfileParams = new URLSearchParams()
  for (const pair of form_data) {
    searchProfileParams.append(pair[0], pair[1])
  }
  let response = await axios({
    method: 'POST',
    url: URL,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', // application/json;
    },
    data: searchProfileParams
  })
  // for(const value of Object.entries(values)) {
  //   formData.append(value[0], value[1])
  // }
  console.log(JSON.stringify(response, null, 2))
  return
  */
  // const formData = new FormData(values)
  // formData.append('_method', 'PUT')
  // formData.append('action', 'Save')
  // console.log(JSON.stringify(formData, null, 2))
  // return

  // const searchProfileParams = new URLSearchParams()
  // for (const pair of values) {
  //   searchProfileParams.append(pair[0], pair[1])
  // }
  // console.log(JSON.stringify(searchProfileParams, null, 2))

  // await setUserProfile(form)
  // going live
  // await setUserProfile(searchProfileParams)


  // console.log(searchProfileParams)

  // console.log(JSON.stringify(JSON.parse(values), null, 2))
  // return

  /** TBD
  try {
    setLoading(true)
    let response = await axios({
      method: 'POST',
      url: URL,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', // application/json;
      },
      data: data // JSON.stringify(data)
    })
    setNotification(response.data.message, 'success')
  } catch (err: any) {
    await sleep(500)
    setNotification(err.data.message, 'error')
  } finally {
    await getUserProfile()
    setLoading(false)
  }
  */
}
// WATCH
function validateText(value) {
  return 'Text Not Valid'
}
function validateEmail(value) {
  return 'Email Not Valid'
}

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
  <div id="users" class="user-profile" :class="loading ? 'bg-pink-100' : ''">
    <h1 class="page-title">Edit Profile</h1>
    <!--<keep-alive>-->
    <!--<form @submit.prevent="onSubmit">-->
    <Form @submit="onSubmit">
      <div style="display:none;"><input type="hidden" name="_method" value="PUT"></div>
      <div class="form">
        <h2 class="section-title">{{ 'Your Account' }}</h2>
        <h3 class="section-subtitle">{{ 'Update your iPeer profile' }}</h3>
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>

        <InputField
            name="data[User][username]"
            type="text"
            label="Username"
            placeholder="Your Username"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][first_name]"
            type="text"
            label="First Name"
            placeholder="Your First Name"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][last_name]"
            type="text"
            label="Last Name"
            placeholder="Your Last Name"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][email]"
            type="text"
            label="Email"
            placeholder="Your Email"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][student_no]"
            type="text"
            label="Student Number"
            placeholder="Your Student Number"
            success-message="Nice to meet you!"
        />


        <!--<div class="form-field">
          <label class="form-label ">Username</label>
          <div class="form-input">
            <input class="form-control" type="text" name="data[User][username]" v-model="username" :rules="validateText" />
            <Field type="text" :name="`data[User][username]`" :rules="validateText" readonly="readonly" />
            <ErrorMessage class="flex-1 text-xs text-red-500" name="data[User][username]" />
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label ">First Name</label>
          <div class="form-input">
            <input class="form-control" type="text" name="data[User][first_name]" v-model="first_name" :rules="validateText" />
            <Field :name="`data[User][first_name]`" type="text" />
            <div class="flex-1 text-xs text-red-500" name="data[User][first_name]" />
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label ">Last Name</label>
          <div class="form-input">
            <input class="form-control" type="text" name="data[User][last_name]" v-model="last_name" :rules="validateText" />
            <Field :name="`data[User][last_name]`" type="text" />
            <div class="flex-1 text-xs text-red-500" name="data[User][last_name]" />
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label ">Email</label>
          <div class="form-input">
            <input class="form-control" type="email" name="data[User][email]" v-model="email" :rules="validateEmail" />
            <Field :name="`data[User][email]`" type="text" :rules="validateEmail" />
            <div class="flex-1 text-xs text-red-500" name="data[User][email]" />
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label ">Student Number</label>
          <div class="form-input">
            <input class="form-control" type="text" name="data[User][student_no]" v-model="student_no" :rules="validateText" />
            <Field :name="`data[User][student_no]`" type="text" readonly="readonly" />
            <div class="flex-1 text-xs text-red-500" name="data[User][student_no]" />
          </div>
        </div>
      </div>-->
      </div>

      <div class="form">
        <h2 class="section-title">{{ 'Your Password' }}</h2>
        <h3 class="section-subtitle">{{ 'Change your iPeer password' }}</h3>
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>

        <InputField
            name="data[User][old_password]"
            type="password"
            label="Old Password"
            placeholder="Your Old Password"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][temp_password]"
            type="password"
            label="New Password"
            placeholder="Your New Password"
            success-message="Nice to meet you!"
        />

        <InputField
            name="data[User][confirm_password]"
            type="password"
            label="Confirm New Password"
            placeholder="Your Confirm New Password"
            success-message="Nice to meet you!"
        />

        <!--<div class="form-field">
          <label class="form-label">Old Password</label>
          <div class="form-input">
            <input class="form-control" type="password" name="data[User][old_password]" v-model="old_password" />
            <div class=""></div>
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label">New Password</label>
          <div class="form-input">
            <input class="form-control" type="password" name="data[User][temp_password]" v-model="temp_password" />
            <div class=""></div>
          </div>
        </div>-->

        <!--<div class="form-field">
          <label class="form-label">Confirm New Password</label>
          <div class="form-input">
            <input class="form-control" type="password" name="data[User][confirm_password]" v-model="confirm_password" />
            <div class=""></div>
          </div>
        </div>-->
        
      </div>

      <div class="cta">
        <button type="submit" class="button submit btn-lg">{{ isSubmitting }} Save</button>
      </div>

    </Form>
  <!--</keep-alive>-->
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


.user-profile {

  .text-input {
    // position: relative;
    // margin-bottom: calc(1em * 1.5);
    // width: 350px;

    @apply w-full sm:w-[250px] md:w-[350px];
    @apply relative;
    @apply mb-6;

    label {
      // display: block;
      // margin-bottom: 4px;
      // width: 100%;
      @apply block;
      @apply text-sm;
      @apply w-full sm:w-[400px] md:w-[200px];
      @apply mb-0 ml-2;
    }

    input {
      border-radius: 5px;
      border: 2px solid transparent;
      padding: 0.75rem 0.5rem;
      outline: none;
      background-color: #f2f5f7;
      width: 100%;
      transition: border-color 0.3s ease-in-out, color 0.3s ease-in-out,
      background-color 0.3s ease-in-out;

      &:focus {
        border-color: var(--primary-color);
      }
    }

    .help-message {
      //position: absolute;
      //bottom: calc(-1.5 * 1em);
      //left: 0;
      //margin: 0;
      //font-size: 14px;

      @apply absolute;
      @apply text-xs;
      @apply left-0;
      @apply -bottom-3;
      @apply m-0;
      @apply ml-2;
    }

    &.has-error input {background-color: var(--error-bg-color);color: var(--error-color);}
    &.has-error input:focus {border-color: var(--error-color);}
    &.has-error .help-message {color: var(--error-color);}
    &.success input {background-color: var(--success-bg-color);color: var(--success-color);}
    &.success input:focus {border-color: var(--success-color);}
    &.success .help-message {color: var(--success-color);}
  }


}





/*
.user-profile {
  @apply my-8;
}
.form {
  @apply flex flex-col space-y-2;
  @apply mt-8 mb-4;

  &-label {
    @apply w-[200px];
    @apply text-right;
  }
  &-control {
    @apply w-[320px];
  }
  &-field {
    @apply flex items-center space-x-4;

  }
  &-input {
    @apply flex flex-col justify-start space-x-2;
  }
}
*/
</style>