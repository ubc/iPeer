<script setup lang="ts">
import {ref, toRef} from 'vue'
  import PageHeading from '@/components/PageHeading.vue'
  import {IPageHeading, IUser} from '@/types/typings'
  // REFERENCES
  const emit = defineEmits<{
    (e: 'set:message', option: object): void
    (e: 'update:profile', option: object): void
    (e: 'update:initialState', option: object): void
  }>()
  const props = defineProps<{
    currentUser: IUser
    settings?: IPageHeading
  }>()
  // DATA
  // const currentUser   = toRef(props, 'currentUser')
  const message       = ref<object|null>(null)
  // COMPUTED
  // METHODS
  function setMessage(event) {
    message.value = event
    emit('set:message', event)
  }
</script>

<template>
  <section class="users">
    <PageHeading v-if="props.settings" :settings="props.settings" />

    <router-view name="flash" v-slot="{ Component }" >
      <component :is="Component" :flash="message" />
    </router-view>

    <router-view name="default"
        :current-user="props.currentUser"
        @set:message="setMessage"
        @update:profile="$emit('update:profile', $event)"
    ></router-view>
  </section>
</template>
