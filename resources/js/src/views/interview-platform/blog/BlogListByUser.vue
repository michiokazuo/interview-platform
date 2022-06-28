<template>
  <content-with-sidebar
    class="blog-wrapper"
  >
    <!-- blogs -->
    <b-row
      v-if="blogList && blogList.length"
      class="blog-list-wrapper match-height"
    >
      <b-col cols="12">
        <b-link
          :to="{ name: 'pages-blog-create' }"
          class="font-weight-bold mb-2"
        >
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="mb-2"
          >
            Create new Blog
          </b-button>
        </b-link>
      </b-col>
      <b-col
        v-for="blog in blogList"
        :key="blog.id"
        md="6"
      >
        <b-card
          tag="article"
          no-body
        >
          <b-card-body class="d-flex flex-column justify-content-between">
            <b-card-title>
              <b-link
                :to="{ name: 'pages-blog-detail', params: { id: blog.id } }"
                class="blog-title-truncate text-body-heading"
              >
                {{ blog.title }}
              </b-link>
            </b-card-title>
            <b-media no-body>
              <b-media-aside
                vertical-align="center"
                class="mr-50"
              >
                <b-avatar
                  href="javascript:void(0)"
                  size="24"
                  :src="blog.user.avatar"
                />
              </b-media-aside>
              <b-media-body>
                <small class="text-muted mr-50">by</small>
                <small>
                  <b-link class="text-body">{{ blog.user.name || blog.user.fullName }}</b-link>
                </small>
                <span class="text-muted ml-75 mr-50">|</span>
                <small class="text-muted">{{ blog.created_at }}</small>
              </b-media-body>
            </b-media>
            <div class="my-1 py-25">
              <b-link
                v-for="(tag,index) in blog.topics.split(',')"
                :key="index"
              >
                <b-badge
                  pill
                  class="mr-75"
                  :variant="tagsColor(tag)"
                >
                  {{ tag }}
                </b-badge>
              </b-link>
            </div>
            <b-card-text class="blog-content-truncate text-truncate">
              <div
                v-html="limitContent(blog.content)"
              />
            </b-card-text>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <hr class="w-100">
              <div class="d-flex align-items-center mr-1">
                <b-link
                  v-if="userOn && blog.user.id === userOn.id"
                  :to="{ name: 'pages-blog-edit', params: { id: blog.id } }"
                  class="font-weight-bold mr-1"
                >
                  <div class="d-inline-flex align-items-center text-primary">
                    <feather-icon
                      icon="Edit3Icon"
                      size="18"
                      class="mr-50"
                    />
                    <span>Edit</span>
                  </div>
                </b-link>
                <b-link
                  v-if="userOn && blog.user.id === userOn.id"
                  @click.prevent="getBlogDelete(blog.id)"
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
              </div>
              <b-link
                :to="{ name: 'pages-blog-detail', params: { id: blog.id } }"
                class="font-weight-bold"
              >
                Read More
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
    <b-row v-else>
      <b-col cols="12">
        <b-link
          :to="{ name: 'pages-blog-create' }"
          class="font-weight-bold mb-2"
        >
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="mb-2"
          >
            Create new Blog
          </b-button>
        </b-link>
      </b-col>
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

    <!-- sidebar -->
    <div
      slot="sidebar"
      class="blog-sidebar py-2 py-lg-0"
    >
      <!-- recent posts -->
      <div class="blog-recent-posts">
        <h6 class="section-label mb-75">
          Recent Comment on Posts
        </h6>
        <b-card
          v-for="(recentpost) in blogSidebar"
          :key="recentpost.id"
        >
          <b-link
            :to="{ name: 'pages-blog-detail', params:{ id :recentpost.blog.id } }"
            class="text-body-heading"
          >
            <b-card-title class="m-0">
              <span>
                {{ recentpost.blog.title }}
              </span>
            </b-card-title>
            <b-media
              no-body
              class="flex-wrap"
            >
              <b-media-aside
                vertical-align="center"
                class="mr-50"
              >
                <b-avatar
                  href="javascript:void(0)"
                  size="24"
                  :src="recentpost.user.avatar"
                />
              </b-media-aside>
              <b-media-body>
                <small class="text-muted mr-50">by</small>
                <small>
                  <b-link class="text-body">{{ recentpost.user.name }}</b-link>
                </small>
                <br>
                <small class="text-muted">{{ new Date(recentpost.created_at).toDateString() }}</small>
              </b-media-body>
            </b-media>
          </b-link>
        </b-card>
        <b-media v-if="!blogSidebar || !blogSidebar.length">
          <b-media-body>
            <span class="text-muted mb-0 text-center">
              Nothing to show here
            </span>
          </b-media-body>
        </b-media>

      </div>
      <!--/ recent posts -->
    </div>
    <!--/ sidebar -->
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
  <!--/ blogs -->
  </content-with-sidebar>
</template>

<script>
import {
  BRow,
  BCol,
  BCard,
  BCardText,
  BCardTitle,
  BMedia,
  BAvatar,
  BMediaAside,
  BMediaBody,
  BCardBody,
  BLink,
  BBadge,
  BPaginationNav,
  BButton,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import ContentWithSidebar from '@core/layouts/components/content-with-sidebar/ContentWithSidebar.vue'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import blog from '@/store/api/Blog'
import comment from '@/store/api/Comment'
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
    BAvatar,
    BMediaAside,
    BMediaBody,
    BLink,
    BBadge,
    BPaginationNav,
    ContentWithSidebar,
    BButton,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      blogList: [],
      currentPage: 1,
      perPage: 8,
      rows: 100,
      blogSidebar: [],
      idDelete: null,
      userOn: {},
    }
  },
  watch: {
    currentPage() {
      this.getData()
    },
  },
  created() {
    this.userOn = JSON.parse(localStorage.getItem('userData'))
    this.getData()
    this.getDataComment()
    this.limitContent = utils.limitContent
  },
  methods: {
    kFormatter,
    tagsColor(tag) {
      
      const color = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark']
      const rd = color[tag.length % color.length]
      return `light-${rd}`
    },
    getData() {
      blog.showByUser(this.userOn.id, {
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data.data
        this.blogList = rs.data
        this.currentPage = rs.current_page
        this.rows = rs.last_page
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
        this.blogList = null
      })
    },
    getDataComment() {
      comment.showByUser().then(resp => {
        const rs = resp.data
        this.blogSidebar = rs.data.slice(0, 6)
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
        this.blogSidebar = null
      })
    },
    deleteBlog(bvModalEvent) {
      bvModalEvent.preventDefault()

      blog.delete(this.idDelete).then(resp => {
        const rs = resp.data
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete Blog success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.blogList = this.blogList.filter(item => item.id !== this.idDelete)
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])

        this.$nextTick(() => {
          this.$bvModal.hide('modal-danger')
        })
        this.idDelete = null
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
    getBlogDelete(id) {
      this.idDelete = id
      this.$bvModal.show('modal-danger')
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
