<script lang="ts" setup>
import { useStore } from '@/stores/main'
import type { INotification } from '@/types/typings'
interface Props { notification: INotification }
// REFERENCES
const emit  = defineEmits<{}>()
const props = defineProps<Props>()
const store = useStore()
</script>

<template>
  <Transition name="fade" mode="out-in">
    <div v-if="props.notification?.id" :class="`flash flash-${props.notification?.type} flash-dismissible fade show`">
      <div :class="`flash-wrapper flash-${props.notification?.type}`">
        <span :class="`flex-1`">{{ props.notification?.message }}</span>
        <span class="close" @click="store.delNotification(props.notification.id)">&times;</span>
      </div>
    </div>
  </Transition>
</template>
