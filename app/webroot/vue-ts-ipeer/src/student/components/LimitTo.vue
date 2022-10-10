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
  <div class="limit flex flex-col md:flex-row md:space-x-16">
    <div class="label">Limit To:</div>
    <div class="space-x-4">
      <label><input name="can_edit" type="checkbox" /> Work I can still edit</label>
      <label><input name="can_view" type="checkbox" /> Peer reviews of me I can see</label>
    </div>
  </div>
</template>

<style lang="scss" scoped>

</style>
