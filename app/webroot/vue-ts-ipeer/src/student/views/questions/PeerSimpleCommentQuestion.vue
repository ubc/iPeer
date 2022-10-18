<script lang="ts" setup>
import { ref, toRef, reactive, watch, computed, onMounted } from 'vue'

import UserCard from "@/student/components/UserCard.vue";
import TextElement from "@/components/fields/experimental/TextElement.vue";

// REFERENCES
const emit = defineEmits<{
  // (e: 'update:modelValue', option: string): void
  // (e: 'update:comments', option: string): void
}>()
const props = defineProps<{
  members: object[]
  form: object
  name: string
  label?: string
  question?: string
  description?: string
  placeholder?: string
  disabled?: boolean | false
}>();

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="datatable" id="PeerSimpleRangeQuestion">
    <div v-if="props.question" class="question">{{ props.question }}</div>
    <div v-if="props.description" class="description text-sm mx-4 mb-2">{{ props.description }}</div>
    <table class="standardtable">
      <thead>
      <tr>
        <th><div class="">Peer</div></th>
        <th><div class="">Comments</div></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(member, memberIdx) of props.members" :key="member.id">
        <td style="width: 20%"><UserCard :member="member" /></td>
        <td style="width: 80%">
          <TextElement
              :label="label"
              :value="props.form[name][memberIdx]"
              :name="`${name}[${memberIdx}]`"
              :placeholder="placeholder"
              :disabled="props.disabled"
          />
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
