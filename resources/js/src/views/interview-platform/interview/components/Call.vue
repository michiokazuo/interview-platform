<template>
  <main class="position-relative">
    <!-- loading is true when the call is in the "joining-meeting" meeting state -->
    <template v-if="loading">
      <div class="d-flex align-items-center justify-content-center vh-100">
        <loading />
      </div>
    </template>

    <template v-else>
      <div class="m-auto vh-100 text-center">
        <template v-if="error">
          <p class="text-danger text-center font-weight-bold pt-5 h3">
            {{ error }}
          </p>
          <button
            class="error-button btn btn-secondary h3"
            @click="leaveAndCleanUp"
          >
            Refresh
          </button>
        </template>

        <template v-if="showPermissionsError">
          <permissions-error-msg :reset="leaveAndCleanUp" />
        </template>

        <template v-else>
          <div
            class="row m-0"
          >
            <template v-if="screen || practice">
              <screenshare-tile
                :participant="screen"
                :interview="interview.id"
                :practice="practice"
                :save-practice="savePractice"
              />
            </template>

            <div
              v-if="participants"
              :class="{
                'pt-1 w-100 h-100': true,
                'row m-0': !(screen || practice),
                'col-md-3 col-12': screen || practice
              }"
            >
              <video-tile
                v-for="(p, i) in participants"
                :key="p.session_id"
                :first="!(screen || practice) && !i"
                :has-screen="screen || practice"
                :participant="p"
                :handle-video-click="handleVideoClick"
                :handle-audio-click="handleAudioClick"
                :handle-screenshare-click="handleScreenshareClick"
                :leave-call="leaveAndCleanUp"
                :disable-screen-share="screen && !screen.local"
                :interview="interview"
                :practice="practice"
              />

              <div
                v-if="count === 1"
                :class="{
                  'mt-2': true,
                  'col-md-3': !(screen || practice)
                }"
              >
                <waiting-card :url="roomUrl" />
              </div>
            </div>
          </div>

          <chat
            :send-message="sendMessage"
            :messages="messages"
          />
        </template>
      </div>
    </template>
  </main>
</template>

<script>
import daily from '@daily-co/daily-js'

import WaitingCard from './WaitingCard.vue'
import Chat from './Chat.vue'
import VideoTile from './VideoTile.vue'
import ScreenshareTile from './ScreenshareTile.vue'
import Loading from './Loading.vue'
import PermissionsErrorMsg from './PermissionsErrorMsg.vue'

export default {
  name: 'Call',
  components: {
    VideoTile,
    WaitingCard,
    Chat,
    ScreenshareTile,
    Loading,
    PermissionsErrorMsg,
  },
  props: ['leaveCall', 'name', 'roomUrl', 'interview'],
  data() {
    return {
      callObject: null,
      participants: null,
      count: 0,
      messages: [],
      error: false,
      loading: true,
      showPermissionsError: false,
      screen: null,
      practice: false,
      savePractice: false,
    }
  },
  mounted() {
    if (this.interview && !this.interview.news && this.interview.questions) {
      this.practice = true
    }
    const option = {
      url: this.roomUrl,
    }

    // Create instance of Daily call object
    const co = daily.createCallObject(option)
    // Assign in data obj for future reference
    this.callObject = co

    // Join the call with the name set in the Home.vue form
    co.join({ userName: this.name })

    // Add call and participant event handler
    // Visit https://docs.daily.co/reference/daily-js/events for more event info
    co.on('joining-meeting', this.handleJoiningMeeting)
      .on('joined-meeting', this.updateParticpants)
      .on('participant-joined', this.updateParticpants)
      .on('participant-updated', this.updateParticpants)
      .on('participant-left', this.updateParticpants)
      .on('error', this.handleError)
      // camera-error = device permissions issue
      .on('camera-error', this.handleDeviceError)
      // app-message handles receiving remote chat messages
      .on('app-message', this.updateMessages)
  },
  unmounted() {
    if (!this.callObject) return
    // Clean-up event handlers
    this.callObject
      .off('joining-meeting', this.handleJoiningMeeting)
      .off('joined-meeting', this.updateParticpants)
      .off('participant-joined', this.updateParticpants)
      .off('participant-updated', this.updateParticpants)
      .off('participant-left', this.updateParticpants)
      .off('error', this.handleError)
      .off('camera-error', this.handleDeviceError)
      .off('app-message', this.updateMessages)
  },
  methods: {
    /**
     * This is called any time a participant update registers.
     * In large calls, this should be optimized to avoid re-renders.
     * For example, track-started and track-stopped can be used
     * to register only video/audio/screen track changes.
     */
    updateParticpants(e) {
      console.log('[EVENT] ', e)
      if (!this.callObject) return

      const p = this.callObject.participants()
      this.count = Object.values(p).length
      this.participants = Object.values(p)

      // eslint-disable-next-line no-shadow
      const screen = this.participants.filter(p => p.screenVideoTrack)
      if (screen?.length && !this.screen) {
        console.log('[SCREEN]', screen)
        // eslint-disable-next-line prefer-destructuring
        this.screen = screen[0]
      } else if (!screen?.length && this.screen) {
        this.screen = null
      }
      this.loading = false
    },
    // Add chat message to local message array
    updateMessages(e) {
      console.log('[MESSAGE] ', e.data)
      this.messages.push(e?.data)
    },
    // Show local error in UI when daily-js reports an error
    handleError(e) {
      console.log('[ERROR] ', e)
      this.error = e?.errorMsg
      this.loading = false
    },
    // Temporary show loading view while joining the call
    handleJoiningMeeting() {
      this.loading = true
    },
    // Toggle local microphone in use (on/off)
    handleAudioClick() {
      const audioOn = this.callObject.localAudio()
      this.callObject.setLocalAudio(!audioOn)
    },
    // Toggle local camera in use (on/off)
    handleVideoClick() {
      const videoOn = this.callObject.localVideo()
      this.callObject.setLocalVideo(!videoOn)
    },
    // Show permissions error in UI to alert local participant
    handleDeviceError() {
      this.showPermissionsError = true
    },
    // Toggle screen share
    handleScreenshareClick() {
      if (this.screen?.local) {
        this.callObject.stopScreenShare()
        this.screen = null
      } else {
        this.callObject.startScreenShare()
      }
    },
    /**
     * Send broadcast message to all remote call participants.
     * The local participant updates their own message history
     * because they do no receive an app-message Daily event for their
     * own messages.
     */
    sendMessage(text) {
      // Attach the local participant's username to the message to be displayed in Chat.vue
      const { local } = this.callObject.participants()
      const message = { message: text, name: local?.user_name || 'Guest' }
      this.messages.push(message)
      this.callObject.sendAppMessage(message, '*')
    },
    // leave call, destroy call object, and reset local state values
    leaveAndCleanUp() {
      if (this.screen?.local) {
        this.callObject.stopScreenShare()
      }
      this.callObject.leave().then(() => {
        this.callObject.destroy()

        this.savePractice = true
        this.participantWithScreenshare = null
        this.screen = null
        this.leaveCall()
      })
    },
  },
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Ropa+Sans&display=swap");
</style>
