<script setup lang="ts">

const props = defineProps<{
  subtitle: string, icon: object
}>()

</script>

<template>
  <div class="section-subtitle mt-2">
    <component v-if="icon?.src" :is="icon.src" :style="{'width': icon?.size, 'margin': icon?.margin}" :class="icon?.src"></component>
    <h3 class="subtitle" v-if="subtitle">{{ subtitle }}</h3>
  </div>
  <slot></slot>
</template>