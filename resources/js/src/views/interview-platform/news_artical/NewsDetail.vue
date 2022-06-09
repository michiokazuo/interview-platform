<template>
  <content-with-sidebar
    class="blog-wrapper"
  >
    <div
      v-if="newsDetail"
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
                :src="newsDetail.user.avatar"
                :text="(newsDetail.user.name || newsDetail.user.fullName)"
                :variant="`light-success`"
                size="104px"
                rounded
              />
              <div class="d-flex flex-column ml-1">
                <div class="mb-1">
                  <h4 class="mb-0">
                    {{ newsDetail.user.name || newsDetail.user.fullName }}
                  </h4>
                  <span class="card-text">{{ newsDetail.user.email }}</span>
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
                  {{ newsDetail.user.role || 'ROLE_COMPANY' }}
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
                  v-b-tooltip.hover.top="newsDetail.user.address"
                  class="pb-50 text-truncate"
                >
                  {{ newsDetail.user.address }}
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
                  {{ newsDetail.user.phone }}
                </td>
              </tr>
              <tr>
                <th>
                  <feather-icon
                    icon="ArchiveIcon"
                    class="mr-75"
                  />
                  <span class="font-weight-bold">Major</span>
                </th>
                <td>
                  {{ newsDetail.user.major }}
                </td>
              </tr>
            </table>
          </b-col>
        </b-row>
      </b-card>
      <b-row>
        <!-- news -->
        <b-col cols="12">
          <b-card
            img-alt="News Detail Pic"
            :title="newsDetail.title"
          >
            <b-link
              :to="{ name: 'pages-company-news-list', params: { id: newsDetail.user.company_id } }"
            >
              <b-media no-body>
                <b-media-aside
                  vertical-align="center"
                  class="mr-50"
                >
                  <b-avatar
                    href="javascript:void(0)"
                    size="24"
                    :src="newsDetail.user.avatar"
                  />
                </b-media-aside>
                <b-media-body>
                  <small class="text-muted mr-50">by</small>
                  <small>
                    <b-link class="text-body">{{ newsDetail.user.name }}</b-link>
                  </small>
                  <span class="text-muted ml-75 mr-50">|</span>
                  <small class="text-muted">{{ new Date(newsDetail.start_time).toDateString() }}</small>
                </b-media-body>
              </b-media>
            </b-link>
            <!-- eslint-disable vue/no-v-html -->
            <b-card
              class="bg-light mt-2"
              title="Description"
            >
              <div
                class="blog-content"
                v-html="newsDetail.description"
              />
            </b-card>

            <b-card
              class="bg-light"
              title="Benefits"
            >
              <div
                class="blog-content"
                v-html="newsDetail.benefits"
              />
            </b-card>
            <b-card
              v-if="newsDetail.requirements"
              class="bg-light"
              title="Requirements Others"
            >
              <div
                class="blog-content"
                v-html="newsDetail.requirements"
              />
            </b-card>

            <!-- eslint-enable -->
            <hr class="my-2">

            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div
                  v-if="userData && userData.candidate_id"
                  class="d-flex align-items-center mr-1"
                >
                  <b-link class="mr-50">
                    <feather-icon
                      icon="MessageSquareIcon"
                      size="21"
                      class="text-body"
                    />
                  </b-link>
                  <b-link>
                    <div
                      v-if="interviewOwner"
                      class="text-body"
                    >
                      <span v-if="interviewOwner.result && interviewOwner.result.company">You have already done interview this job</span>
                      <span v-else>You have already applied for this job</span>
                    </div>
                    <div
                      v-else
                      class="text-body"
                    >Not yet applied for this job</div>
                  </b-link>
                </div>
              </div>

              <!-- dropdown -->
              <div class="blog-detail-share">
                <b-link v-if="userData && userData.candidate_id">
                  <b-button
                    v-if="interviewOwner && interviewOwner.result && interviewOwner.result.company"
                    v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                    variant="outline-primary"
                  >
                    Done Interview
                  </b-button>
                  <span v-else-if="interviewOwner">
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      v-b-modal.modal-danger
                      variant="outline-danger"
                    >
                      Cancel Apply
                    </b-button>
                  </span>
                  <span v-else>
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      v-b-modal.modal-success
                      variant="outline-success"
                    >
                      Apply Now
                    </b-button>
                  </span>
                </b-link>
              </div>
            <!--/ dropdown -->
            </div>
          </b-card>
        </b-col>
      <!--/ news -->

      </b-row>
    <!--/ news -->

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

    <!-- sidebar -->
    <div
      v-if="newsDetail"
      slot="sidebar"
      class="blog-sidebar py-2 py-lg-0"
    >

      <!-- recent posts -->
      <div class="blog-recent-posts">
        <h6 class="section-label mb-75 font-weight-bolder">
          Information Recruitment News
        </h6>
        <b-card>
          <b-row>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-primary"
                rounded
              >
                <feather-icon
                  icon="UserIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.job_position }}
                </h5>
                <small>Job Position</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="DollarSignIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  ~${{ newsDetail.salary }}
                </h5>
                <small>Salary</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="FileTextIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.experience }}
                </h5>
                <small>Experience</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="UserCheckIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.gender }}
                </h5>
                <small>Gender</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="ClipboardIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.working_form }}
                </h5>
                <small>Working Form</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="UsersIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.number_of_recruits }}
                </h5>
                <small>Number of recruits</small>
              </div>
            </b-col>
            <b-col
              lg="12"
              cols="4"
              class="d-flex mb-2"
            >
              <b-avatar
                variant="light-success"
                rounded
              >
                <feather-icon
                  icon="MapPinIcon"
                  size="18"
                />
              </b-avatar>
              <div class="ml-1">
                <h5 class="mb-0">
                  {{ newsDetail.workplace }}
                </h5>
                <small>Workplace</small>
              </div>
            </b-col>
          </b-row>
        </b-card>
      </div>
      <b-modal
        id="modal-success"
        ok-only
        ok-variant="success"
        ok-title="Accept"
        modal-class="modal-success"
        centered
        title="Apply now"
        @ok="applyNow"
      >
        <b-card-text>
          Are you sure you want to apply this job?
        </b-card-text>
      </b-modal>

      <b-modal
        id="modal-danger"
        ok-only
        ok-variant="danger"
        ok-title="Accept"
        modal-class="modal-danger"
        centered
        title="Cancel apply"
        @ok="cancelApply"
      >
        <b-card-text>
          Are you sure you want to delete this job?
        </b-card-text>
      </b-modal>
      <!--/ recent posts -->
    </div>
  </content-with-sidebar>
</template>

<script>
import {
  BMedia,
  BAvatar,
  BMediaAside,
  BMediaBody,
  BLink,
  BCard,
  BRow,
  BCol,
  BCardText,
  BCardBody,
  BButton,
  BModal,
  VBTooltip,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { kFormatter } from '@core/utils/filter'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { required, email, password } from '@validations'
import ContentWithSidebar from '@core/layouts/components/content-with-sidebar/ContentWithSidebar.vue'
import news from '@/store/api/RNews'
import interview from '@/store/api/Interview'
import utils from '@/store/utils'

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
    BCardText,
    BCardBody,
    BButton,
    BModal,

    ContentWithSidebar,
  },
  directives: {
    'b-tooltip': VBTooltip,
    Ripple,
  },
  data() {
    return {
      id: null,
      newsDetail: null,
      commentUpdate: {
        content: '',
        id: null,
      },
      commentStore: '',
      userData: null,
      interviewOwner: null,
    }
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id
      this.getData()
      this.getInterviewOwner()
    } else {
      this.id = null
      this.newsDetail = null
      this.interviewOwner = null
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
      news.findById(this.id).then(resp => {
        const rs = resp.data
        this.newsDetail = rs.data
        this.userData = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.newsDetail = null
      })
    },
    getInterviewOwner() {
      interview.findByNewsId(this.id).then(resp => {
        const rs = resp.data
        this.interviewOwner = rs.data
        this.userData = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.interviewOwner = null
      })
    },
    applyNow() {
      interview.store({
        candidate_id: this.userData.candidate_id,
        news_id: this.id,
        company_id: this.newsDetail.company.id,
      }).then(resp => {
        const rs = resp.data
        this.interviewOwner = rs.data
        this.userData = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Apply job success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })
        this.$nextTick(() => {
          this.$bvModal.hide('modal-success')
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
          this.$bvModal.hide('modal-success')
        })
      })
    },
    cancelApply() {
      interview.delete(this.interviewOwner.id).then(resp => {
        const rs = resp.data
        this.interviewOwner = null
        this.userData = rs.user
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: 'all',
            // subject: userData.role,
          },
        ])
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: 'Cancel apply success',
            icon: 'CoffeeIcon',
            variant: 'success',
          },
        })
        this.$nextTick(() => {
          this.$bvModal.hide('modal-danger')
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
          this.$bvModal.hide('modal-danger')
        })
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/pages/page-blog.scss';
</style>
