<template>

  <div
    v-if="blogDetail"
    class="blog-detail-wrapper"
  >
    <b-row>
      <!-- blogs -->
      <b-col cols="12">
        <b-card
          img-alt="Blog Detail Pic"
          :title="blogDetail.title"
        >
          <b-media no-body>
            <b-media-aside
              vertical-align="center"
              class="mr-50"
            >
              <b-avatar
                href="javascript:void(0)"
                size="24"
                :src="blogDetail.user.avatar"
              />
            </b-media-aside>
            <b-media-body>
              <small class="text-muted mr-50">by</small>
              <small>
                <b-link class="text-body">{{ blogDetail.user.name }}</b-link>
              </small>
              <span class="text-muted ml-75 mr-50">|</span>
              <small class="text-muted">{{ blogDetail.created_at }}</small>
            </b-media-body>
          </b-media>
          <div class="my-1 py-25">
            <b-link
              v-for="tag in blogDetail.topics.split(',')"
              :key="tag"
            >
              <b-badge
                v-if="tag && tag.length"
                pill
                class="mr-75"
                :variant="tagsColor(tag.trim())"
              >
                {{ tag.trim() }}
              </b-badge>
            </b-link>
          </div>
          <!-- eslint-disable vue/no-v-html -->
          <div
            class="blog-content"
            v-html="blogDetail.content"
          />

          <!-- eslint-enable -->
          <hr class="my-2">

          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="d-flex align-items-center mr-1">
                <b-link class="mr-50">
                  <feather-icon
                    icon="MessageSquareIcon"
                    size="21"
                    class="text-body"
                  />
                </b-link>
                <b-link>
                  <div class="text-body">
                    {{ kFormatter(blogDetail.comments.length) }}
                  </div>
                </b-link>
              </div>
            </div>

            <!-- dropdown -->
            <div class="blog-detail-share" >
              <b-link
                v-if="userOn && userOn.id === blogDetail.user.id"
                :to="{ name: 'pages-blog-edit', params: { id: blogDetail.id } }"
              >
                <div class="d-inline-flex align-items-center text-primary">
                  <feather-icon
                    icon="EditIcon"
                    size="18"
                    class="mr-50"
                  />
                  <span>Edit</span>
                </div>
              </b-link>
            </div>
            <!--/ dropdown -->
          </div>
        </b-card>
      </b-col>
      <!--/ blogs -->

      <!-- blog comment -->
      <b-col
        id="blogComment"
        cols="12"
        class="mt-2"
      >
        <h6 class="section-label">
          Comment
        </h6>
        <b-card
          v-for="(comment,index) in blogDetail.comments"
          :key="index"
        >
          <b-media no-body>
            <b-media-aside class="mr-75">
              <b-avatar
                :src="comment.user.avatar"
                size="38"
              />
            </b-media-aside>
            <b-media-body>
              <h6 class="font-weight-bolder mb-25">
                {{ comment.user.name }}
              </h6>
              <b-card-text>{{ new Date(comment.created_at).toDateString() }}</b-card-text>
              <b-card-text>
                {{ comment.content }}
              </b-card-text>
              <b-link
                v-if="userOn && userOn.id === comment.user.id"
                @click.prevent="getCommentChange(comment, 'update')"
              >
                <div class="d-inline-flex align-items-center text-primary">
                  <feather-icon
                    icon="EditIcon"
                    size="18"
                    class="mr-50"
                  />
                  <span>Edit</span>
                </div>
              </b-link>
              <b-link
                v-if="userOn && userOn.id === comment.user.id"
                @click.prevent="getCommentChange(comment, 'delete')"
              >
                <div class="d-inline-flex align-items-center text-danger">
                  <feather-icon
                    icon="Trash2Icon"
                    size="18"
                    class="mr-50"
                  />
                  <span>Delete</span>
                </div>
              </b-link>
            </b-media-body>
          </b-media>
        </b-card>
      </b-col>
      <!--/ blog comment -->

      <!-- Leave a Blog Comment -->
      <b-col
        cols="12"
        class="mt-2"
      >
        <h6 class="section-label">
          Leave a Comment
        </h6>
        <b-card>
          <validation-observer
            ref="commentForm"
            #default="{invalid}"
          >
            <b-form @submit.prevent="saveComment">
              <b-row>
                <b-col cols="12">
                  <b-form-group class="mb-2">
                    <validation-provider
                      #default="{ errors }"
                      name="Content"
                      vid="content"
                      rules="required"
                    >
                      <b-form-textarea
                        id="content"
                        v-model="commentStore"
                        name="textarea"
                        rows="3"
                        placeholder="Content here"
                        :state="errors.length > 0 ? false:null"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col cols="12">
                  <b-button
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="primary"
                    type="submit"
                    :disabled="invalid"
                  >
                    Post Comment
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
          </validation-observer>
        </b-card>
      </b-col>
      <!--/ Leave a Blog Comment -->
    </b-row>
    <!--/ blogs -->

    <!-- modal login-->
    <b-modal
      id="modal-update"
      cancel-variant="outline-secondary"
      ok-title="Accept"
      cancel-title="Close"
      centered
      title="Update comment"
      @ok="updateComment"
    >
      <validation-observer
        ref="commentFormUpdate"
      >
        <b-form>
          <b-form-group>
            <label for="email">Content:</label>
            <validation-provider
              #default="{ errors }"
              name="Content"
              vid="content"
              rules="required"
            >

              <b-form-textarea
                id="comment-update"
                v-model="commentUpdate.content"
                placeholder="Content"
                rows="3"
                :state="errors.length > 0 ? false:null"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group></b-form>
      </validation-observer>
    </b-modal>

    <b-modal
      id="modal-delete"
      ok-only
      ok-variant="danger"
      ok-title="Accept"
      modal-class="modal-danger"
      centered
      title="Delete Comment"
      @ok="deleteComment"
    >
      <b-card-text>
        Are you sure you want to delete this comment?
      </b-card-text>
    </b-modal>
  </div>
  <!--/ content -->
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
  BMedia,
  BAvatar,
  BMediaAside,
  BMediaBody,
  BLink,
  BFormGroup,
  BCard,
  BRow,
  BCol,
  BBadge,
  BCardText,
  BForm,
  BFormTextarea,
  BButton,
  BModal,
  BCardBody,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { kFormatter } from '@core/utils/filter'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { required, email, password } from '@validations'
import blog from '@/store/api/Blog'
import comment from '@/store/api/Comment'

export default {
  components: {
    BMedia,
    BAvatar,
    BMediaAside,
    BMediaBody,
    BLink,
    BCard,
    BRow,
    BCol,
    BFormGroup,
    BBadge,
    BCardText,
    BForm,
    BFormTextarea,
    BButton,
    ValidationProvider,
    ValidationObserver,
    BModal,
    BCardBody,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      id: null,
      blogDetail: null,
      commentUpdate: {
        content: '',
        id: null,
      },
      commentStore: '',
      userOn: null,
    }
  },
  created() {
    this.userOn = JSON.parse(localStorage.getItem('userData'))
    const { id } = this.$route.params
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
      this.blogDetail = null
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
      blog.findById(this.id).then(resp => {
        const rs = resp.data
        this.blogDetail = rs.data
      }).catch(err => {
        console.log(err)
        this.blogDetail = null
      })
    },
    saveComment() {
      this.$refs.commentForm.validate().then(success => {
        if (success) {
          comment.store({
            blog_id: this.blogDetail.id,
            content: this.commentStore,
          }).then(resp => {
            const rs = resp.data
            this.blogDetail.comments.unshift(rs.data)
            this.$refs.commentForm.reset()
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Create comment success',
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
                text: 'Something error. Please try again!!!',
              },
            })
          })
        }
      })
    },
    updateComment(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.$refs.commentFormUpdate.validate().then(success => {
        if (success) {
          comment.update(this.commentUpdate.id, {
            blog_id: this.blogDetail.id,
            content: this.commentUpdate.content,
          }).then(resp => {
            const rs = resp.data
            const index = this.blogDetail.comments.findIndex(item => rs.data.id === item.id)
            this.blogDetail.comments = [
              ...this.blogDetail.comments.slice(0, index),
              rs.data,
              ...this.blogDetail.comments.slice(index + 1),
            ]
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Update Blog success',
                icon: 'CoffeeIcon',
                variant: 'success',
              },
            })

            this.$nextTick(() => {
              this.$bvModal.hide('modal-update')
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
                text: 'Something error. Please try again!!!',
              },
            })

            this.$nextTick(() => {
              this.$bvModal.hide('modal-update')
            })
          })
        }
      })
    },
    deleteComment(bvModalEvent) {
      bvModalEvent.preventDefault()

      comment.delete(this.commentUpdate.id).then(resp => {
        console.log(resp)
        this.blogDetail.comments = this.blogDetail.comments.filter(item => item.id !== this.commentUpdate.id)
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete comment success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })
        this.$nextTick(() => {
          this.$bvModal.hide('modal-delete')
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
            text: 'Something error. Please try again!!!',
          },
        })

        this.$nextTick(() => {
          this.$bvModal.hide('modal-delete')
        })
      })
    },
    // eslint-disable-next-line no-shadow
    getCommentChange(comment, method) {
      this.commentUpdate = { ...comment }
      this.$nextTick(() => {
        this.$bvModal.show(`modal-${method}`)
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
