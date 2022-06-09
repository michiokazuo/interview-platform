<template>
  <b-card
    v-if="Object.keys(projectEdit ? projectEdit : {}).length > 0"
    class="project-edit-wrapper"
  >
    <!-- media -->
    <b-media
      no-body
      vertical-align="center"
    >
      <b-media-aside class="mr-75">
        <b-avatar
          size="38"
          :src="projectEdit.user.avatar"
        />
      </b-media-aside>
      <b-media-body>
        <h6 class="mb-25">
          {{ projectEdit.user.fullName || projectEdit.user.name }}
        </h6>
        <b-card-text>{{ projectEdit.start_time ? new Date(projectEdit.start_time).toDateString() : new Date().toDateString() }}</b-card-text>
      </b-media-body>
    </b-media>
    <!--/ media -->

    <validation-observer
      ref="projectForm"
      v-slot="{invalid}"
    >
      <!-- form -->
      <b-form
        class="mt-2"
        @submit.prevent="saveProject"
      >
        <b-row>
          <b-col cols="12">
            <b-form-group
              label="Title"
              label-for="project-edit-title"
              class="mb-2"
            >
              <validation-provider
                v-slot="{ errors }"
                name="Title"
                vid="title"
                rules="required"
              >
                <b-form-input
                  id="project-edit-title"
                  v-model="projectEdit.title"
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
              label="Start time"
              label-for="project-edit-start-time"
              class="mb-2"
            >
              <flat-pickr
                id="project-edit-start-time"
                v-model="projectEdit.start_time"
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
              label-for="project-edit-end-time"
              class="mb-2"
            >
              <flat-pickr
                id="project-edit-end-time"
                v-model="projectEdit.end_time"
                name="end_time"
                placeholder="End time"
                disabled
                readonly
                class="form-control"
                :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
              />

            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Description"
              label-for="project-edit-description"
              class="mb-2"
            >
              <validation-provider
                v-slot="{ errors }"
                name="Description"
                vid="description"
                rules="required"
              >
                <quill-editor
                  id="project-description"
                  v-model="projectEdit.description"
                  name="description"
                  :options="snowOption"
                  aria-placeholder="Description here"
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
                v-if="id"
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                variant="outline-success"
                @click.prevent="endProject"
              >
                <span v-if="projectEdit.end_time">Continue</span>
                <span v-else>End Project (Close)</span>
              </b-button>
            </div>
            <b-button
              v-if="projectEdit.id"
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
      @ok="deleteProject"
    >
      <b-card-text>
        Are you sure you want to delete this project?
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
import project from '@/store/api/Project'
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
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      projectEdit: {
        user: JSON.parse(localStorage.getItem('userData')),
        start_time: new Date(),
      },
      snowOption: {
        theme: 'snow',
      },
    }
  },
  created() {
    if (this.id) {
      this.getData()
    }
  },
  methods: {
    getData() {
      project.findById(this.id).then(resp => {
        const rs = resp.data
        this.projectEdit = rs.data
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.projectEdit = null
      })
    },
    saveProject() {
      this.$refs.projectForm.validate().then(success => {
        if (success) {
          if (this.id) {
            project.update(this.id, this.projectEdit).then(resp => {
              const rs = resp.data
              this.projectEdit = rs.data
              this.userOn = rs.user
              utils.updateUser(rs.user)
              this.$ability.update([
                {
                  action: 'manage',
                  subject: 'all',
                  // subject: userData.role,
                },
              ])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Update Project success',
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
          } else {
            project.store(this.projectEdit).then(resp => {
              const rs = resp.data
              this.projectEdit = rs.data
              this.userOn = rs.user
              utils.updateUser(rs.user)
              this.$ability.update([
                {
                  action: 'manage',
                  subject: 'all',
                  // subject: userData.role,
                },
              ])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Create Project success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$router.push({ name: 'pages-project-detail', params: { id: this.projectEdit.id } })
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
    deleteProject(bvModalEvent) {
      bvModalEvent.preventDefault()

      project.delete(this.id).then(resp => {
        const rs = resp.data
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete Project success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.$router.push({ name: 'pages-company-projects' })
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
    endProject() {
      project.changeStatus(this.id).then(resp => {
        const rs = resp.data
        this.projectEdit = rs.data
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: this.projectEdit.end_time ? 'End Project success' : 'Continue Project success',
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
