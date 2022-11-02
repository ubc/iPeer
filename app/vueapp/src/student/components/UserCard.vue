<script setup lang="ts">
import { IconSmilingUser } from '@/components/icons'
interface Member {
  first_name: string
  last_name: string
}
const props = defineProps<{
  member: Member
}>()
</script>

<template>
  <div class="flex justify-center items-center md:space-x-4">
    <IconSmilingUser class="hidden md:block w-12 h-12" />
    <div v-if="props.member" class="flex flex-col justify-center items-center text-sm leading-4">
      <span>{{ props.member.first_name }}</span>
      <span>{{ props.member.last_name }}</span>
    </div>
  </div>
</template>