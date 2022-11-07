<script lang="ts" setup>
import {ref, watch, watchEffect} from 'vue'
import type { Ref } from 'vue'
import { sleep } from '@/helpers'
export interface IToast {
  id: string|number
  type: string
  status: number
  message: string
}
// REFERENCES
const emit = defineEmits<{
  (e: 'update:messages', value: string): void
}>()
const props = defineProps<{
  action: object
  toast: IToast
}>()
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

function pauseStatusBar() {
  clearInterval(intervalId.value)
}
async function closeMessage() {
  show.value = false
  await sleep(400)
  emit('update:messages', props.toast?.id)
}
// WATCH
watch(width, () => {
  startStatusBar()
})
watchEffect((onInvalidate) => {
  startStatusBar()
})
// LIFECYCLE
</script>

<template>
  <div
      v-if="props.toast"
      @mouseover="pauseStatusBar"
      @mouseleave="startStatusBar"
      :class="`toast toast-${props.toast.type} toast-dismissible fade ${show?'show':''}`"
  >
    <div :class="`toast-wrapper flex justify-between items-center`">
      <span :class="`flex-1`">{{ props.toast?.text }}</span>
      <span class="close" @click="closeMessage">&times;</span>
    </div>
    <div :style="{ width: width+'%'}" class="status-bar"></div>
  </div>
</template>
