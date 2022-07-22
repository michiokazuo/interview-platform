<template>
  <!-- question-test -->
  <b-row
    v-if="id && interview && !interview.result"
    class="blog-list-wrapper match-height"
  >
    <b-col
      cols="12"
    >
      <validation-observer
        ref="formTest"
        v-slot="{invalid}"
      >
        <!-- form -->
        <b-form
          class="mt-2"
          @submit.prevent="saveTest"
        >
          <b-card
            title="General"
          >
            <b-row>
              <b-col md="6">
                <b-form-group
                  label="Title"
                  label-for="blog-edit-title"
                  class="mb-2"
                >
                  <validation-provider
                    v-slot="{ errors }"
                    name="Title"
                    vid="title"
                    rules="required"
                  >
                    <b-form-input
                      id="blog-edit-title"
                      v-model="groupTest.title"
                      :state="errors.length > 0 ? false:null"
                      name="title"
                      placeholder="Title"
                    />
                    <small class="text-danger">{{ errors[0] }}</small>
                  </validation-provider>
                </b-form-group>
              </b-col>
              <b-col md="6">
                <b-form-group
                  label="Topics"
                  label-for="blog-edit-topic"
                  class="mb-2"
                >
                  <validation-provider
                    name="Topics"
                    vid="topics"
                  >
                    <v-select
                      id="blog-edit-topic"
                      v-model="groupTest.topics"
                      multiple
                      taggable
                      push-tags
                      name="topics"
                      placeholder="Topics"
                    />
                  </validation-provider>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col
                md="3"
                class="mb-75"
                sm="4"
                cols="6"
              >
                <h5 class="text-capitalize ">
                  Date Edit
                </h5>
                <b-card-text>
                  {{ new Date().toLocaleDateString() }}
                </b-card-text>
              </b-col>
              <b-col
                v-if="interview.company"
                md="3"
                class="mb-75"
                sm="4"
                cols="6"
              >
                <h5 class="text-capitalize">
                  Creator
                </h5>
                <b-card-text>
                  <b-link
                    :to="{ name: 'pages-company-news-list', params: { id: interview.company.id } }"
                  >
                    {{ interview.company.general.name }}
                  </b-link>
                </b-card-text>
              </b-col>
              <b-col
                v-if="interview.news"
                md="3"
                class="mb-75"
                sm="4"
                cols="6"
              >
                <h5 class="text-capitalize ">
                  Recruitment news
                </h5>
                <b-card-text>
                  <b-link
                    :to="{ name: 'pages-news-detail', params: { id: interview.news.id } }"
                  >
                    {{ interview.news.title }}
                  </b-link>
                </b-card-text>
              </b-col>
              <b-col
                v-if="interview.news"
                md="3"
                class="mb-75"
                sm="4"
                cols="6"
              >
                <h5 class="text-capitalize ">
                  For candidate
                </h5>
                <b-card-text>
                  {{ interview.candidate.general.name }}
                </b-card-text>
              </b-col>
            </b-row>
          </b-card>
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

          <b-row class="blog-list-wrapper match-height">
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
                      <b-form-checkbox
                        class="d-flex align-items-center"
                        :checked="showSelected(question.id)"
                        @change="changeSelected(question.id)"
                      >
                        <div
                          class="mail-message blog-content-truncate"
                          v-html="question.title"
                        />
                      </b-form-checkbox>
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
                          <feather-icon
                            v-if="question.root_question"
                            icon="ExternalLinkIcon"
                            class="ml-50 text-warning"
                            @click.prevent="setQA(question.root_question, false)"
                          />

                        </div>
                      </b-link>
                      <b-link
                        class="font-weight-bold"
                        @click.prevent="setQA(question, false)"
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
                  :disabled="invalid && !(questions && questions.length > 0)"
                  type="submit"
                >
                  Save test
                </b-button>
              </div>

            </b-col>
          </b-row>
        </b-form>
        <!--/ form -->
      </validation-observer>

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
          v-if="isEditQuestionSelected"
          class="bg-light"
          title="Edit question"
        >
          <b-card>
            <validation-observer
              ref="formEdit"
              v-slot="{invalid}"
            >
              <b-form
                class="mt-2"
                @submit.prevent="createEdit(question)"
              >
                <b-row>
                  <b-col cols="12">
                    <b-form-group
                      label="Question"
                      label-for="blog-content"
                      class="mb-2"
                    >
                      <validation-provider
                        v-slot="{ errors }"
                        name="Question"
                        vid="question"
                        rules="required"
                      >
                        <quill-editor
                          id="blog-content"
                          v-model="editQuestion.question"
                          name="question"
                          :options="snowOption"
                          aria-placeholder="Question"
                          :state="errors.length > 0 ? false:null"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                  <b-col cols="12">
                    <b-form-group
                      label="Answer"
                      class="mb-2"
                    >
                      <quill-editor
                        id="blog-answer"
                        v-model="editQuestion.answer"
                        name="answer"
                        :options="snowOption"
                        aria-placeholder="Answer"
                      />

                    </b-form-group>
                  </b-col>
                  <b-col
                    cols="12"
                    class="mt-50 d-flex justify-content-between"
                  >
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      variant="primary"
                      class="mr-1"
                      type="submit"
                      :disabled="invalid"
                    >
                      Save Edit
                    </b-button>
                    <b-button
                      v-if="editQuestionSelected[question.id]"
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      class="mr-1"
                      variant="outline-danger"
                      @click.prevent="deleteEdit(question.id)"
                    >
                      Delete
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>

            </validation-observer>
          </b-card>
        </b-card>

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
          v-for="question_d in questionsShow"
          :key="question_d.id"
          md="6"
          lg="4"
        >
          <b-card
            tag="article"
            no-body
          >
            <b-card-body class="d-flex justify-content-between flex-column">
              <b-card-title>
                <b-form-checkbox
                  :checked="showSelected(question_d.id)"
                  @change="changeSelected(question_d.id)"
                >
                  <div
                    class="mail-message blog-content-truncate"
                    v-html="question_d.title"
                  />
                </b-form-checkbox>
              </b-card-title>

              <b-media no-body>
                <b-media-body>
                  <div
                    class="mail-message blog-content-truncate"
                    v-html="limitContent(question_d.content)"
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
                    <span v-if="question_d.answers"> {{ question_d.answers.length }} </span>
                    <feather-icon
                      v-if="editQuestionSelected[question_d.id]"
                      icon="EditIcon"
                      class="ml-50 text-success"
                    />
                    <feather-icon
                      v-if="question_d.root_question"
                      icon="ExternalLinkIcon"
                      class="ml-50 text-warning"
                      @click.prevent="setQA(question_d.root_question, false)"
                    />
                  </div>
                </b-link>
                <b-link
                  class="font-weight-bold"
                  @click.prevent="setQA(question_d, true)"
                >
                  Detail & Edit
                </b-link>
              </div>
            </b-card-body>
          </b-card>
        </b-col>
        <b-col cols="12">
          <!-- pagination -->
          <div class="my-2">
            <b-pagination-nav
              v-if="rows"
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
  BForm,
  BFormInput,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { quillEditor } from 'vue-quill-editor'
import interview from '@/store/api/Interview'
import dashboard from '@/store/api/Dashboard'
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
    ValidationProvider,
    ValidationObserver,
    BForm,
    BFormInput,
    quillEditor,
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
      perPage: 12,
      rows: 100,
      userOn: {},
      snowOption: {
        theme: 'snow',
      },
      tags: [],
      questionsSearch: null,
      idQSelected: [],
      currentPageSearch: 1,
      perPageSearch: 20,
      rowsSearch: 100,
      tagSearch: [],
      groupTest: {
        topics: [],
      },
      required,
      editQuestionSelected: {},
      editQuestion: {
        question: '',
        answer: '',
      },
      isEditQuestionSelected: false,
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
    this.limitContent = utils.limitContent
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
      const color = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'dark',
      ]
      const rd = color[tag.length % color.length]
      return `light-${rd}`
    },
    getData() {
      interview
        .findById(this.id)
        .then(resp => {
          const rs = resp.data
          this.interview = rs.data
          this.currentPage = 1
          this.rows = Math.ceil(this.interview.questions?.length / this.perPage)
          this.questions = this.interview.questions ?? []
          this.questionsShow = this.interview.questions?.slice(
            (this.currentPage - 1) * this.perPage,
            this.currentPage * this.perPage,
          )
          this.idQSelected = this.interview.questions?.map(
            item => item.id,
          ) ?? []
          this.groupTest = this.interview?.group_question ?? {}
          this.groupTest.topics = this.groupTest.topics ? this.groupTest.topics.split(',') : []
          this.userOn = rs.user
          utils.updateUser(rs.user)
          this.$ability.update([
            {
              action: 'manage',
              subject: rs.user.role,
            },
          ])
          this.userOn = rs.user
        })
        .catch(err => {
          console.log(err)
          this.interview = null
        })
    },
    setQA(question, isEdit) {
      this.question = question
      this.isEditQuestionSelected = isEdit
      this.editQuestion = this.editQuestionSelected[question.id] ?? {
        question: '',
        answer: '',
      }
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
        delete this.editQuestionSelected[id]
        this.editQuestion = {
          question: '',
          answer: '',
        }
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
      this.rows = Math.ceil(this.idQSelected.length / this.perPage)
    },
    saveTest() {
      this.$refs.formTest.validate().then(success => {
        if (success && this.idQSelected.length) {
          const editQuestions = []
          const idRootQs = []
          // eslint-disable-next-line no-restricted-syntax
          for (const key in this.editQuestionSelected) {
            if (Object.prototype.hasOwnProperty.call(this.editQuestionSelected, key)) {
              editQuestions.push({
                question: {
                  root_question_id: key,
                  content: this.editQuestionSelected[key].question,
                  title: this.editQuestionSelected[key].title,
                },
                answer: this.editQuestionSelected[key].answer ? {
                  content: this.editQuestionSelected[key].answer,
                  score: 1,
                  answered: false,
                } : null,
              })

              idRootQs.push(key - 0)
            }
          }

          interview.createTest(this.id, {
            candidate_id: this.interview.candidate.general.id,
            interview_questions: this.idQSelected.filter(item => !idRootQs.includes(item)),
            title: this.groupTest.title,
            topics: this.groupTest.topics?.join(','),
            edit_questions: editQuestions,
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
            dashboard.notify({
              emails: [this.interview.candidate.general.email],
              subject: 'Test created',
              name: this.interview.candidate.general.name,
              body: `${this.interview.company.general.name} has created a test for you. <b><a href="${process.env.APP_URL}/interview/${this.id}/practice-test">Click here to see</a></b> <br />
              Please login to the system to start the test.`,
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
        } else {
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'warning',
              text: 'Something not filled in the input. Please check again!!!',
            },
          })
        }
      })
    },
    createEdit(question) {
      this.editQuestionSelected[question.id] = {
        ...this.editQuestion,
        title: question.title,
      }
      this.question = {
        ...this.question,
        edit: true,
      }
      this.$toast({
        component: ToastificationContent,
        position: 'top-right',
        props: {
          title: 'Notification',
          icon: 'CoffeeIcon',
          variant: 'success',
          text: 'It will be saved when you save this test!!!',
        },
      })
      this.$nextTick(() => {
        this.$bvModal.hide('modal-detail-answer')
      })
    },
    deleteEdit(id) {
      delete this.editQuestionSelected[id]
      this.editQuestion = {
        question: '',
        answer: '',
      }
      this.question = {
        ...this.question,
        edit: false,
      }

      this.$toast({
        component: ToastificationContent,
        position: 'top-right',
        props: {
          title: 'Notification',
          icon: 'CoffeeIcon',
          variant: 'success',
          text: 'It will be saved when you save this test!!!',
        },
      })
      this.$nextTick(() => {
        this.$bvModal.hide('modal-detail-answer')
      })
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
