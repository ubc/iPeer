<script setup lang="ts">
import SectionSubtitle from '@/components/SectionSubtitle.vue'
import { IconNotebook } from '@/components/icons'
</script>

<template>
  <SectionSubtitle subtitle="Take note" :icon="{src: IconNotebook, size: '3.25rem'}">
    <p class="my-auto mx-4">Based on how your instructor has setup this review, your teammates will eventually be allowed to see the following:</p>
    <ul class="list-disc list-inside leading-6 ml-4 my-4">
      <li class="">The average of all the relative contribution ratings they received.</li>
      <li class="">The comments written about them, presented anonymously so they will not see who wrote what.</li>
    </ul>
  </SectionSubtitle>
</template>