<template>
  <b-tabs
    vertical
    content-class="col-12 col-md-9 mt-1 mt-md-0"
    pills
    nav-wrapper-class="col-md-3 col-12"
    nav-class="nav-left"
  >
    <!-- general tab -->
    <b-tab active>

      <!-- title -->
      <template #title>
        <feather-icon
          icon="GridIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Process</span>
      </template>

      <b-col cols="12">
        <b-row
          v-if="processList && processList.length"
          class="blog-list-wrapper match-height justify-content-center"
        >
          <b-col
            md="8"
            cols="12"
          >
            <b-link
              :to="{ name: 'pages-process-create', params: { idProject: id }}"
              class="font-weight-bold mb-2 "
            >
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-2 box-shadow-custom"
              >
                Create new Process
              </b-button>
            </b-link>
          </b-col>
          <app-timeline class="col-md-8 col-12">
            <app-timeline-item
              v-for="(process, index) in processList"
              :key="process.id"
            >
              <h3 class="mb-1">
                Process {{ index + 1 }}
              </h3>
              <b-card
                tag="article"
                no-body
                style="box-shadow: 0 4px 24px 0 rgb(34 41 47 / 10%)!important;"
              >
                <b-card-body>
                  <b-card-title>
                    <b-link
                      :to="{ name: 'pages-process-edit', params: { id: process.id, idProject: id } }"
                      class="process-title-truncate text-body-heading"
                    >
                      {{ process.title }}
                    </b-link>
                    <b-badge
                      :variant="statusColor(process.start_time, process.end_time)"
                      class="badge-glow"
                    >
                      {{ statusText(process.start_time, process.end_time) }}
                    </b-badge>
                  </b-card-title>
                  <b-media no-body>
                    <b-media-aside
                      vertical-align="center"
                      class="mr-50"
                    >
                      <b-avatar
                        href="javascript:void(0)"
                        size="24"
                        :src="process.user.avatar"
                      />
                    </b-media-aside>
                    <b-media-body>
                      <small class="text-muted mr-50">by</small>
                      <small>
                        <b-link class="text-body">{{ process.user.name }}</b-link>
                      </small>
                      <span class="text-muted ml-75 mr-50">|</span>
                      <small class="text-muted">{{ new Date(process.start_time).toDateString() }}</small>
                    </b-media-body>
                  </b-media>
                  <b-card-text class="blog-content-truncate tex-truncate mt-2">
                    <div
                      v-html="process.description"
                    />
                  </b-card-text>
                  <hr>
                  <div class="d-flex justify-content-between align-items-center">
                    <b-link
                      class="font-weight-bold mr-1"
                    >
                      <div class="d-inline-flex align-items-center text-secondary">
                        <feather-icon
                          icon="UsersIcon"
                          size="18"
                          class="mr-50"
                        />
                        <span>{{ process.total }}</span>
                      </div>
                    </b-link>
                    <b-link>
                      <div class="d-inline-flex align-items-center text-success">
                        <feather-icon
                          icon="CheckIcon"
                          size="18"
                          class="mr-50"
                        />
                        <span>{{ process.passed }}</span>
                      </div>
                    </b-link>
                    <b-link>
                      <div class="d-inline-flex align-items-center text-danger">
                        <feather-icon
                          icon="XIcon"
                          size="18"
                          class="mr-50"
                        />
                        <span>{{ process.failed }}</span>
                      </div>
                    </b-link>
                  </div>
                  <hr>
                  <div class="d-flex justify-content-between align-items-center">
                    <b-link
                      v-if="userOn && userOn.company_id === process.project.company_id"
                      :to="{ name: 'pages-process-edit', params: { id: process.id, idProject: id } }"
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
                      v-if="userOn && userOn.company_id === process.project.company_id"
                      @click.prevent="getProcessDelete(process.id)"
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
                </b-card-body>
              </b-card>
            </app-timeline-item>
          </app-timeline>
        </b-row>
        <b-row v-else>
          <b-col cols="12">
            <b-link
              :to="{ name: 'pages-process-create', params: { idProject: id }}"
              class="font-weight-bold mb-2"
            >
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-2 box-shadow-custom"
              >
                Create new Process
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
      </b-col>
    </b-tab>
    <!--/ general tab -->

    <b-tab>
      <!-- title -->
      <template #title>
        <feather-icon
          icon="FileTextIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">News</span>
      </template>

      <!-- blogs -->
      <b-col cols="12">
        <b-row
          v-if="newsList && newsList.length"
          class="blog-list-wrapper match-height"
        >
          <b-col cols="12">
            <b-link
              :to="{ name: 'pages-news-create', params: { idProject: id }}"
              class="font-weight-bold mb-2"
            >
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-2 box-shadow-custom"
              >
                Create new News
              </b-button>
            </b-link>
          </b-col>
          <b-col
            v-for="news in newsList"
            :key="news.id"
            md="6"
            cols="12"
          >
            <b-card
              tag="article"
              no-body
              style="box-shadow: 0 4px 24px 0 rgb(34 41 47 / 10%)!important;"
            >
              <b-card-body>
                <b-card-title>
                  <b-link
                    :to="{ name: 'pages-news-detail', params: { id: news.id } }"
                    class="news-title-truncate text-body-heading"
                  >
                    {{ news.title }}
                  </b-link>
                  <b-badge
                    :variant="statusColor(news.start_time, news.end_time)"
                    class="badge-glow"
                  >
                    {{ statusText(news.start_time, news.end_time) }}
                  </b-badge>
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
                    v-html="news.description"
                  />
                </b-card-text>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                  <b-link
                    v-if="userOn && userOn.company_id === news.project.company_id"
                    :to="{ name: 'pages-news-edit', params: { idProject: id, id: news.id } }"
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
                    v-if="userOn && userOn.company_id === news.project.company_id"
                    @click.prevent="getNewsDelete(news.id)"
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
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
        <b-row v-else>
          <b-col cols="12">
            <b-link
              :to="{ name: 'pages-news-create', params: { idProject: id }}"
              class="font-weight-bold mb-2"
            >
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mb-2 box-shadow-custom"
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
      </b-col>
    </b-tab>

    <b-modal
      id="modal-process"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Process"
      @ok="deleteProcess"
    >
      <b-card-text>
        Are you sure you want to delete this process?
      </b-card-text>
    </b-modal>

    <b-modal
      id="modal-news"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete news"
      @ok="deleteNews"
    >
      <b-card-text>
        Are you sure you want to delete this news?
      </b-card-text>
    </b-modal>
  </b-tabs>

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
  BButton,
  BTabs,
  BTab,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import AppTimeline from '@core/components/app-timeline/AppTimeline.vue'
import AppTimelineItem from '@core/components/app-timeline/AppTimelineItem.vue'
import process from '@/store/api/RProcess'
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
    BButton,
    BBadge,
    BTabs,
    BTab,

    AppTimeline,
    AppTimelineItem,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      processList: [],
      newsList: [],
      blogSidebar: [],
      idDelete_process: null,
      idDelete_news: null,
      userOn: {},
    }
  },
  created() {
    this.userOn = JSON.parse(localStorage.getItem('userData'))
    this.getDataProcess()
    this.getDataNews()
  },
  methods: {
    kFormatter,
    tagsColor(tag) {
      const color = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark']
      const rd = color[tag.length % color.length]
      return `light-${rd}`
    },
    getDataProcess() {
      process.getAll({
        project_id: this.id,
      }).then(resp => {
        const rs = resp.data
        this.processList = rs.data
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
        this.processList = null
      })
    },
    getDataNews() {
      news.showAllByProject(this.id).then(resp => {
        const rs = resp.data
        this.newsList = rs.data
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
        this.newsList = null
      })
    },
    deleteProcess(bvModalEvent) {
      bvModalEvent.preventDefault()

      process.delete(this.idDelete_process).then(resp => {
        const rs = resp.data
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete Process success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.processList = this.processList.filter(item => item.id !== this.idDelete_process)
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])

        this.$nextTick(() => {
          this.$bvModal.hide('modal-process')
        })
        this.idDelete_process = null
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
          this.$bvModal.hide('modal-process')
        })
      })
    },
    getProcessDelete(id) {
      this.idDelete_process = id
      this.$bvModal.show('modal-process')
    },
    deleteNews(bvModalEvent) {
      bvModalEvent.preventDefault()

      news.delete(this.idDelete_news).then(resp => {
        const rs = resp.data
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete News success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.newsList = this.newsList.filter(item => item.id !== this.idDelete_news)
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])

        this.$nextTick(() => {
          this.$bvModal.hide('modal-news')
        })
        this.idDelete_news = null
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
          this.$bvModal.hide('modal-news')
        })
      })
    },
    getNewsDelete(id) {
      this.idDelete_news = id
      this.$bvModal.show('modal-news')
    },
    statusColor(start, end) {
      let rd = 'warning'
      if (end) {
        rd = 'success'
      } else if (new Date(start) < new Date()) {
        rd = 'primary'
      } else {
        rd = 'warning'
      }

      return `light-${rd}`
    },
    statusText(start, end) {
      let rd = 'Upcoming'
      if (end) {
        rd = 'Completed'
      } else if (new Date(start) < new Date()) {
        rd = 'Ongoing'
      } else {
        rd = 'Upcoming'
      }

      return rd
    },
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/pages/page-blog.scss';

.box-shadow-custom {
  box-shadow: 0 4px 18px -4px rgb(115 103 240 / 65%)!important;
}
</style>
