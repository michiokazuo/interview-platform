<template>
  <!-- blogs -->
  <div>
    <b-row class="mb-2">
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
        <b-card title="Search">
          <b-row>
            <b-col
              cols="12"
              class="mb-1"
            >
              <b-input-group class="input-group-merge">
                <b-form-input
                  v-model="keyword"
                  placeholder="Search keyword"
                  class="search-product"
                />
                <b-input-group-append is-text>
                  <feather-icon
                    icon="SearchIcon"
                    class="text-muted"
                  />
                </b-input-group-append>
              </b-input-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col
              cols="12"
              class="d-flex justify-content-between align-items-center"
            >
              <div class="search-results">
                <strong>{{ total }}</strong> results found
              </div>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="outline-primary"
                @click.prevent="searchNews"
              >
                Search News
              </b-button>
            </b-col>
          </b-row>
        </b-card>
      </b-col>
      <b-col cols="12">
        <div class="d-flex justify-content-end">
          <div class="view-options d-flex">
            <!-- Sort Button -->
            <b-dropdown
              v-ripple.400="'rgba(113, 102, 240, 0.15)'"
              :text="sortBy.text"
              right
              variant="outline-primary"
            >
              <b-dropdown-item
                v-for="sortOption in sortByOptions"
                :key="sortOption.value"
                @click="changeSortOption(sortOption)"
              >
                {{ sortOption.text }}
              </b-dropdown-item>
            </b-dropdown>
          </div>
        </div>
      </b-col>
    </b-row>
    <b-row
      v-if="blogList && blogList.length"
      class="blog-list-wrapper match-height"
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
                v-html="limitContent(blog.content)"
              />
            </b-card-text>
            <div class="d-flex flex-wrap justify-content-between align-items-center">
              <hr class="w-100">
              <b-link :to="{ path: `/user/blog/${blog.id}#blogComment`}">
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
  </div>
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
  BInputGroup,
  BFormInput,
  BInputGroupAppend,
  BDropdown,
  BDropdownItem,
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
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BDropdown,
    BDropdownItem,
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
      sortByOptions: [
        { text: 'Newest date', value: 'created_at-desc' },
        { text: 'Oldest date', value: 'created_at-asc' },
      ],
      sortBy: {},
      keyword: null,
      total: 0,
    }
  },
  watch: {
    currentPage() {
      this.getData()
    },
  },
  created() {
    this.sortBy = { ...this.sortByOptions[0] }
    this.getData()
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
      blog.getAll({
        page: this.currentPage,
        per_page: this.perPage,
        sort_by: this.sortBy?.value,
        keyword: this.keyword?.trim() ?? null,
      }).then(resp => {
        const rs = resp.data
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.blogList = rs.data.data
        this.currentPage = rs.data.current_page
        this.rows = rs.data.last_page
        this.total = rs.data.total
      }).catch(err => {
        console.log(err)
        this.blogList = null
      })
    },
    changeSortOption(option) {
      if (this.sortBy.value !== option.value) {
        this.sortBy = option
        this.getData()
      }
    },
    searchNews() {
      this.getData()
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
