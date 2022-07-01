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
                :user-on="userOn"
                :result="result"
                :count="count"
                @update-result="updateResult"
              />
            </template>

            <div
              v-if="participants"
              :class="{
                'w-100 h-100': true,
                'row m-0': !(screen || practice),
                'col-md-3 col-12 vh-100 overflow-auto': screen || practice
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
                :show-info="count > 1 && i + 1 === participants.length"
                :disable-screen-share="screen && !screen.local"
                :interview="interview"
                :practice="practice"
                :user="userOn"
                :save-practice="savePractice"
                :result="result"
                :count="count"
                @update-result="updateResult"
              />
              <div
                v-if="count === 1"
                :class="{
                  'pt-1': true,
                  'col-md-3 vh-100 overflow-auto': !(screen || practice)
                }"
              >
                <waiting-card :url="roomUrl" />
                <b-card
                  v-if="interview"
                  title="General"
                  class="text-justify mt-2"
                >
                  <b-col
                    v-if="interview.project"
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      Project
                    </h5>
                    <b-card-text>
                      <b-link
                        class="project-title-truncate"
                      >
                        {{ interview.project.title }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                  <b-col
                    v-if="interview.news"
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      News
                    </h5>
                    <b-card-text>
                      <b-link
                        :to="{ name: 'pages-news-detail', params: { id: interview.news.id } }"
                        class="project-title-truncate"
                        target="_blank"
                      >
                        {{ interview.news.title }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                  <b-col
                    v-if="interview.process"
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      Process
                    </h5>
                    <b-card-text>
                      <b-link
                        class="project-title-truncate"
                      >
                        {{ interview.process.id }} - {{ interview.process.title }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                  <b-col
                    v-if="interview.company"
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      Company
                    </h5>
                    <b-card-text>
                      <b-link
                        :to="{ name: 'pages-company-news-list', params: { id: interview.company.id } }"
                        class="project-title-truncate"
                        target="_blank"
                      >
                        {{ interview.company.general.name }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                  <b-col
                    v-if="interview.candidate"
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      Candidate or Practice
                    </h5>
                    <b-card-text>
                      <b-link
                        class="project-title-truncate"
                      >
                        {{ interview.candidate.general.name }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                  <b-col
                    class="mb-75 pl-0"
                    cols="12"
                  >
                    <h5 class="text-capitalize ">
                      Date
                    </h5>
                    <b-card-text>
                      <b-link
                        class="project-title-truncate"
                      >
                        {{ new Date().toLocaleString() }}
                      </b-link>
                    </b-card-text>
                  </b-col>
                </b-card>

                <b-card
                  v-if="userOn && userOn.company_id && interview && interview.interview_question"
                  title="Interview questions"
                  class="text-justify mt-2"
                >
                  <app-collapse>
                    <app-collapse-item
                      v-for="(question, index) in interview.interview_question_data"
                      :key="index"
                      :title="(index + 1) + '. ' + question.content"
                    >
                      {{ question.answers && question.answers.length ? question.answers[0].content : 'Not set' }}
                    </app-collapse-item>
                  </app-collapse>
                </b-card>
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
import {
  BCol,
  BCard,
  BCardText,
  BLink,
} from 'bootstrap-vue'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'

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
    BCol,
    BCard,
    BCardText,
    BLink,
    AppCollapse,
    AppCollapseItem,

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
      userOn: null,
      result: {
        is_success: null,
        review: null,
      },
    }
  },
  mounted() {
    if (this.interview) {
      this.result = {
        review: this.interview?.result?.company?.review,
        is_success: this.interview?.is_success,
      }
    }
    this.userOn = JSON.parse(localStorage.getItem('userData'))
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
    updateResult($event) {
      this.result = { ...$event }
    },
  },
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Ropa+Sans&display=swap");
</style>
