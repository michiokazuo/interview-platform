<template>
  <b-card
    title="Welcome for others!"
    sub-title="Invite someone by sharing this link:"
  >
    <b-card-text class="text-white">
      {{ url }}
    </b-card-text>
  </b-card>
</template>

<script>
import {
  BCard, BCardText,
} from 'bootstrap-vue'

export default {
  name: 'WaitingCard',
  components: {
    BCard, BCardText,
  },
  props: ['url'],
}
</script>

<style scoped>
.card {
  background-color: #2b3f56!important;
  box-shadow: 0 5px 25px 0 #1a2d3fa6;
  border-radius: 16px;
}
.card-title {
  color: #1bebb9;
}
.card-subtitle {
  color: rgb(63, 121, 137)!important;
}
</style>
