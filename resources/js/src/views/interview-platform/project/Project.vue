<template>
  <!-- projects -->
  <b-row
    v-if="projects && projects.length"
    class="blog-list-wrapper match-height"
  >
    <b-col cols="12">
      <b-link
        :to="{ name: 'pages-project-create' }"
        class="font-weight-bold mb-2"
      >
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          class="mb-2"
        >
          Create new Project
        </b-button>
      </b-link>
    </b-col>
    <b-col
      v-for="project in projects"
      :key="project.id"
      md="6"
    >
      <b-card
        tag="article"
        no-body
      >
        <b-card-body class="d-flex flex-column justify-content-between">
          <b-card-title>
            <b-link
              :to="{ name: 'pages-project-detail', params: { id: project.id } }"
              class="project-title-truncate text-body-heading"
            >
              {{ project.title }}
            </b-link>
            <b-badge
              :variant="statusColor(project.start_time, project.end_time)"
              class="badge-glow"
            >
              {{ statusText(project.start_time, project.end_time) }}
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
                :src="project.user.avatar"
              />
            </b-media-aside>
            <b-media-body>
              <small class="text-muted mr-50">by</small>
              <small>
                <b-link class="text-body">{{ project.user.name }}</b-link>
              </small>
              <span class="text-muted ml-75 mr-50">|</span>
              <small class="text-muted">{{ new Date(project.start_time).toDateString() }}</small>
            </b-media-body>
          </b-media>
          <b-card-text class="blog-content-truncate tex-truncate mt-2">
            <div
              v-html="limitContent(project.description)"
            />
          </b-card-text>
          <div class="d-flex flex-wrap justify-content-between align-items-center">
            <hr class="w-100">
            <b-link
              v-if="userOn && project.company.id === userOn.company_id"
              @click.prevent="getProjectDelete(project.id)"
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
            <b-link
              :to="{ name: 'pages-project-detail', params: { id: project.id } }"
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

    <!--/ sidebar -->
    <b-modal
      id="modal-danger"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Alert"
      @ok="deleteProject"
    >
      <b-card-text>
        Are you sure you want to delete this blog?
      </b-card-text>
    </b-modal>
  </b-row>
  <b-row v-else>
    <b-col cols="12">
      <b-link
        :to="{ name: 'pages-project-create' }"
        class="font-weight-bold mb-2"
      >
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          class="mb-2"
        >
          Create new Project
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

  <!--/ projects -->
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
  BPaginationNav,
  BButton,
  BBadge,
} from 'bootstrap-vue'
import { kFormatter } from '@core/utils/filter'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import project from '@/store/api/Project'
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
    BPaginationNav,
    BButton,
    BBadge,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      projects: [],
      currentPage: 1,
      perPage: 8,
      rows: 100,
      userOn: {},
      idDelete: null,
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
    getData() {
      project.getAll({
        page: this.currentPage,
        per_page: this.perPage,
      }).then(resp => {
        const rs = resp.data
        this.projects = rs.data.data
        this.currentPage = rs.data.current_page
        this.rows = rs.data.last_page
        this.userOn = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.userOn = rs.user
      }).catch(err => {
        console.log(err)
        this.projects = null
      })
    },
    deleteProject(bvModalEvent) {
      bvModalEvent.preventDefault()

      project.delete(this.idDelete).then(resp => {
        const rs = resp.data
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Delete Project success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })

        this.projects = this.projects.filter(item => item.id !== this.idDelete)
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
    getProjectDelete(id) {
      this.idDelete = id
      this.$bvModal.show('modal-danger')
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
</style>
