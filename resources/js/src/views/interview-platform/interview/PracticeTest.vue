<template>
  <!-- question-test -->
  <b-row
    v-if="id && interview && !interview.result"
    class="blog-list-wrapper col-md-10 m-auto"
  >
    <b-col cols="12">
      <b-link
        v-if="interview.questions && interview.room && !interview.news"
        :to="{ name: 'interview-meeting', params: { id: interview.id } }"
        class="font-weight-bold mb-2"
        target="_blank"
      >
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          class="mb-2"
        >
          Go to the meeting
        </b-button>
      </b-link>
    </b-col>
    <b-col
      v-if="interview.questions"
    >
      <b-card
        v-for="question in questions"
        :key="question.id"
        tag="article"
        no-body
      >
        <b-card-body>
          <b-card-title>
            <div
              class="mail-message"
              v-html="question.title"
            />
          </b-card-title>
          <div class="my-1 py-25">
            <b-link
              v-for="(tag,index) in question.tags"
              :key="index"
            >
              <b-badge
                pill
                class="mr-75"
                :variant="tagsColor(tag.name)"
              >
                {{ tag.name }}
              </b-badge>
            </b-link>
          </div>
          <b-media no-body>
            <b-media-body>
              <div
                class="mail-message"
                v-html="question.content"
              />
            </b-media-body>
          </b-media>
          <b-card-text class="project-content-truncate mt-2">
            <quill-editor
              :id="`question-${question.id}`"
              v-model="result[`question-${question.id}`]"
              :options="snowOption"
              aria-placeholder="Answer here"
            />
          </b-card-text>
          <hr>
        </b-card-body>
      </b-card>
    </b-col>
    <b-col cols="12">
      <!-- pagination -->
      <div class="my-2">
        <b-pagination-nav
          v-model="currentPage"
          align="center"
          :number-of-pages="rows"
          class="mb-0"
          base-url="#"
        />
      </div>
    </b-col>

    <b-col
      cols="12"
      class="d-flex justify-content-center"
    >
      <b-button
        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
        variant="primary"
        class="mb-2"
        size="lg"
        :disabled="!(result && Object.keys(result).length > 0)"
        @click.prevent="saveRS"
      >
        Save result
      </b-button>
    </b-col>
  </b-row>
  <b-row v-else>
    <b-col cols="12">
      <b-card
        no-body
        class="faq-search pt-5 pb-5"
        :style="{backgroundImage:`url(${require('@/assets/images/banner/banner.png')})`}"
      >
        <b-card-body class="text-center">
          <h2 class="text-primary">
            Nothing to show or you have already finished the test
          </h2>
          <b-card-text class="mb-2">
            Please try again!!!
          </b-card-text>
        </b-card-body>
      </b-card>
    </b-col>
  </b-row>

  <!--/ question-test -->
</template>

<script>
import {
  BRow,
  BCol,
  BCard,
  BCardText,
  BCardTitle,
  BMedia,
  BMediaBody,
  BCardBody,
  BLink,
  BPaginationNav,
  BButton,
  BBadge,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { quillEditor } from 'vue-quill-editor'
import interview from '@/store/api/Interview'
import utils from '@/store/utils'

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BCardBody,
    BCardTitle,
    BMedia,
    BMediaBody,
    BLink,
    BPaginationNav,
    BButton,
    BBadge,
    quillEditor,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      id: null,
      interview: null,
      questions: null,
      result: {},
      currentPage: 1,
      perPage: 5,
      rows: 100,
      userOn: {},
      snowOption: {
        theme: 'snow',
      },
    }
  },
  watch: {
    currentPage() {
      this.questions = this.interview.questions?.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage,
      )
    },
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id - 0
      this.getData()
    } else {
      this.id = null
    }
  },
  methods: {
    kFormatter,
    tagsColor(tag) {
      console.log(tag)
      const color = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark']
      const rd = color[Math.floor(Math.random() * color.length)]
      return `light-${rd}`
    },
    getData() {
      interview.findById(this.id).then(resp => {
        const rs = resp.data
        this.interview = rs.data
        this.currentPage = 1
        this.rows = Math.ceil(this.interview.questions?.length / this.perPage)
        this.questions = this.interview.questions?.slice(
          (this.currentPage - 1) * this.perPage,
          this.currentPage * this.perPage,
        )
        if (!this.interview.result) {
          window.onbeforeunload = () => {
            this.saveRS()
            return 'Are you sure you want to leave?'
          }
        }
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.userOn = rs.user
      }).catch(err => {
        console.log(err)
        this.interview = null
      })
    },
    saveRS() {
      if (this.interview.company) {
        interview.saveRSTest(this.id, {
          candidate_id: this.userOn.candidate_id,
          result: this.result,
        }).then(() => {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Save result success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          window.onbeforeunload = null
          this.$router.push({ name: 'interview-meeting-result', params: { id: this.id } })
        }).catch(err => {
          console.log(err)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              text: 'Something error. Please try again!!!',
            },
          })
        })
      } else {
        interview.update(this.id, {
          candidate_id: this.userOn.candidate_id,
          result: this.result,
        }).then(() => {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Save result success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          window.onbeforeunload = null
          this.$router.push({ name: 'interview-meeting-result', params: { id: this.id } })
        }).catch(err => {
          console.log(err)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              text: 'Something error. Please try again!!!',
            },
          })
        })
      }
    },
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/libs/quill.scss';
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
