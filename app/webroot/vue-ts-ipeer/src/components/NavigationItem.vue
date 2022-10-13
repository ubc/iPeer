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
      <div @click="navigate" :class="dynamicClass">{{ displayName }}</div>
    </router-link>
  </div>
</template>

<style lang="scss">
.navigation-wrapper {
  @apply w-auto;

  &__link {
    @apply cursor-pointer;
  }
  &__link,
  &__link--active {
    @apply flex justify-center items-center h-[2rem] px-2;
  }
  &__link--active {
    border-bottom: 2px solid goldenrod;
    @apply cursor-default;
  }
}
</style>
