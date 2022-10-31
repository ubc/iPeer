<script lang="ts" setup>
import { reactive } from 'vue'
import PageHeading from '@/components/PageHeading.vue'
import { IconCollaboratorCircle } from '@/components/icons'
import type {IUser} from '@/types/typings'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  currentUser: IUser
}>()
// DATA
const settings = reactive({
  page: {
    title: 'iPeer Dashboard',
    description: '<b class="font-medium">Welcome to iPeer!</b> This application lets you review your team members’ contributions to group projects and assignments. Your feedback helps you reflect on teamwork and express your point-of-view. It also helps instructors understand how well groups work together and how much each group member contributes.',
    icon: {
      src: IconCollaboratorCircle,
      size: '',
      position: ''
    }
  }
})
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="dashboard">
    <PageHeading :page="settings.page" />
    <router-view :current-user="currentUser"></router-view>
  </div>
</template>
