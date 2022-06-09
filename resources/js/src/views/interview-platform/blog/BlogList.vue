<template>
  <!-- blogs -->
  <b-row
    v-if="blogList && blogList.length"
    class="blog-list-wrapper"
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
        <b-card-body>
          <b-card-title>
            <b-link
              :to="{ name: 'pages-blog-detail', params: { id: blog.id } }"
              class="blog-title-truncate text-body-heading"
            >
              {{ blog.title }}
            </b-link>
          </b-card-title>
          <b-link
            :to="{ name: 'pages-another-user-blog-list', params: { id: blog.user.id } }"
          >
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
                  <span class="text-primary cursor-pointer">{{ blog.user.name }}</span>
                </small>
                <span class="text-muted ml-75 mr-50">|</span>
                <small class="text-muted">{{ new Date(blog.created_at).toDateString() }}</small>
              </b-media-body>
            </b-media>
          </b-link>
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
              v-html="blog.content"
            />
          </b-card-text>
          <hr>
          <div class="d-flex justify-content-between align-items-center">
            <b-link :to="{ path: `/pages/blog/${blog.id}#blogComment`}">
              <div class="d-flex align-items-center text-body">
                <feather-icon
                  icon="MessageSquareIcon"
                  class="mr-50"
                />
                <span class="font-weight-bold">{{ kFormatter(blog.comments) }} Comments</span>
              </div>
            </b-link>
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

  <!--/ blogs -->
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
import Ripple from 'vue-ripple-directive'
import blog from '@/store/api/Blog'
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
      rows: 1,
    }
  },
  watch: {
    currentPage() {
      this.getData()
    },
  },
  created() {
    this.getData()
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
      blog.getAll({
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
        this.blogList = rs.data.data
        this.currentPage = rs.data.current_page
        this.rows = rs.data.last_page
      }).catch(err => {
        console.log(err)
        this.blogList = null
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
