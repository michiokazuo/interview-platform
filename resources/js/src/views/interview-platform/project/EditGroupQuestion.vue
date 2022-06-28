<template>
  <validation-observer
    ref="formGQ"
    v-slot="{invalid}"
  >
    <b-form @submit.prevent="storeGQ">
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
                  v-model="group.title"
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
                  v-model="group.topics"
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
            class="mb-75"
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
            v-if="group.interview && group.interview.company"
            class="mb-75"
            cols="6"
          >
            <h5 class="text-capitalize">
              Creator
            </h5>
            <b-card-text>
              <b-link
                :to="{ name: 'pages-company-news-list', params: { id: group.interview.company.id } }"
              >
                {{ group.interview.company.general.name }}
              </b-link>
            </b-card-text>
          </b-col>
        </b-row>
      </b-card>

      <b-card>
        <b-row>
          <b-col cols="12">
            <div class="d-flex align-items-center mb-2">
              <feather-icon
                icon="InfoIcon"
                size="18"
              />
              <h4 class="mb-0 ml-75 text-capitalize">
                Questions
              </h4>
            </div>
          </b-col>
          <b-col cols="12">

            <b-form
              ref="formAdd"
              :style="{height: trHeight}"
              class="repeater-form"
              @submit.prevent="repeatAgain"
            >

              <!-- Row Loop -->
              <b-row
                v-for="(item, index) in items"
                :id="item.id"
                :key="item.id"
                ref="row"
                class="justify-content-end align-items-center"
              >

                <!-- Remove Button -->
                <b-col
                  cols="12"
                  class="mb-50 d-flex justify-content-end"
                >
                  <b-button
                    v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                    variant="outline-danger"
                    class="mt-0 mt-md-2"
                    @click="removeItem(item.id, index)"
                  >
                    <feather-icon
                      icon="XIcon"
                      class="mr-25"
                    />
                    <span>Delete</span>
                  </b-button>
                </b-col>
                <!-- Item Key -->
                <b-col
                  md="6"
                >
                  <b-form-group
                    label="Question"
                    label-for="item-key"
                  >
                    <validation-provider
                      v-slot="{ errors }"
                      name="Key"
                      rules="required"
                    >
                      <quill-editor
                        id="item-key"
                        v-model="group.qas[`key-${item.id}`]"
                        name="question"
                        :options="snowOption"
                        aria-placeholder="Question"
                        :state="errors.length > 0 ? false:null"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <!-- Item Value -->
                <b-col
                  md="6"
                >
                  <b-form-group
                    label="Answer"
                    label-for="item-value"
                  >
                    <validation-provider
                      v-slot="{ errors }"
                      name="answer"
                    >
                      <quill-editor
                        id="item-key"
                        v-model="group.qas[`value-${item.id}`]"
                        name="answer"
                        :options="snowOption"
                        aria-placeholder="Answer"
                        :state="errors.length > 0 ? false:null"
                      />
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <hr>
                </b-col>
              </b-row>

            </b-form>
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              @click="repeatAgain"
            >
              <feather-icon
                icon="PlusIcon"
                class="mr-25"
              />
              <span>Add New</span>
            </b-button>
          </b-col>
        </b-row>
      </b-card>

      <b-col cols="12 d-flex justify-content-center">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          type="submit"
          variant="primary"
          class="mt-1 mr-1"
          :disabled="invalid && items.length > 0"
        >
          Save changes
        </b-button>
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          type="reset"
          class="mt-1"
          variant="outline-secondary"
          @click.prevent="resetForm"
        >
          Reset
        </b-button>
      </b-col>
    </b-form>
  </validation-observer>
</template>

<script>
import {
  BButton,
  BForm,
  BFormGroup,
  BFormInput,
  BRow,
  BCol,
  BCard,
  BCardText,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@core/mixins/ui/transition'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { required } from '@validations'
import vSelect from 'vue-select'
import { quillEditor } from 'vue-quill-editor'
import groupQS from '@/store/api/GroupQuestion'
import utils from '@/store/utils'

export default {
  components: {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    ValidationProvider,
    ValidationObserver,
    quillEditor,
    vSelect,
    BCardText,
  },
  directives: {
    Ripple,
  },
  mixins: [heightTransition],
  data() {
    return {
      group: {
        qas: {},
      },
      groupData: {},
      items: [],
      nextTodoId: 1,
      snowOption: {
        theme: 'snow',
      },
      required,
    }
  },
  mounted() {
    this.initTrHeight()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
    const { id } = this.$route.params
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
      this.group = {
        qas: {},
      }
    }
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {
    getData() {
      groupQS.findById(this.id).then(resp => {
        const rs = resp.data
        this.groupData = rs.data
        this.convertDataToShow(this.groupData)
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.groupData = {}
      })
    },
    resetForm() {
      this.initTrHeight()
      this.items = {
      }
      this.nextTodoId = 1
      this.group = {}
      this.convertDataToShow(this.groupData)
    },
    repeatAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })
      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(id, index) {
      delete this.group.qas[`key-${id}`]
      delete this.group.qas[`value-${id}`]
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.formAdd.scrollHeight)
      })
    },
    convertDataToShow(data) {
      this.group.title = data.title
      this.group.topics = data.topics?.split(',')
      const detailGroup = data.questions ?? []
      // eslint-disable-next-line no-restricted-syntax
      for (const item of detailGroup) {
        this.repeatAgain()
        const { id } = this.items.slice(-1)[0]
        this.group.qas[`key-${id}`] = item.content
        this.group.qas[`value-${id}`] = item.answers[0]?.content
        this.group.qas[`id-${id}`] = item.id
      }
    },
    convertDataToSave() {
      const rs = {
        title: '',
        topics: '',
        edit_questions: [],
        is_interview: true,
      }
      rs.title = this.group.title
      rs.topics = (this.group.topics ?? []).join(',')
      const detailGroup = this.group.qas ?? {}

      // eslint-disable-next-line no-restricted-syntax
      for (const item in this.items) {
        if (Object.prototype.hasOwnProperty.call(this.items, item)) {
          rs.edit_questions.push({
            question: {
              root_question_id: null,
              content: detailGroup[`key-${this.items[item].id}`],
              title: '',
              id: detailGroup[`id-${this.items[item].id}`],
            },
            answer: detailGroup[`value-${this.items[item].id}`] ? {
              content: detailGroup[`value-${this.items[item].id}`],
              score: 1,
              answered: false,
            } : null,
          })
        }
      }

      return rs
    },
    storeGQ() {
      this.$refs.formGQ.validate().then(success => {
        if (success && this.items.length > 0) {
          const data = this.convertDataToSave(this.group)

          if (this.id) {
            groupQS.update(this.id, data)
              .then(response => {
                const resp = response.data
                utils.updateUser(resp.user)
                this.$ability.update([
                  {
                    action: 'manage',
                    subject: resp.user.role,
                  },
                ])
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Update Group questions success',
                    icon: 'CoffeeIcon',
                    variant: 'success',
                  },
                })

                this.$router.push({ name: 'pages-manage-interview-question' })
              })
              .catch(error => {
                console.log(error)
                this.$refs.formCV.setErrors([])
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
            groupQS.store(data)
              .then(response => {
                const resp = response.data
                utils.updateUser(resp.user)
                this.$ability.update([
                  {
                    action: 'manage',
                    subject: resp.user.role,
                  },
                ])
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: 'Create Group questions success',
                    icon: 'CoffeeIcon',
                    variant: 'success',
                  },
                })

                this.$router.push({ name: 'pages-manage-interview-question' })
              })
              .catch(error => {
                console.log(error)
                this.$refs.formCV.setErrors([])
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
        }
      })
    },
  },
}
</script>

<style lang="scss" scoped>
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
@import "~@core/scss/vue/libs/quill.scss";
</style>

<style lang="scss" scoped>
.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
</style>
