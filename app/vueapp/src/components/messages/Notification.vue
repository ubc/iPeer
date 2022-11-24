<script lang="ts" setup>
import { ref, watch, watchEffect } from 'vue'
import { sleep } from '@/helpers'
import type { Ref } from 'vue'
import type { INotification } from '@/types/typings'
interface Props {
  statusBar: boolean
  notification: INotification
}
// REFERENCES
const emit  = defineEmits<{ (e: 'close:notification', option: string): void }>()
const props = defineProps<Props>()
// DATA
const width: Ref<number> = ref(0)
const show: Ref<boolean> = ref(true)
const hide: Ref<boolean> = ref(false)
const intervalId: Ref<number> = ref(0)
// COMPUTED
// METHODS
const setWidth = (val: number) => width.value = width.value + val
const setHide = (val: boolean) => hide.value = val
const setShow = (val: boolean) => show.value = val
const setIntervalId = (val: number) => intervalId.value = val

async function startStatusBar(): void {
  if(props.statusBar) {
    const timeout: number = window.setInterval(  () => {
      if (width.value < 100) {
        setWidth(0.5)
      } else {
        sleep(400)
        closeMessage()
      }
      window.clearInterval(timeout)
      setWidth(0)
    }, 20)
    setIntervalId(timeout)
  }
  else {
    await sleep(5000)
    await closeMessage()
  }
}

function pauseStatusBar() {
  clearInterval(intervalId.value)
}
async function closeMessage() {
  show.value = false
  await sleep(400)
  emit('close:notification', props.notification?.id)
}
// WATCH
watch(width, () => startStatusBar())
watchEffect(startStatusBar())
// LIFECYCLE
</script>

<template>
  <div v-if="props.notification" @mouseover="pauseStatusBar" @mouseleave="startStatusBar"
      :class="`notification notification-${props.notification.type} notification-dismissible fade ${show?'show':''}`">
    <div :class="`notification-wrapper flex justify-between items-center`">
      <span :class="`flex-1`">{{ props.notification?.message }}</span>
      <span class="close" @click="closeMessage">&times;</span>
    </div>
    <div v-if="props.statusBar" :style="{ width: width+'%'}" class="status-bar"></div>
  </div>
</template>
