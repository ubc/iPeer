<script setup lang="ts">
  import { ref, watchEffect } from 'vue'
  import PageHeading from '@/components/PageHeading.vue'
  import type {IPageHeading, IUser} from '@/types/typings'
  import { isEmpty } from 'lodash'
  // REFERENCES
  const emit = defineEmits<{
    (e: 'set:message', option: object): void
    (e: 'update:profile', option: object): void
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
  function setMessage(event: object) {
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
