<script lang="ts" setup>
import {computed, onBeforeMount, onMounted} from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from '@/stores/main'
import { useAuthStore } from '@/stores/auth'

interface Props {
  title?: string
}
// REFERENCES
const emit    = defineEmits<{}>()
const props   = defineProps<Props>()
const router  = useRouter()
const store   = useStore()
const auth    = useAuthStore()
// DATA
// COMPUTED
const loading       = computed(() => store.isLoading)
const initialized   = computed(() => store.isInitialized)
const currentUser   = computed(() => auth.getCurrentUser)
// METHODS
// WATCH
// LIFECYCLE
// onBeforeMount(async () => await store.initialize())
onMounted(async () => await store.initialize())

if (import.meta.env.MODE !== 'production') {
  console.log('[mode]', import.meta.env.MODE, import.meta.env.VITE_DEV_URL)
}
</script>

<script lang="ts">
export default{inheritAttrs:false}
</script>
<template>
  <router-view name="banner" v-slot="{ Component }" >
    <component :is="Component" />
  </router-view>

  <router-view name="navigation" v-slot="{ Component, pageTitle }" >
    <component :is="Component" :page-title="pageTitle" />
  </router-view>

  <router-view class="content" v-slot="{ Component, pageTitle }">
    <transition name="fade" mode="out-in">
      <component :is="Component" :page-title="title" />
    </transition>
  </router-view>

  <router-view name="toast" v-slot="{ Component }">
    <component :is="Component" />
  </router-view>

  <router-view name="footer" v-slot="{ Component }">
    <component :is="Component"/>
  </router-view>
</template>
