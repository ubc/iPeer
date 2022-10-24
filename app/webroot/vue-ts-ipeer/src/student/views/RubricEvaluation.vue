<script lang="ts" setup="">
import { ref, reactive, watch, computed, onMounted } from 'vue';
import EnhancedForm from "@/student/views/EnhancedForm.vue";

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
  <EnhancedForm class="">
    <h1>RubricEvaluation</h1>
    <slot name="header"></slot>
    <slot name="main"></slot>
    <slot name="footer"></slot>
  </EnhancedForm>
</template>

<style lang="scss" scoped>

</style>
