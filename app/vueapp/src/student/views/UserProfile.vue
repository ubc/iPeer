<script setup lang="ts">
import { ref, toRefs } from 'vue'
import UserForm from '@/student/views/UserForm.vue'
import PageHeading from '@/components/PageHeading.vue'
import SectionTitle from '@/components/SectionTitle.vue'
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconUser, IconKey, IconSpinner } from '@/components/icons'
import { CustomHiddenField, CustomInputField } from '@/components/fields'
import {
  validateProfileTextInput,
  validateProfileEmailInput,
  validateProfileOldPasswordInput,
  validateProfileNewPasswordInput,
  validateProfileConfirmPasswordInput,
} from '@/helpers/rules'

import type { IUser, IPageHeading } from '@/types/typings'
// REFERENCES
const emit = defineEmits<{
  (e: 'set:message', option: object): void
  (e: 'update:profile', option: object): void
}>()
const props = defineProps<{
  currentUser: IUser
  settings?: IPageHeading
}>()
// DATA
const user          = toRefs(props, 'currentUser')
const error         = ref([])
const loading       = ref(false)
</script>

<template>
  <PageHeading v-if="props.settings" :settings="props.settings" />

  <UserForm v-if="props.currentUser"
      :initial-state="props.currentUser"
      v-slot="{ values, errors, meta, isSubmitting, message }"
      @set:message="$emit('set:message', $event)"
      @update:profile="$emit('update:profile', $event)"
  >
    <CustomInputField type="hidden" name="_method" value="PUT" />
    <div class="form-section">
      <SectionTitle title="Your Account" class="mt-8" />
      <SectionSubtitle subtitle="Update your iPeer profile" :icon="{src: IconUser, size: '3.5rem'}">
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Your first name and last name are shown to peers when they review you but not when they read reviews from you. No one other than your instructor and teaching assistants will know what you share about peers in iPeer.</p>
      </SectionSubtitle>
      <div class="your-account mt-6 mb-4 md:space-y-5">
        <CustomInputField
            class="profile"
            label="Username"
            name="data[User][username]"
            :value="values?.username"
            :rules="validateProfileTextInput.label('Username')"
            :disabled="true"
            readonly="readonly"
        />
        <CustomInputField
            class="profile"
            label="First name"
            name="data[User][first_name]"
            :value="values?.first_name"
            :rules="validateProfileTextInput.label('First name')"
        />
        <CustomInputField
            class="profile"
            label="Last name"
            name="data[User][last_name]"
            :value="values?.last_name"
            :rules="validateProfileTextInput.label('Last name')"
        />
        <CustomInputField
            class="profile"
            label="Email"
            name="data[User][email]"
            :value="values?.email"
            :rules="validateProfileEmailInput.label('Email')"
        />
        <CustomInputField
            class="profile"
            label="Student number"
            name="data[User][student_no]"
            :value="values?.student_no"
            :rules="validateProfileTextInput.label('Student number')"
            :disabled="true"
            readonly="readonly"
        />
      </div>
    </div>
    <div class="form-section">
      <SectionTitle title="Your Password" />
      <SectionSubtitle subtitle="Change your iPeer password" :icon="{src: IconKey, size: '3.5rem'}">
        <p class="text-sm leading-relaxed text-slate-900 mx-4">Enter this information if you'd like to change your password. You can save updates to your account without changing your password.</p>
      </SectionSubtitle>
      <div class="your-password mt-6 mb-4 md:space-y-5">
        <CustomInputField
            type="password"
            class="profile"
            label="Old password"
            name="data[User][old_password]"
            :value="values?.old_password"
            :rules="validateProfileOldPasswordInput.label('Old password')"
            :autocomplete="false"
        />
        <CustomInputField
            class="profile"
            type="password"
            name="data[User][temp_password]"
            label="New password"
            :value="values?.temp_password"
            :rules="validateProfileNewPasswordInput.label('New password')"
            :autocomplete="false"
        />
        <CustomInputField
            class="profile"
            type="password"
            name="data[User][confirm_password]"
            label="Confirm new password"
            :value="values?.confirm_password"
            :rules="validateProfileConfirmPasswordInput.label('Confirm password')"
            :autocomplete="false"
        />
      </div>
    </div>
    <div class="form-action cta">
      <button
          type="submit"
          class="button submit btn-lg flex items-center space-x-2"
          :disabled="!meta.valid === meta.touched || isSubmitting">
        <IconSpinner class="w-4 h-4" v-if="isSubmitting" /> Save
      </button>
    </div>
  </UserForm>
</template>
