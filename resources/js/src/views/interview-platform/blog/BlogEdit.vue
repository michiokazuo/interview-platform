<template>
  <b-card
    v-if="Object.keys(blogEdit ? blogEdit : {}).length > 0"
    class="blog-edit-wrapper"
  >
    <!-- media -->
    <b-media
      no-body
      vertical-align="center"
    >
      <b-media-aside class="mr-75">
        <b-avatar
          size="38"
          :src="blogEdit.user.avatar"
        />
      </b-media-aside>
      <b-media-body>
        <h6 class="mb-25">
          {{ blogEdit.user.fullName }}
        </h6>
        <b-card-text>{{ blogEdit.created_at ? new Date(blogEdit.created_at).toDateString() : new Date().toDateString() }}</b-card-text>
      </b-media-body>
    </b-media>
    <!--/ media -->

    <validation-observer
      ref="blogForm"
      #default="{invalid}"
    >
      <!-- form -->
      <b-form
        class="mt-2"
        @submit.prevent="saveBlog"
      >
        <b-row>
          <b-col md="6">
            <b-form-group
              label="Title"
              label-for="blog-edit-title"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Title"
                vid="title"
                rules="required"
              >
                <b-form-input
                  id="blog-edit-title"
                  v-model="blogEdit.title"
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
                #default="{ errors }"
                name="Topics"
                vid="topics"
                rules="required"
              >
                <b-form-input
                  id="blog-edit-topic"
                  v-model="blogEdit.topics"
                  name="topics"
                  placeholder="Topics"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-primary">Use ',' to define multiple topics</small>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-form-group
              label="Content"
              label-for="blog-content"
              class="mb-2"
            >
              <validation-provider
                #default="{ errors }"
                name="Content"
                vid="content"
                rules="required"
              >
                <quill-editor
                  id="blog-content"
                  v-model="blogEdit.content"
                  name="content"
                  :options="snowOption"
                  aria-placeholder="Content"
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
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                variant="outline-secondary"
                @click.prevent="cancelChange"
              >
                Cancel
              </b-button>
            </div>
            <b-button
              v-if="blogEdit.id"
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
      @ok="deleteBlog"
    >
      <b-card-text>
        Are you sure you want to delete this blog?
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
import blog from '@/store/api/Blog'

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
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      id: null,
      blogEdit: {
        user: {},
      },
      snowOption: {
        theme: 'snow',
      },
    }
  },
  created() {
    this.blogEdit = {
      user: JSON.parse(localStorage.getItem('userData')),
    }
    const { id } = this.$route.params
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
    }
  },
  methods: {
    getData() {
      blog.showToEdit(this.id).then(resp => {
        const rs = resp.data
        this.blogEdit = rs.data
      }).catch(err => {
        console.log(err)
        this.blogEdit = null
        console.log(this.blogEdit)
      })
    },
    saveBlog() {
      this.$refs.blogForm.validate().then(success => {
        if (success) {
          if (this.id) {
            blog.update(this.id, this.blogEdit).then(resp => {
              const rs = resp.data
              this.blogEdit = rs.data
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Update Blog success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$router.push({ name: 'pages-blog-detail', params: { id: this.blogEdit.id } })
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
            blog.store(this.blogEdit).then(resp => {
              const rs = resp.data
              this.blogEdit = rs.data
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Create Blog success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$router.push({ name: 'pages-blog-detail', params: { id: this.blogEdit.id } })
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
        }
      })
    },
    deleteBlog(bvModalEvent) {
      bvModalEvent.preventDefault()

      blog.delete(this.id).then(resp => {
        console.log(resp)
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete Blog success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.$router.push({ name: 'pages-blog-list' })
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

        this.$nextTick(() => {
          this.$bvModal.hide('modal-danger')
        })
      })
    },
    cancelChange() {
      this.$router.push({ name: 'pages-blog-detail', params: { id: this.blogEdit.id } })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/quill.scss';
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
