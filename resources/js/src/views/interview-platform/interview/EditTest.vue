<template>
  <!-- question-test -->
  <b-row
    v-if="id && interview && !interview.result"
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
          v-if="questions && questions.length"
          class="font-weight-bold"
        >
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            v-b-modal.modal-detail-selected
            variant="primary"
            class="mb-2"
          >
            Edit selected questions
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
      >
        <b-card
          tag="article"
          no-body
        >
          <b-card-body class="d-flex justify-content-between flex-column">
            <b-card-title>
              <b-form-checkbox
                class="d-flex align-items-center"
                :checked="showSelected(question.id)"
                @change="changeSelected(question.id)"
              >
                <div
                  class="mail-message"
                  v-html="question.title"
                />
              </b-form-checkbox>
              <div class="my-1 ml-2 py-25 h6">
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
            </b-card-title>

            <b-media no-body>
              <b-media-body>
                <div
                  class="mail-message blog-content-truncate"
                  v-html="question.content"
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
          v-model="currentPageSearch"
          align="center"
          :number-of-pages="rowsSearch"
          class="mb-0"
          base-url="#"
        />
      </div>
    </b-col>

    <b-col
      cols="12"
      class="d-flex justify-content-between align-items-center"
    >
      <div class="d-flex align-items-center">
        <b-link>
          <div class="d-flex align-items-center text-primary mr-2">
            <feather-icon
              icon="MessageSquareIcon"
              class="mr-50"
            />
            <span class="font-weight-bold">{{ questions ? questions.length : 0 }} Selected</span>
          </div>
        </b-link>
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          size="lg"
          :disabled="!(questions && questions.length > 0)"
          @click.prevent="saveTest"
        >
          Save test
        </b-button>
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
      id="modal-detail-selected"
      cancel-variant="outline-secondary"
      ok-title="OK"
      cancel-title="Close"
      scrollable
      size="xl"
      title="Detail Selected"
    >
      <b-row
        v-if="id && interview && questions"
        class="blog-list-wrapper bg-light pt-2 match-height"
      >
        <b-col
          v-for="question in questionsShow"
          :key="question.id"
          md="6"
        >
          <b-card
            tag="article"
            no-body
          >
            <b-card-body class="d-flex justify-content-between flex-column">
              <b-card-title>
                <b-form-checkbox
                  :checked="showSelected(question.id)"
                  @change="changeSelected(question.id)"
                >
                  <div
                    class="mail-message"
                    v-html="question.title"
                  />
                </b-form-checkbox>
                <div class="my-1 py-25 h6">
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
              </b-card-title>

              <b-media no-body>
                <b-media-body>
                  <div
                    class="mail-message blog-content-truncate"
                    v-html="question.content"
                  />
                </b-media-body>
              </b-media>
              <hr class="w-100">
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
      </b-row>
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
  BFormCheckbox,
  BButton,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
import interview from '@/store/api/Interview'
import qat from '@/store/api/QAT'
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
    BFormCheckbox,
    BFormGroup,
    BButton,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      id: null,
      interview: null,
      questions: null,
      question: {},
      currentPage: 1,
      perPage: 10,
      rows: 100,
      userOn: {},
      snowOption: {
        theme: 'snow',
      },
      tags: [],
      questionsSearch: null,
      idQSelected: [],
      currentPageSearch: 1,
      perPageSearch: 10,
      rowsSearch: 100,
      tagSearch: [],
    }
  },
  watch: {
    currentPage() {
      this.questionsShow = this.questions?.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage,
      )
    },
    currentPageSearch() {
      this.getQuestion()
    },
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id - 0
      this.getData()
      this.getTags()
      this.getQuestion()
    } else {
      this.id = null
    }
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
    getData() {
      interview
        .findById(this.id)
        .then(resp => {
          const rs = resp.data
          this.interview = rs.data
          this.currentPage = 1
          this.rows = this.interview.questions?.length + 1
          this.questions = this.interview.questions
          this.questionsShow = this.interview.questions?.slice(
            (this.currentPage - 1) * this.perPage,
            this.currentPage * this.perPage,
          )
          this.idQSelected = this.interview.questions?.map(
            item => item.id,
          )
          this.userOn = rs.user
          utils.updateUser(rs.user)
          this.$ability.update([
            {
              action: 'manage',
              subject: 'all',
              // subject: userData.role,
            },
          ])
          this.userOn = rs.user
        })
        .catch(err => {
          console.log(err)
          this.interview = null
        })
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
              subject: 'all',
              // subject: userData.role,
            },
          ])
          this.userOn = rs.user
        })
        .catch(err => {
          console.log(err)
          this.interview = null
        })
    },
    showSelected(id) {
      return this.idQSelected?.includes(id)
    },
    changeSelected(id) {
      if (this.idQSelected?.includes(id)) {
        this.idQSelected = this.idQSelected.filter(
          item => item !== id,
        )
        this.questions = this.questions.filter(item => item.id !== id)
      } else {
        this.idQSelected.push(id)
        this.questions.push(this.questionsSearch.find(item => item.id === id))
      }

      if (this.idQSelected.length === 0) {
        this.$nextTick(() => {
          this.$bvModal.hide('modal-detail-selected')
        })
      }
      this.questionsShow = this.questions?.slice(
        (this.currentPage - 1) * this.perPage,
        this.currentPage * this.perPage,
      )
      this.rows = this.idQSelected.length / this.perPage + 1
    },
    saveTest() {
      interview.createTest(this.id, {
        candidate_id: this.interview.candidate.general.id,
        interview_questions: this.idQSelected,
      }).then(() => {
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Create test success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })
        this.$router.push({ name: 'pages-news-edit', params: { idProject: this.interview.news.project_id, id: this.interview.news.id } })
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
    },
  },
}
</script>

<style lang="scss" scoped>
@import "~@core/scss/vue/libs/quill.scss";
@import "~@core/scss/vue/pages/page-blog.scss";
</style>
