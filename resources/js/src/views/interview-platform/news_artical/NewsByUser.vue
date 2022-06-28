<template>
  <div
    v-if="id && userData"
    class="news-detail-wrapper"
  >
    <b-card>
      <b-row>
        <!-- User Info: Left col -->
        <b-col
          cols="21"
          xl="6"
          class="d-flex justify-content-center flex-column"
        >
          <!-- User Avatar & Action Buttons -->
          <div class="d-flex justify-content-start align-items-center">
            <b-avatar
              :src="userData.avatar"
              :text="(userData.name || userData.fullName)"
              :variant="`light-success`"
              size="104px"
              rounded
            />
            <div class="d-flex flex-column ml-1">
              <div class="mb-1">
                <h4 class="mb-0">
                  {{ userData.name }}
                </h4>
                <span class="card-text">{{ userData.email }}</span>
              </div>
            </div>
          </div>
        </b-col>

        <!-- Right Col: Table -->
        <b-col
          cols="12"
          xl="6"
        >
          <table class="mt-2 mt-xl-0 w-100">
            <tr>
              <th class="pb-50">
                <feather-icon
                  icon="CheckIcon"
                  class="mr-75"
                />
                <span class="font-weight-bold">Status</span>
              </th>
              <td class="pb-50 text-capitalize">
                Active
              </td>
            </tr>
            <tr>
              <th class="pb-50">
                <feather-icon
                  icon="StarIcon"
                  class="mr-75"
                />
                <span class="font-weight-bold">Role</span>
              </th>
              <td class="pb-50 text-capitalize">
                {{ userData.role.name }}
              </td>
            </tr>
            <tr>
              <th class="pb-50">
                <feather-icon
                  icon="FlagIcon"
                  class="mr-75"
                />
                <span class="font-weight-bold">Address</span>
              </th>
              <td
                v-b-tooltip.hover.top="userData.address"
                class="pb-50 text-truncate"
              >
                {{ userData.address }}
              </td>
            </tr>
            <tr>
              <th class="pb-50">
                <feather-icon
                  icon="PhoneIcon"
                  class="mr-75"
                />
                <span class="font-weight-bold">Contact</span>
              </th>
              <td class="pb-50">
                {{ userData.phone }}
              </td>
            </tr>
            <tr>
              <th class="pb-50">
                <feather-icon
                  icon="ArchiveIcon"
                  class="mr-75"
                />
                <span class="font-weight-bold">Major</span>
              </th>
              <td class="pb-50">
                {{ userData.major }}
              </td>
            </tr>
          </table>
        </b-col>
      </b-row>
    </b-card>
    <b-card
      v-if="userData.introduction"
      title="Description"
    >
      <b-row>
        <!-- User Info: Left col -->
        <b-col
          cols="12"
        >
          <div
            class="blog-content"
            v-html="userData.introduction"
          />
        </b-col>
      </b-row>
    </b-card>
    <template>
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
                    <b-link class="text-body">{{ news.user.name }}</b-link>
                  </small>
                  <span class="text-muted ml-75 mr-50">|</span>
                  <small class="text-muted">{{ new Date(news.start_time).toDateString() }}</small>
                </b-media-body>
              </b-media>
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
                <div class="d-flex align-items-center mr-1" />
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
            :to="{ name: 'pages-news-create' }"
            class="font-weight-bold mb-2"
          >
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mb-2"
            >
              Create new News
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

    </template>
  </div>
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
  VBTooltip,
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
    BButton,
  },
  directives: {
    'b-tooltip': VBTooltip,
    Ripple,
  },
  data() {
    return {
      id: null,
      userData: null,
      newsList: [],
      currentPage: 1,
      perPage: 8,
      rows: 100,
    }
  },
  watch: {
    currentPage() {
      this.getData()
    },
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
    }
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
      news.showAllByUser(this.id, {
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data
        this.newsList = rs.data.data
        this.currentPage = rs.data.current_page
        this.rows = rs.data.last_page
        this.userData = rs.data.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
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
