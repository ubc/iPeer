<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<{
  action: object
  notification: object
}>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>
<div class="notification">

</div>
<template>
  <div class="py-1 px-2 m-2 bg-white h-auto w-auto" v-if="Object.keys(props.notification).length>0">
    <div class="">
      <!--<component :is="props.action.default_icon" class="w-16 h-16" />-->
      <div class="">
        <div class="title text-sm text-slate-900">{{ props.action.default_title }}</div>
        <div class="notification text-sm text-slate-700">{{ props.notification?.message }}</div>
      </div>
    </div>
    <div class="status-bar"></div>
  </div>
</template>
