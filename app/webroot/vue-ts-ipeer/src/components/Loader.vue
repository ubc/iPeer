<template>
  <div class="spinner-wrapper">
    <div class="spin"></div>
  </div>
</template>

<style lang="scss" scoped>
.spinner-wrapper {
  position: relative;
  @apply w-full;
  @apply flex justify-center items-center;
  @apply h-96;
}
.spin {
  display: inline-block;
  width: 50px;
  height: 50px;
  border: 5px solid rgba(140,98,159,.7);
  border-radius: 50%;
  border-top-color: #ecbb06;
  animation: spin 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
}
@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}
@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}
</style>