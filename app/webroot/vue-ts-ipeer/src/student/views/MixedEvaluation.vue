<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted } from 'vue';

// REFERENCES
const props = defineProps<{
 //...
}>()
const emit = defineEmits<{
  // (e: 'updateModelValue', option: string): void
}>()

// DATA

// COMPUTED

// METHODS

// WATCH

// LIFECYCLE

</script>

<template>
  <div class="">
    <h1>__MixedEvaluation</h1>
    <slot name="main"></slot>
  </div>
</template>

<style lang="scss" scoped>

</style>
