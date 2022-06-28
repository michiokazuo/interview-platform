<template>
  <div
    v-if="id && userData"
    class="blog-detail-wrapper"
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
    <template>
      <b-row
        v-if="blogList && blogList.length"
        class="blog-list-wrapper"
      >
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
                    <b-link class="text-body">{{ blog.user.name }}</b-link>
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
                <div class="d-flex align-items-center mr-1" />
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
    'b-tooltip': VBTooltip,
    Ripple,
  },
  data() {
    return {
      id: null,
      userData: null,
      blogList: [],
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
    this.limitContent = utils.limitContent
    if (id) {
      this.id = id
      this.getData()
    } else {
      this.id = null
    }
  },
  methods: {
    kFormatter,
    tagsColor(tag) {
      
      const color = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark']
      const rd = color[tag.length % color.length]
      return `light-${rd}`
    },
    getData() {
      blog.showByUser(this.id, {
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data
        this.blogList = rs.data.data
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
        this.blogList = null
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
