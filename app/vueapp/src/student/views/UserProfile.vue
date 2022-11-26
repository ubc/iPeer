<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from '@/stores/main'
import { useAuthStore } from '@/stores/auth'
import * as Yup from 'yup'
import UserForm from '@/student/views/UserForm.vue'
import PageHeading from '@/components/PageHeading.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconUser, IconKey, IconSpinner } from '@/components/icons'
import { CustomHiddenField, CustomInputField } from '@/components/fields'
import {
  validateFirstNameField,
  validateLastNameField,
  validateEmailAddressField,
  validateProfileOldPasswordInput,
  validateProfileNewPasswordInput,
  validateProfileConfirmPasswordInput,
} from '@/helpers/rules'
// REFERENCES
const route = useRoute()
const store = useStore()
const auth  = useAuthStore()
// DATA
const fields = reactive({
  account: [
    {
      label: 'Username',
      name: 'username',
      rules: Yup.string().required(),
      disabled: true,
      readonly: true,
    },
    {
      label: 'First name',
      name: 'first_name',
      rules: validateFirstNameField,
      disabled: false,
      readonly: false
    },
    {
      label: 'Last name',
      name: 'last_name',
      rules: validateLastNameField,
      disabled: false,
      readonly: false
    },
    {
      label: 'Email',
      name: 'email',
      rules: validateEmailAddressField,
      disabled: false,
      readonly: false
    },
    {
      label: 'Student number',
      name: 'student_no',
      rules: Yup.string().required(),
      disabled: true,
      readonly: true
    },
  ],
  password: [
    {
      label: 'Old password',
      type: 'password',
      name: 'old_password',
      rules: validateProfileOldPasswordInput.label('Old password')
    },{
      label: 'New password',
      type: 'password',
      name: 'temp_password',
      rules: validateProfileNewPasswordInput.label('New password')
    },{
      label: 'Confirm new password',
      type: 'password',
      name: 'confirm_password',
      rules: validateProfileConfirmPasswordInput.label('Confirm password')
    },
  ]
})
// COMPUTED
const loading       = computed(() => store.isLoading)
const error         = computed(() => store.isError)
const currentUser   = computed(() => auth.getCurrentUser)
</script>

<template>
  <PageHeading v-if="route.meta" :settings="route.meta" />

  <UserForm v-if="currentUser" :initial-state="currentUser" v-slot="{ values, errors, meta, isSubmitting }">
    <CustomInputField type="hidden" name="_method" value="PUT" />
    <div class="form-section">
      <SectionTitle title="Your Account" />
      <SectionSubtitle subtitle="Update your iPeer profile" :icon="{src: IconUser, size: '3.5rem'}">
        <p>Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>
      </SectionSubtitle>
      <div class="your-account">
        <CustomInputField
            v-for="{ name, label, ...attrs } of fields.account" :key="name"
            class="max-w-xl"
            :label="label"
            :name="`data[User][${name}]`"
            :value="values[name]"
            v-bind="attrs"
        >
          {{ field.children }}/{{ field.name }}
        </CustomInputField>
      </div>
    </div>
    <div class="form-section">
      <SectionTitle title="Your Password" />
      <SectionSubtitle subtitle="Change your iPeer password" :icon="{src: IconKey, size: '3.5rem'}">
        <p>Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>
      </SectionSubtitle>
      <div class="your-password">
        <CustomInputField
            v-for="{ name, label, ...attrs } of fields.password" :key="name"
            class="max-w-xl"
            :label="label"
            :name="`data[User][${name}]`"
            :value="values[name]"
            v-bind="attrs"
        />
      </div>
    </div>
    <div class="form-action cta">
      <button
          type="submit"
          class="button primary min-w-[100px]"
          :disabled="!meta.valid === meta.touched || isSubmitting">
        <IconSpinner class="w-4 h-4" v-if="isSubmitting" /> Save
      </button>
    </div>
  </UserForm>
</template>
<!--          class="button primary min-w-[100px] flex justify-center items-center space-x-2"-->
