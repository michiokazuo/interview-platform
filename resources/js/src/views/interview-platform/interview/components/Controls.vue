<template>
  <div class="controls">
    <div class="devices">
      <button @click="handleAudioClick">
        <template v-if="participant.audio">
          <img
            class="icon"
            :src="micOn"
            alt=""
          >
        </template>
        <template v-else>
          <img
            class="icon"
            :src="micOff"
            alt=""
          >
        </template>
      </button>

      <button @click="handleVideoClick">
        <template v-if="participant.video">
          <img
            class="icon"
            :src="videoOn"
            alt=""
          >
        </template>
        <template v-else>
          <img
            class="icon"
            :src="videoOff"
            alt=""
          >
        </template>
      </button>

      <template v-if="supportsScreenshare && !practice">
        <button
          :disabled="disableScreenShare"
          @click="handleScreenshareClick"
        >
          <img
            class="icon"
            :src="screenShare"
            alt=""
          >
        </button>
      </template>

      <template v-if="supportsScreenshare">
        <button
          v-if="recorder"
          @click="pauseRecording"
        >
          <feather-icon
            v-if="statusRecord == 'recording'"
            icon="TargetIcon"
            size="20"
            class="mr-50 text-danger"
          />
          <feather-icon
            v-else
            icon="TargetIcon"
            size="20"
            class="mr-50 text-white"
          />
        </button>
        <button
          v-else
          @click="recording"
        >
          <feather-icon
            icon="TargetIcon"
            size="20"
            class="mr-50 text-white"
          />
        </button>
      </template>

    </div>

    <button
      class="leave"
      @click="leaveCall"
    >
      <img
        class="icon"
        :src="leave"
        alt=""
      >
    </button>
  </div>
</template>

<script>
import daily from '@daily-co/daily-js'

export default {
  name: 'Controls',
  props: [
    'participant',
    'handleVideoClick',
    'handleAudioClick',
    'handleScreenshareClick',
    'leaveCall',
    'disableScreenShare',
    'recording',
    'pauseRecording',
    'recorder',
    'statusRecord',
    'practice',
  ],
  data() {
    return {
      leave: require('@/assets/images/meeting/leave_call.svg'),
      micOn: require('@/assets/images/meeting/mic_on.svg'),
      micOff: require('@/assets/images/meeting/mic_off.svg'),
      screenShare: require('@/assets/images/meeting/screenshare.svg'),
      videoOn: require('@/assets/images/meeting/vid_on.svg'),
      videoOff: require('@/assets/images/meeting/vid_off.svg'),
      supportsScreenshare: false,
    }
  },
  watch: {
    recorder: {
      handler() {
        console.log('recorder', this.recorder)
      },
    },
  },
  mounted() {
    // Only show the screen share button if the browser supports it
    this.supportsScreenshare = daily.supportedBrowser().supportsScreenShare
  },
}
</script>

<style scoped>
.controls {
  position: absolute;
  bottom: 12px;
  left: 25px;
  justify-content: space-between;
  display: flex;
  width: calc(100% - 50px);
}

.devices {
  border-radius: 12px;
  background-color: #121a24;
  opacity: 0.85;
  padding: 14px 10px 15px;
}
button {
  background-color: transparent;
  border: none;
  cursor: pointer;
}
button:not(.leave) {
  margin: 0 4px;
  width: 36px;
  height: 26px;
}
button.leave {
  background-color: #f63135;
  opacity: 0.85;
  padding: 14px 16px 15px;
  border-radius: 12px;
}
button:disabled {
  cursor: not-allowed;
  opacity: 0.4;
}
.icon {
  height: 24px;
}
</style>
