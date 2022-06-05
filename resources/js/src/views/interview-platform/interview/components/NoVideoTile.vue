<template>
  <div class="no-video h-100 w-100 d-flex justify-content-center align-items-center mt-1 pt-3 pb-3">
    <p class="text-white font-weight-bold">
      {{ username }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'NoVideoTile',
  props: ['username'],
}
</script>

<style>
.no-video {
  border-radius: 16px;
  background-color: #434343;
  box-shadow: 0 5px 25px 0 #1a2d3fa6;
}
</style>
