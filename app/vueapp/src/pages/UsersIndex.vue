<script setup lang="ts">
  import { computed } from 'vue'
  import { useStore } from '@/stores/main'
  import PageHeading from '@/components/PageHeading.vue'
  import type { IPageHeading } from '@/types/typings'
  interface Props {
    settings?: IPageHeading
  }
  // REFERENCES
  const emit  = defineEmits<{}>()
  const props = defineProps<Props>()
  const store = useStore()
  // DATA
  // COMPUTED
  const notification = computed(() => store.getNotification)
  // METHODS
</script>

<template>
  <section class="users">
    <PageHeading v-if="props.settings" :settings="props.settings" />
    <router-view name="flash" v-slot="{ Component }" >
      <component :is="Component" :notification="notification" />
    </router-view>
    <router-view name="default"></router-view>
  </section>
</template>
