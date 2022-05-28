<template>
  <b-card
    v-if="Object.keys(processEdit ? processEdit : {}).length > 0"
    class="process-edit-wrapper"
  >
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="d-flex align-items-center mr-1">
          <b-link
            v-if="processEdit.prev_step"
            @click.prevent="goToStep(processEdit.prev_step)"
          >
            <div class="d-inline-flex align-items-center text-primary">
              <feather-icon
                icon="ArrowLeftIcon"
                size="18"
                class="mr-50"
              />
              <span>Prev Step</span>
            </div>
          </b-link>
        </div>
      </div>

      <!-- dropdown -->
      <div class="blog-detail-share">
        <b-link
          v-if="processEdit.next_step"
          @click.prevent="goToStep(processEdit.next_step)"
        >
          <div class="d-inline-flex align-items-center text-primary">
            <span>Next Step</span>
            <feather-icon
              icon="ArrowRightIcon"
              size="18"
              class="mr-50"
            />
          </div>
        </b-link>
      </div>
      <!--/ dropdown -->
    </div>

    <!-- eslint-enable -->
    <hr class="my-2">
    <!-- media -->
    <b-media
      no-body
      vertical-align="center"
    >
      <b-media-aside class="mr-75">
        <b-avatar
          size="38"
          :src="processEdit.user.avatar"
        />
      </b-media-aside>
      <b-media-body>
        <h6 class="mb-25">
          {{ processEdit.user.fullName || processEdit.user.name }}
        </h6>
        <b-card-text>{{ processEdit.start_time ? new Date(processEdit.start_time).toDateString() : new Date().toDateString() }}</b-card-text>
      </b-media-body>
    </b-media>
    <!--/ media -->

    <validation-observer
      ref="processForm"
      #default="{invalid}"
    >
      <!-- form -->
      <b-form
        class="mt-2"
        @submit.prevent="saveProcess"
      >
        <b-row>
          <b-col cols="12">
            <b-form-group
              label="Title"
              label-for="process-edit-title"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Title"
                vid="title"
                rules="required"
              >
                <b-form-input
                  id="process-edit-title"
                  v-model="processEdit.title"
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
              label-for="process-edit-start-time"
              class="mb-2"
            >
              <flat-pickr
                id="process-edit-start-time"
                v-model="processEdit.start_time"
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
              label-for="process-edit-end-time"
              class="mb-2"
            >
              <flat-pickr
                id="process-edit-end-time"
                v-model="processEdit.end_time"
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
              label-for="process-edit-description"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Description"
                vid="description"
                rules="required"
              >
                <quill-editor
                  id="process-description"
                  v-model="processEdit.description"
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
                v-if="id && idProject"
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                variant="outline-success"
                @click.prevent="endProcess"
              >
                <span v-if="processEdit.end_time">Continue</span>
                <span v-else>End Process (Close)</span>
              </b-button>
            </div>
            <b-button
              v-if="processEdit.id"
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
      @ok="deleteProcess"
    >
      <b-card-text>
        Are you sure you want to delete this process?
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
  BLink,
} from 'bootstrap-vue'
import { quillEditor } from 'vue-quill-editor'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, password } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import process from '@/store/api/RProcess'
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
    BLink,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      id: null,
      idProject: null,
      processEdit: {
        user: {},
        start_time: new Date(),
      },
      snowOption: {
        theme: 'snow',
      },
    }
  },
  created() {
    const { id } = this.$route.params
    const { idProject } = this.$route.params
    this.idProject = idProject
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
      this.processEdit = {
        user: JSON.parse(localStorage.getItem('userData')),
        start_time: new Date(),
      }
    }
  },
  methods: {
    getData() {
      process.findById(this.id).then(resp => {
        const rs = resp.data
        this.processEdit = rs.data
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
        this.processEdit = null
        console.log(this.processEdit)
      })
    },
    saveProcess() {
      this.$refs.processForm.validate().then(success => {
        if (success) {
          this.processEdit.project_id = this.idProject
          if (this.id) {
            process.update(this.id, this.processEdit).then(resp => {
              const rs = resp.data
              this.processEdit = rs.data
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
                  title: 'Update Process success',
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
            process.store(this.processEdit).then(resp => {
              const rs = resp.data
              this.processEdit = rs.data
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
                  title: 'Create Process success',
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
    deleteProcess(bvModalEvent) {
      bvModalEvent.preventDefault()

      process.delete(this.id).then(resp => {
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
            title: 'Delete Process success',
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
    endProcess() {
      process.changeStatus(this.id).then(resp => {
        const rs = resp.data
        this.processEdit = rs.data
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
            title: this.processEdit.end_time ? 'End Process success' : 'Continue Process success',
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
    goToStep(step) {
      this.id = step
      this.getData()
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
