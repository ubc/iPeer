<script lang="ts" setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{
  routeName: string;
  displayName: string;
}>()
// DATA
// COMPUTED
const isActive = computed(() => {
  return useRoute().name === props.routeName
})
const dynamicClass = computed(() => {
  let className = 'navigation-wrapper__link'
  if (isActive.value === true || props.routeName == null) {
    className = `${className}--active`
  }
  return className
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div class="navigation-wrapper">
    <router-link custom v-slot="{ navigate, isActive }" :to="{ name: routeName }">
      <a href="javascript:" @click="navigate" :class="dynamicClass">{{ displayName }}</a>
    </router-link>
  </div>
</template>
