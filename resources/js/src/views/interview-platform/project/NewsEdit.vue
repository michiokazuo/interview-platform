<template>
  <b-card
    v-if="Object.keys(newsEdit ? newsEdit : {}).length > 0"
    class="news-edit-wrapper"
  >
    <!-- media -->
    <b-media
      no-body
      vertical-align="center"
    >
      <b-media-aside class="mr-75">
        <b-avatar
          size="38"
          :src="newsEdit.user.avatar"
        />
      </b-media-aside>
      <b-media-body>
        <h6 class="mb-25">
          {{ newsEdit.user.fullName || newsEdit.user.name }}
        </h6>
        <b-card-text>{{ newsEdit.start_time ? new Date(newsEdit.start_time).toDateString() : new Date().toDateString() }}</b-card-text>
      </b-media-body>
    </b-media>
    <!--/ media -->

    <validation-observer
      ref="newsForm"
      #default="{invalid}"
    >
      <!-- form -->
      <b-form
        class="mt-2"
        @submit.prevent="saveNews"
      >
        <b-row>
          <b-col md="6">
            <b-form-group
              label="Title"
              label-for="news-edit-title"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Title"
                vid="title"
                rules="required"
              >
                <b-form-input
                  id="news-edit-title"
                  v-model="newsEdit.title"
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
              label="Job Position"
              label-for="job-position"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="JobPosition"
                vid="JobPosition"
                rules="required"
              >
                <b-form-input
                  id="news-edit-job-position"
                  v-model="newsEdit.job_position"
                  :state="errors.length > 0 ? false:null"
                  name="job_position"
                  placeholder="Job Position"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            md="6"
            class=""
          >
            <b-form-group
              label="Working form"
              label-for="register-working-form"
            >
              <validation-provider
                #default="{ errors }"
                name="WorkingForm"
                vid="WorkingForm"
                rules="required"
              >
                <v-select
                  id="register-working-form"
                  v-model="newsEdit.working_form"
                  name="register-working-form"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Working form"
                  :options="options"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            md="6"
            class=""
          >
            <b-form-group
              label="Gender"
              label-for="Gender"
            >
              <validation-provider
                #default="{ errors }"
                name="Gender"
                vid="gender"
                rules=""
              >
                <v-select
                  id="register-gender"
                  v-model="newsEdit.gender"
                  name="register-gender"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Gender"
                  :options="optionsGender"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Start time"
              label-for="news-edit-start-time"
              class="mb-2"
            >
              <flat-pickr
                id="news-edit-start-time"
                v-model="newsEdit.start_time"
                name="start_time"
                placeholder="start time"
                disabled
                readonly
                class="form-control"
                :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
              />

            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="End time"
              label-for="news-edit-end-time"
              class="mb-2"
            >
              <flat-pickr
                id="news-edit-end-time"
                v-model="newsEdit.end_time"
                name="end_time"
                placeholder="End time"
                disabled
                readonly
                class="form-control"
                :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
              />

            </b-form-group>
          </b-col>
          <b-col
            md="6"
          >

            <b-form-group
              label="Salary"
              label-for="Salary"
            >
              <validation-provider
                #default="{ errors }"
                name="Salary"
                vid="Salary"
                rules="required"
              >
                <cleave
                  id="number"
                  v-model="newsEdit.salary"
                  class="form-control"
                  :raw="false"
                  :options="number"
                  placeholder="0,000"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>

            </b-form-group>
          </b-col>
          <b-col
            md="6"
          >

            <b-form-group
              label="Numbers of recruits"
              label-for="NumbersOfRecruits"
            >
              <validation-provider
                #default="{ errors }"
                name="NumbersOfRecruits"
                vid="NumbersOfRecruits"
                rules="required"
              >
                <cleave
                  id="number"
                  v-model="newsEdit.number_of_recruits"
                  class="form-control"
                  :raw="false"
                  :options="number"
                  placeholder="0,000"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>

            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Working place"
              label-for="news-edit-working-place"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="WorkingPlace"
                vid="WorkingPlace"
                rules="required"
              >
                <b-form-input
                  id="news-edit-working-place"
                  v-model="newsEdit.workplace"
                  :state="errors.length > 0 ? false:null"
                  name="working_place"
                  placeholder="Working place"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group
              label="Experience"
              label-for="news-edit-experience"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Experience"
                vid="Experience"
                rules="required"
              >
                <b-form-input
                  id="news-edit-experience"
                  v-model="newsEdit.experience"
                  :state="errors.length > 0 ? false:null"
                  name="experience"
                  placeholder="Experience"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Description"
              label-for="news-edit-description"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Description"
                vid="description"
                rules="required"
              >
                <quill-editor
                  id="news-description"
                  v-model="newsEdit.description"
                  name="description"
                  :options="snowOption"
                  aria-placeholder="Description here"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Requirements others"
              label-for="news-requirements-others"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="RequirementsOthers"
                vid="RequirementsOthers"
                rules=""
              >
                <quill-editor
                  id="news-requirements-others"
                  v-model="newsEdit.requirements"
                  name="description"
                  :options="snowOption"
                  aria-placeholder="Requirements here"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Benefits"
              label-for="news-benefits"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Benefits"
                vid="Benefits"
                rules="required"
              >
                <quill-editor
                  id="news-benefits"
                  v-model="newsEdit.benefits"
                  name="benefits"
                  :options="snowOption"
                  aria-placeholder="Benefits here"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            cols="12"
            class="mt-50 d-flex justify-content-between"
          >
            <div>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-1"
                type="submit"
                :disabled="invalid"
              >
                Save Changes
              </b-button>
              <b-button
                v-if="id && idProject"
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                variant="outline-success"
                @click.prevent="endNews"
              >
                <span v-if="newsEdit.end_time">Continue</span>
                <span v-else>End News (Close)</span>
              </b-button>
            </div>
            <b-button
              v-if="newsEdit.id"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              v-b-modal.modal-danger
              class="mr-1"
              variant="outline-danger"
            >
              Delete
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    <!--/ form -->
    </validation-observer>

    <b-modal
      id="modal-danger"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Alert"
      @ok="deleteNews"
    >
      <b-card-text>
        Are you sure you want to delete this news?
      </b-card-text>
    </b-modal>
  </b-card>

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
</template>

<script>
import {
  BCard,
  BMedia,
  BAvatar,
  BCardText,
  BMediaAside,
  BMediaBody,
  BForm,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BButton,
  BModal,
  BCardBody,
} from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, password } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import vSelect from 'vue-select'
import Cleave from 'vue-cleave-component'
import news from '@/store/api/RNews'
import utils from '@/store/utils'

export default {
  components: {
    BCard,
    BCardBody,
    BMedia,
    BAvatar,
    BCardText,
    BMediaAside,
    BMediaBody,
    BForm,
    BRow,
    BCol,
    BButton,
    BFormGroup,
    BModal,
    BFormInput,
    quillEditor,
    ValidationProvider,
    ValidationObserver,
    flatPickr,
    Cleave,
    vSelect,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
    idProject: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      newsEdit: {
        user: {},
        start_time: new Date(),
      },
      snowOption: {
        theme: 'snow',
      },
      options: ['Remote', 'Full time', 'Part time', 'Freelancer'],
      optionsGender: ['None', 'Male', 'Female'],
      number: {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
      },
      required, email, password,
    }
  },
  created() {
    if (this.id && this.idProject) {
      this.getData()
    } else {
      this.newsEdit = {
        user: JSON.parse(localStorage.getItem('userData')),
        start_time: new Date(),
      }
    }
  },
  methods: {
    getData() {
      news.showToEdit(this.id).then(resp => {
        const rs = resp.data
        this.newsEdit = rs.data
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
        this.newsEdit = null
      })
    },
    saveNews() {
      this.$refs.newsForm.validate().then(success => {
        if (success) {
          this.newsEdit.project_id = this.idProject
          const data = {
            ...this.newsEdit,
            salary: this.newsEdit.salary.replace(',', ''),
            number_of_recruits: this.newsEdit.number_of_recruits.replace(',', ''),
          }
          if (this.id) {
            news.update(this.id, data).then(resp => {
              const rs = resp.data
              this.newsEdit = rs.data
              this.userOn = rs.user
              utils.updateUser(rs.user)
              this.$ability.update([
                {
                  action: 'manage',
                  subject: rs.user.role,
                },
              ])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Update News success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$router.push({ name: 'pages-project-detail', params: { id: this.idProject } })
            }).catch(err => {
              console.log(err)
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Error',
                  icon: 'AlertTriangleIcon',
                  variant: 'danger',
                  text: 'Something error or you are not company. Please try again!!!',
                },
              })
            })
          } else {
            news.store(data).then(resp => {
              const rs = resp.data
              this.newsEdit = rs.data
              this.userOn = rs.user
              utils.updateUser(rs.user)
              this.$ability.update([
                {
                  action: 'manage',
                  subject: rs.user.role,
                },
              ])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Create News success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$router.push({ name: 'pages-project-detail', params: { id: this.idProject } })
            }).catch(err => {
              console.log(err)
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Error',
                  icon: 'AlertTriangleIcon',
                  variant: 'danger',
                  text: 'Something error or you are not company. Please try again!!!',
                },
              })
            })
          }
        }
      })
    },
    deleteNews(bvModalEvent) {
      bvModalEvent.preventDefault()

      news.delete(this.id).then(resp => {
        const rs = resp.data
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete News success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.$router.push({ name: 'pages-project-detail', params: { id: this.idProject } })
      }).catch(err => {
        console.log(err)
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Error',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
            text: 'Something error or you are not company. Please try again!!!',
          },
        })

        this.$nextTick(() => {
          this.$bvModal.hide('modal-danger')
        })
      })
    },
    endNews() {
      news.changeStatus(this.id).then(resp => {
        const rs = resp.data
        this.newsEdit = rs.data
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: this.newsEdit.end_time ? 'End News success' : 'Continue News success',
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
            text: 'Something error or you are not company. Please try again!!!',
          },
        })
      })
    },
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/quill.scss';
@import '~@core/scss/vue/pages/page-blog.scss';
@import '~@core/scss/vue/libs/vue-flatpicker.scss';
</style>
