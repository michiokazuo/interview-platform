<template>
  <div class="col-md-9 col-12">
    <video
      autoPlay
      muted
      playsInline
      :srcObject.prop="videoSource"
    />
  </div>
</template>

<script>
export default {
  name: 'ScreenshareTile',
  props: ['participant'],
  data() {
    return {
      videoSource: null,
    }
  },
  mounted() {
    this.handleVideo(this.participant)
  },
  methods: {
    // Add srcObject to video element
    handleVideo() {
      if (!this.participant?.screen) return
      const videoTrack = this.participant?.screenVideoTrack
      const source = new MediaStream([videoTrack])
      this.videoSource = source
    },
  },
}
</script>

<style scoped>
video {
  width: 100%;
  border-radius: 16px;
  box-shadow: 0 5px 25px 0 #1a2d3fa6;
}
</style>
