<script lang="ts" setup>
import { ref, reactive, watch, computed, onMounted, watchEffect } from 'vue'
import { isEmpty, merge } from 'lodash'
import { localDateOptions } from '@/helpers'
interface IMessage {
  title: string
  message: string
  status: string
  code: number
  type: string
}
interface IProps {
  status: string
  data?: IMessage
  releaseStatus?: string
  releaseDate?: string|Date
  releaseInfo?: IMessage
}
// REFERENCES
const props = defineProps<IProps>()
// DATA
// COMPUTED
const state = computed(() => {
  if(!isEmpty(props.data)) {
    return props.data
  } else if(props.status === 'online') {
    return {
      title: 'Content Not Yet Available.',
      message: 'Check back on the release date',
      direction: null,
    }
  } else if(props.status === 'offline') {
    return {
      title: 'Operation Could Not Be Completed.',
      message: 'The following content is not available offline.',
      direction: null,
    }
  }
})
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div v-if="state" class="flex flex-col justify-center items-center py-16 bg-gray-50 rounded shadow">
    <h3 class="text-slate-300 text-3xl mb-2">{{ state.title }}</h3>
    <div class="text-slate-500 font-light text-xl tracking-wider">{{ state.message }}</div>
    <div class="text-slate-400 text-base font-light mt-4 tracking-wide">

      <template v-if="props.status === 'online'">
        <template v-if="state.direction">{{ state.direction }}</template>
        <template v-if="props.date">{{ new Date().toLocaleDateString('en-CA', localDateOptions) }}</template>
      </template>


      <template v-if="props.status === 'offline'">
        <p>Please click <a class="font-medium px-1" href="/login">here</a>to login or refresh the browser to be redirected to the login screen.</p>
      </template>
      <template v-if="props.status === 'online'">

      </template>
      <template v-else>
        <p>Please click <router-link class="font-medium px-1" :to="{ name: 'student.events' }">here</router-link> to navigate back to Dashboard.</p>
      </template>

    </div>
  </div>
</template>
