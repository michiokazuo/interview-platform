<template>
  <div
    :class="{
      'tile position-relative mt-1': true,
      'col-md-9 col-12': first && participant.video,
      'col-md-3 col-12': !first && !hasScreen,
      'col-md-6 col-12': !participant.video && !hasScreen,
    }"
  >
    <audio
      autoPlay
      playsInline
      :srcObject.prop="audioSource"
    >
      <track kind="captions">
    </audio>

    <template v-if="participant.video">
      <video
        autoPlay
        muted
        playsInline
        :srcObject.prop="videoSource"
      />
      <p class="participant-name font-weight-bolder">
        {{ username }}
      </p>
    </template>

    <template v-else>
      <no-video-tile :username="username" />
    </template>

    <template v-if="participant.local">
      <controls
        :handle-video-click="handleVideoClick"
        :handle-audio-click="handleAudioClick"
        :handle-screenshare-click="handleScreenshareClick"
        :participant="participant"
        :leave-call="leaveCallCustom"
        :disable-screen-share="disableScreenShare"
        :recording="recordScreenAndAudio"
        :pause-recording="pauseRecording"
        :recorder="recorder"
        :status-record="statusRecord"
        :practice="practice"
      />
    </template>
  </div>
</template>

<script>
import Controls from './Controls.vue'
import NoVideoTile from './NoVideoTile.vue'
import interview from '@/store/api/Interview'

export default {
  name: 'VideoTile',
  components: {
    Controls,
    NoVideoTile,
  },
  props: [
    'participant',
    'handleVideoClick',
    'handleAudioClick',
    'handleScreenshareClick',
    'leaveCall',
    'disableScreenShare',
    'first',
    'hasScreen',
    'interview',
    'practice',
  ],
  data() {
    return {
      videoSource: null,
      audioSource: null,
      username: 'Guest',
      recorder: null,
      statusRecord: 'paused',
      blobRecorder: null,
      chunks: [],
    }
  },
  watch: {
    chunks(val) {
      if (val && val.length) {
        window.onbeforeunload = e => {
          e.preventDefault()
          this.leaveCallCustom()
          return 'Something went wrong! Are you sure you want to leave?'
        }
      }
    },
  },
  mounted() {
    this.username = this.participant?.user_name
    this.handleVideo(this.participant)
    this.handleAudio(this.participant)
  },
  updated() {
    this.username = this.participant?.user_name

    if (!this.videoSource) {
      this.handleVideo(this.participant)
    }
    if (!this.audioSource) {
      this.handleAudio(this.participant)
    }
  },
  methods: {
    // Add srcObject to video element
    handleVideo() {
      if (!this.participant?.video) return
      const videoTrack = this.participant?.tracks?.video?.persistentTrack
      const source = new MediaStream([videoTrack])
      this.videoSource = source
    },
    // Add srcObject to audio element
    handleAudio() {
      if (this.participant?.local) return
      if (!this.participant?.tracks?.audio?.persistentTrack) return
      const audioTrack = this.participant?.tracks?.audio?.persistentTrack
      const source = new MediaStream([audioTrack])
      this.audioSource = source
    },
    async captureMediaDevices(mediaConstraints = {
      video: {
        width: 1280,
        height: 720,
      },
      audio: {
        echoCancellation: true,
        noiseSuppression: true,
        sampleRate: 44100,
      },
    }) {
      const stream = await navigator.mediaDevices.getUserMedia(mediaConstraints)
      return stream
    },

    async captureScreen(mediaConstraints = {
      video: {
        cursor: 'always',
        resizeMode: 'crop-and-scale',
      },
    }) {
      const screenStream = await navigator.mediaDevices.getDisplayMedia(mediaConstraints)

      return screenStream
    },

    pauseRecording() {
      if (this.recorder.state === 'recording') {
        this.recorder.pause()
        this.statusRecord = 'paused'
      } else if (this.recorder.state === 'paused') {
        this.recorder.resume()
        this.statusRecord = 'recording'
      }
    },

    async recordScreenAndAudio() {
      const screenStream = await this.captureScreen()
      const audioStream = await this.captureMediaDevices({
        audio: {
          echoCancellation: true,
          noiseSuppression: true,
          sampleRate: 44100,
        },
        video: false,
      })

      const stream = new MediaStream([...screenStream.getTracks(), ...audioStream.getTracks()])

      this.recorder = new MediaRecorder(stream)

      this.recorder.ondataavailable = event => {
        if (event.data.size > 0) {
          this.chunks.push(event.data)
        }
      }

      this.recorder.onpause = () => {
        this.blobRecorder = new Blob(this.chunks, {
          type: 'video/webm',
        })

        const blobUrl = URL.createObjectURL(this.blobRecorder)
        console.log('blobUrl', blobUrl)
      }

      this.recorder.onstart = () => {
        this.statusRecord = 'recording'
      }

      this.recorder.start(200)
    },
    saveRecording() {
      if (this.chunks) {
        this.blobRecorder = new Blob(this.chunks, {
          type: 'video/webm',
        })
        const update = {
          candidate_id: this.interview.candidate.general.id,
          record: this.blobRecorder,
        }
        const formData = new FormData()
        /* eslint guard-for-in: "error" */
        for (const key in update) {
          formData.append(key, update[key])
        }
        interview.update(this.interview.id, formData)
          .then(rs => {
            window.onbeforeunload = null
            console.log(rs)
          })
          .catch(e => console.log(e))
      }
    },
    leaveCallCustom() {
      this.leaveCall()
      this.saveRecording()
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
.participant-name {
  position: absolute;
  color: #fff;
  top: 12px;
  right: 30px;
  margin: 0;
}
.tile .controls {
  opacity: 0;
  transition: opacity 0.5s ease;
}
.tile:hover .controls {
  opacity: 1;
}

@media screen and (max-width: 700px) {
  .tile {
    max-width: 100%;
    margin: 10px 20px;
  }
}
</style>
