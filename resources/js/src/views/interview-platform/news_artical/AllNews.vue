<template>
  <!-- newss -->
  <b-row
    v-if="newsList && newsList.length"
    class="news-list-wrapper match-height"
  >
    <b-col
      v-for="news in newsList"
      :key="news.id"
      md="6"
    >
      <b-card
        tag="article"
        no-body
      >
        <b-card-body class="d-flex flex-column justify-content-between">
          <b-card-title>
            <b-link
              :to="{ name: 'pages-news-detail', params: { id: news.id } }"
              class="news-title-truncate text-body-heading"
            >
              {{ news.title }}
            </b-link>
          </b-card-title>
          <b-link
            :to="{ name: 'pages-company-news-list', params: { id: news.user.company_id } }"
          >
            <b-media no-body>
              <b-media-aside
                vertical-align="center"
                class="mr-50"
              >
                <b-avatar
                  href="javascript:void(0)"
                  size="24"
                  :src="news.user.avatar"
                />
              </b-media-aside>
              <b-media-body>
                <small class="text-muted mr-50">by</small>
                <small>
                  <span class="text-primary cursor-pointer">{{ news.user.name }}</span>
                </small>
                <span class="text-muted ml-75 mr-50">|</span>
                <small class="text-muted">{{ new Date(news.start_time).toDateString() }}</small>
              </b-media-body>
            </b-media>
          </b-link>
          <div class="my-1 py-25">
            <b-link>
              <b-badge
                pill
                class="mr-75"
                :variant="tagsColor(news.job_position)"
              >
                {{ news.job_position }}
              </b-badge>
            </b-link>
          </div>
          <b-card-text class="blog-content-truncate tex-truncate">
            <div
              v-html="limitContent(news.description)"
            />
          </b-card-text>
          <div class="d-flex flex-wrap justify-content-between align-items-center">
            <hr class="w-100">
            <b-link :to="{ path: `/pages/news/${news.id}#newsComment`}">
              <div class="d-flex align-items-center text-body">
                <feather-icon
                  icon="MessageSquareIcon"
                  class="mr-50"
                />
              </div>
            </b-link>
            <b-link
              :to="{ name: 'pages-news-detail', params: { id: news.id } }"
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

  <!--/ newss -->
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
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import news from '@/store/api/RNews'
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
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      newsList: [],
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
    this.limitContent = utils.limitContent
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
      news.getAll({
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.newsList = rs.data.data
        this.currentPage = rs.data.current_page
        this.rows = rs.data.last_page
      }).catch(err => {
        console.log(err)
        this.newsList = null
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
