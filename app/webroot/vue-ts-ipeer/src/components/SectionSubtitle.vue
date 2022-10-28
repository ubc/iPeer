<script setup lang="ts">

const props = defineProps<{
  subtitle: string,
  icon?: object
}>()

</script>

<template>
  <div class="section-subtitle mt-2">
    <component v-if="props.icon?.src" :is="props.icon.src" :style="{'width': props.icon?.size, 'margin': props.icon?.margin}" :class="props.icon?.src"></component>
    <h3 class="subtitle" v-if="props.subtitle">{{ props.subtitle }}</h3>
  </div>
  <slot></slot>
</template>