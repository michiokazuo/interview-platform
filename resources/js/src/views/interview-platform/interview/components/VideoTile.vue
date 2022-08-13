<template>
  <div
    :class="{
      'tile pt-1 overflow-auto': true,
      'col-md-9 col-12 vh-100': first && participant.video,
      'col-md-3 col-12 vh-100': !first && !hasScreen,
      'col-md-6 col-12': first && !participant.video && !hasScreen,
      'col-md-3 col-12': !first && !participant.video && !hasScreen,
    }"
  >
    <div class="tile position-relative">
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

    <b-card
      v-if="interview && showInfo"
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
      v-if="user && user.company_id && interview && showInfo && interview.interview_question"
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

    <b-card
      v-if="((first && participant.video) || (first && !participant.video && !hasScreen)) && user && user.company_id"
      title="Review"
      class="mt-2 text-justify"
    >
      <b-card-body
        class="pt-0"
      >
        <b-card-text>
          <!-- form -->
          <b-form
            class="mt-2"
          >
            <b-row>
              <b-col
                md="4"
                cols="6"
              >
                <b-form-checkbox
                  class="d-flex align-items-center custom-control-success"
                  :checked="showSelected(true)"
                  @change="changeSelected(true)"
                >
                  <strong class="text-success">Success</strong>
                </b-form-checkbox>
              </b-col>
              <b-col
                md="4"
                cols="6"
              >
                <b-form-checkbox
                  class="d-flex align-items-center custom-control-danger"
                  :checked="showSelected(false)"
                  @change="changeSelected(false)"
                >
                  <strong class="text-danger">Failure</strong>
                </b-form-checkbox>
              </b-col>
              <b-col cols="12">
                <b-form-group
                  label-for="news-edit-title"
                  class="mb-2 mt-2"
                >
                  <quill-editor
                    id="review"
                    v-model="resultLocal.review"
                    name="review"
                    :options="snowOption"
                    aria-placeholder="reivew here"
                  />
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-card-text>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import {
  BCol,
  BCard,
  BCardText,
  BLink,
  BRow,
  BCardBody,
  BForm,
  BFormGroup,
  BFormCheckbox,
} from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import Controls from './Controls.vue'
import NoVideoTile from './NoVideoTile.vue'
import interview from '@/store/api/Interview'

export default {
  name: 'VideoTile',
  components: {
    BCol,
    BCard,
    BCardText,
    BLink,
    BRow,
    BCardBody,
    BForm,
    BFormGroup,
    BFormCheckbox,
    AppCollapse,
    AppCollapseItem,
    quillEditor,

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
    'showInfo',
    'user',
    'savePractice',
    'result',
    'count',
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
      snowOption: {
        theme: 'snow',
      },
      resultLocal: {
        is_success: null,
        review: null,
      },
      checkNews: false,
    }
  },
  watch: {
    chunks(val) {
      if (val && val.length && (!this.interview.company || (this.interview.company && this.checkNews))) {
        window.onbeforeunload = e => {
          e.preventDefault()
          this.leaveCallCustom()
          return 'Something went wrong! Are you sure you want to leave?'
        }
      }
    },
    savePractice: {
      handler() {
        if (this.savePractice && ((this.first && this.participant.video) || (this.first && !this.participant.video && !this.hasScreen)) && this.user && this.user.company_id) {
          this.saveReview()
        }
      },
      deep: true,
    },
    resultLocal: {
      handler() {
        this.$emit('update-result', this.resultLocal)
      },
      deep: true,
    },
    count: {
      handler() {
        if (this.count > 1 && !this.checkNews) {
          this.checkNews = true
        }
      },
      deep: true,
    },
  },
  mounted() {
    this.resultLocal = { ...this.result }
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
      const userOn = JSON.parse(localStorage.getItem('userData'))
      if (this.chunks && this.chunks.length && (!this.interview.company || (this.interview.company && this.checkNews))) {
        this.blobRecorder = new Blob(this.chunks, {
          type: 'video/webm',
        })
        const update = {
          candidate_id: this.interview.candidate.general.id,
          record: this.blobRecorder,
        }
        const formData = new FormData()
        for (const key in update) {
          formData.append(key, update[key])
        }
        interview.update(this.interview.id, formData)
          .then(rs => {
            window.onbeforeunload = null
            console.log(rs)

            if (this.interview.company && userOn && userOn.role === 'ROLE_CANDIDATE') {
              this.$router.push({ name: 'interview-meeting-result', params: { id: this.interview.id } })
            }
          })
          .catch(e => console.log(e))
      } else if (this.interview.company && userOn && userOn.role === 'ROLE_CANDIDATE') {
        this.$router.push({ name: 'interview-meeting-result', params: { id: this.interview.id } })
      }
    },
    leaveCallCustom() {
      this.leaveCall()
      this.saveRecording()
    },
    showSelected(val) {
      // eslint-disable-next-line
      return this.resultLocal.is_success == val
    },
    changeSelected(val) {
      // eslint-disable-next-line
      if (this.resultLocal.is_success == val) {
        this.resultLocal.is_success = null
      } else {
        this.resultLocal.is_success = val
      }
      console.log(this.resultLocal)
    },
    saveReview() {
      interview
        .update(this.interview.id, {
          candidate_id: this.interview.candidate.general.owner.id,
          result: {
            review: this.resultLocal.review,
          },
          is_success: this.resultLocal.is_success,
        })
        .then(resp => {
          console.log(resp)
          window.onbeforeunload = null
          this.$router.push({ name: 'interview-meeting-result', params: { id: this.interview.id } })
        })
        .catch(err => {
          console.log(err)
        })
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
  top: 15px;
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
<style lang="scss" scoped>
@import "~@core/scss/vue/libs/quill.scss";
</style>
