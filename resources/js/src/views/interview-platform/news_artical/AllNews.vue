<template>
  <!-- news list -->
  <div>
    <b-row class="mb-2">
      <b-col cols="12">
        <b-card title="Search">
          <b-row>
            <b-col
              lg="6"
              cols="12"
              class="mb-1"
            >
              <b-input-group class="input-group-merge">
                <b-form-input
                  v-model="filters.keyword"
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
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_job"
                v-model="filters.job_positions"
                multiple
                taggable
                name="job_position"
                placeholder="Job Position"
                :options="selectOptions.job_positions"
              />
            </b-col>
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_salary"
                v-model="filters.salaries"
                multiple
                taggable
                name="salary"
                placeholder="Salary"
                :options="selectOptions.salaries"
              />
            </b-col>
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_experience"
                v-model="filters.experiences"
                multiple
                taggable
                name="experience"
                placeholder="Experience"
                :options="selectOptions.experiences"
              />
            </b-col>
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_workplace"
                v-model="filters.workplaces"
                multiple
                taggable
                name="workplace"
                placeholder="Workplace"
                :options="selectOptions.workplaces"
              />
            </b-col>
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_gender"
                v-model="filters.genders"
                multiple
                taggable
                name="gender"
                placeholder="Gender"
                :options="optionsGender"
              />
            </b-col>
            <b-col
              md="4"
              sm="6"
              lg="3"
              cols="12"
              class="mb-1"
            >
              <v-select
                id="search_working_forms"
                v-model="filters.working_forms"
                multiple
                taggable
                name="working_form"
                placeholder="Working Form"
                :options="optionsWorkingForm"
              />
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

  <!--/ news -->
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
  BInputGroup,
  BFormInput,
  BInputGroupAppend,
  BButton,
  BDropdown,
  BDropdownItem,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
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
    BInputGroup,
    BFormInput,
    BInputGroupAppend,
    BButton,
    BDropdown,
    BDropdownItem,
    vSelect,
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
      total: 0,
      filters: {},
      optionsWorkingForm: ['Remote', 'Full time', 'Part time', 'Freelancer'],
      optionsGender: ['None', 'Male', 'Female'],
      selectOptions: {
        job_positions: [],
        salaries: [],
        workplaces: [],
        experiences: [],
      },
      sortByOptions: [
        { text: 'Newest date', value: 'start_time-desc' },
        { text: 'Oldest date', value: 'start_time-asc' },
      ],
      sortBy: {},
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
    this.getSelection()
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
      const dataFilters = {}
      // eslint-disable-next-line no-restricted-syntax
      for (const key in this.filters) {
        if (Object.prototype.hasOwnProperty.call(this.filters, key) && this.filters[key]) {
          if (typeof this.filters[key] === 'string') {
            dataFilters[key] = this.filters[key].trim() ?? null
          } else if (Array.isArray(this.filters[key]) && this.filters[key].length) {
            const filterTmp = this.filters[key].map(item => item.trim()).filter(item => item.length)
            if (filterTmp.length) {
              dataFilters[key] = filterTmp
            }
          }
        }
      }

      news.getAll({
        page: this.currentPage,
        per_page: this.perPage,
        ...dataFilters,
        sort_by: this.sortBy?.value,
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
        this.total = rs.data.total
      }).catch(err => {
        console.log(err)
        this.newsList = null
      })
    },
    getSelection() {
      news.getSelectOptions().then(resp => {
        const rs = resp.data
        this.selectOptions = rs.data
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
        this.newsEdit = null
      })
    },
    searchNews() {
      this.getData()
    },
    changeSortOption(option) {
      if (this.sortBy.value !== option.value) {
        this.sortBy = option
        this.getData()
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
