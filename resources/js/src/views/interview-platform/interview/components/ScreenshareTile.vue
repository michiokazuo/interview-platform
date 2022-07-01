<template>
  <div class="col-md-9 col-12 vh-100 overflow-auto text-justify">
    <video
      v-if="!practice"
      autoPlay
      muted
      playsInline
      :srcObject.prop="videoSource"
    />
    <practice-test
      v-else
      :is-meeting="true"
      :id-interview="interview"
      :save-practice="savePractice"
    />

    <b-card
      v-if="userOn && userOn.company_id"
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
  BRow,
  BCol,
  BCard,
  BCardText,
  BCardBody,
  BForm,
  BFormGroup,
  BFormCheckbox,
} from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import PracticeTest from '../PracticeTest.vue'
import interview from '@/store/api/Interview'

export default {
  name: 'ScreenshareTile',
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BCardBody,
    BForm,
    BFormGroup,
    BFormCheckbox,
    quillEditor,

    PracticeTest,
  },
  // eslint-disable-next-line vue/require-prop-types
  props: ['participant', 'interview', 'practice', 'savePractice', 'userOn', 'result', 'count'],
  data() {
    return {
      videoSource: null,
      snowOption: {
        theme: 'snow',
      },
      resultLocal: {
        is_success: null,
        review: null,
      },
    }
  },
  watch: {
    savePractice: {
      handler() {
        if (this.savePractice && this.userOn && this.userOn.company_id && this.count > 1) {
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
  },
  mounted() {
    this.resultLocal = { ...this.result }
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
    showSelected(val) {
      // eslint-disable-next-line
      return this.resultLocal.is_success == val
    },
    changeSelected(val) {
      if (this.resultLocal.is_success === val) {
        this.resultLocal.is_success = null
      } else {
        this.resultLocal.is_success = val
      }
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
</style>
<style lang="scss" scoped>
@import "~@core/scss/vue/libs/quill.scss";
</style>
