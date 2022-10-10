<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

import Debugger from "@/components/Debugger.vue";

interface Props {
  event_id: string
  group_id: string
}
// REFERENCES
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()
const props = defineProps<Props>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="">
    <Debugger title="EvaluationIndex" :form="{}" :data="{}" />
    test
    <pre>{{ event_id }}</pre>
    <pre>{{ group_id }}</pre>
  </div>
</template>

<style lang="scss" scoped>

</style>
