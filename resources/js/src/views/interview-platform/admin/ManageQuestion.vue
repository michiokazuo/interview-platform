<template>
  <!-- question-test -->
  <b-row
    v-if="questionsSearch"
    class="blog-list-wrapper match-height"
  >
    <b-row class="w-100 m-0 justify-content-center">
      <b-col md="10">
        <b-form-group>
          <v-select
            v-model="tagSearch"
            multiple
            label="name"
            :options="tags"
            placeholder="Tags"
            class="bg-white"
          />
        </b-form-group>
      </b-col>
      <b-col
        md="10"
        class="d-flex flex-md-row flex-column justify-content-between align-items-center"
      >
        <b-link
          class="font-weight-bold"
        >
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            v-b-modal.modal-crawl
            variant="primary"
            class="mb-2"
          >
            Crawl Data QAT
          </b-button>
        </b-link>
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          class="mb-2"
          @click.prevent="getQuestion()"
        >
          Search
        </b-button>
      </b-col>
    </b-row>

    <template v-if="questionsSearch">
      <b-col
        v-for="question in questionsSearch"
        :key="question.id"
        md="6"
        lg="4"
        xl="3"
      >
        <b-card
          tag="article"
          no-body
        >
          <b-card-body class="d-flex justify-content-between flex-column">
            <b-card-title>
              <div
                class="mail-message blog-content-truncate"
                v-html="question.title"
              />
              <div class="mt-1 ml-2 h6 blog-content-truncate">
                <b-link
                  v-for="(tag,index) in question.tags"
                  :key="index"
                >
                  <b-badge
                    pill
                    class="mr-75 mb-50"
                    :variant="tagsColor(tag.name)"
                  >
                    {{ tag.name }}
                  </b-badge>
                </b-link>
              </div>
            </b-card-title>

            <b-media no-body>
              <b-media-body>
                <div
                  class="mail-message blog-content-truncate"
                  v-html="limitContent(question.content)"
                />
              </b-media-body>
            </b-media>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <hr class="w-100">
              <b-link>
                <div class="d-flex align-items-center text-body">
                  <feather-icon
                    icon="MessageSquareIcon"
                    class="mr-50"
                  />
                  <span v-if="question.answers"> {{ question.answers.length }} </span>
                </div>
              </b-link>
              <b-link
                class="font-weight-bold"
                @click.prevent="setQA(question)"
              >
                Details
              </b-link>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </template>
    <b-col cols="12">
      <!-- pagination -->
      <div class="my-2">
        <b-pagination-nav
          v-if="rowsSearch"
          v-model="currentPageSearch"
          align="center"
          :number-of-pages="rowsSearch"
          class="mb-0"
          base-url="#"
        />
      </div>
    </b-col>

    <b-modal
      id="modal-detail-answer"
      cancel-variant="outline-secondary"
      ok-title="OK"
      cancel-title="Close"
      scrollable
      size="xl"
      title="Detail Answer"
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
        <hr>
        <b-card
          class="bg-light"
          title="Other Answer"
        >
          <b-card
            v-for="answer in question.answers"
            :key="answer.id"
          >
            <b-media no-body>
              <b-media-body>
                <b-card-title>
                  <b-link class="text-body">
                    Score: {{ answer.score }}
                  </b-link>
                  <span class="text-muted ml-75 mr-50">|</span>
                  <feather-icon
                    v-if="answer.answered"
                    icon="CheckIcon"
                    class="mr-50 text-success font-weight-bold"
                    size="20"
                  />
                </b-card-title>

                <b-card-text>
                  <div
                    class="blog-content"
                    v-html="answer.content"
                  />
                </b-card-text>
              </b-media-body>
            </b-media>
          </b-card>
        </b-card>
      </b-card-body>
    </b-modal>

    <b-modal
      id="modal-crawl"
      cancel-variant="outline-secondary"
      ok-title="Accept"
      cancel-title="Close"
      centered
      title="Crawl data question from StackOverflow"
      @ok="crawlData"
    >

      <b-form>
        <b-form-group>
          <v-select
            v-model="crawler.tags"
            multiple
            taggable
            push-tags
            label="name"
            :options="tags"
            placeholder="Tags"
          />
        </b-form-group>
        <b-form-group>
          <label for="demo-sb">Number of questions</label>
          <b-form-spinbutton
            id="demo-sb"
            v-model="crawler.numbers"
            min="50"
            max="10000"
            step="50"
            placeholder="Number questions"
          />
        </b-form-group>
      </b-form>
    </b-modal>
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
            Nothing to show...
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
  BBadge,
  VBModal,
  BFormGroup,
  BButton,
  BFormSpinbutton,
  BForm,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
import qat from '@/store/api/QAT'
import admin from '@/store/api/Admin'
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
    BBadge,
    vSelect,
    BFormGroup,
    BButton,
    BFormSpinbutton,
    BForm,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      question: {},
      userOn: {},
      snowOption: {
        theme: 'snow',
      },
      tags: [],
      questionsSearch: null,
      currentPageSearch: 1,
      perPageSearch: 20,
      rowsSearch: 100,
      tagSearch: [],
      crawler: {
        tags: [],
        numbers: 50,
      },
    }
  },
  watch: {
    currentPageSearch() {
      this.getQuestion()
    },
  },
  created() {
    this.getTags()
    this.getQuestion()
    this.limitContent = utils.limitContent
  },
  methods: {
    kFormatter,
    tagsColor(tag) {
      console.log(tag)
      const color = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'dark',
      ]
      const rd = color[Math.floor(Math.random() * color.length)]
      return `light-${rd}`
    },
    setQA(question) {
      this.question = question
      this.$bvModal.show('modal-detail-answer')
    },
    getTags() {
      qat.getTags().then(resp => {
        const rs = resp.data
        this.tags = rs.data
        utils.updateUser(rs.user)
        this.userData = rs.user
      })
    },
    getQuestion() {
      qat.getQuestions({
        page: this.currentPageSearch,
        per_page: this.perPageSearch,
        tags: this.tagSearch.map(s => s.id),
      })
        .then(resp => {
          const rs = resp.data
          this.questionsSearch = rs.data.data
          this.currentPageSearch = rs.data.current_page
          this.rowsSearch = rs.data.last_page
          this.userOn = rs.user
          utils.updateUser(rs.user)
          this.$ability.update([
            {
              action: 'manage',
              subject: rs.user.role,
            },
          ])
        })
        .catch(err => {
          console.log(err)
          this.interview = null
        })
    },
    crawlData() {
      const { crawler } = this
      if (crawler.numbers) {
        admin.crawlData({
          tags: crawler.tags.map(s => s.name),
          numbers: crawler.numbers,
        }).then(() => {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Crawl data is running',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
        }).catch(err => {
          console.log(err)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              text: 'Something error or crawler is running. Please try again!!!',
            },
          })
        })
      }
    },
  },
}
</script>

<style lang="scss" scoped>
@import "~@core/scss/vue/libs/quill.scss";
@import "~@core/scss/vue/pages/page-blog.scss";
</style>
<style lang="css" scoped>
@import "~@core/css/stack.css";
</style>
