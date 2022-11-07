<script lang="ts" setup>
// REFERENCES
const emit = defineEmits<{}>()
const props = defineProps<{}>()
// DATA
// COMPUTED
// METHODS
// WATCH
// LIFECYCLE
</script>

<template>
  <div id="bannerLarge" class="banner">
    <div id="ipeerLogo">
      <router-link id="home" :to="{ name: 'student.events' }">
        <img :src="'../img/layout/ipeer_logo.png'" id="bannerLogoImgLeft" alt="logo"><span id="ipeerI">i</span><span
          id="ipeerText">Peer</span> <span id="bannerLogoText">3.4.10 with TeamMaker</span>
      </router-link>
    </div>
    <div id="customLogo"></div>
  </div>
</template>
