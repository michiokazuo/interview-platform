<template>
  <b-card>

    <!-- input search -->
    <div class="custom-search d-flex justify-content-end">
      <b-form-group>
        <div class="d-flex align-items-center">
          <label class="mr-1 mb-0 font-weight-bold h4">Search</label>
          <b-form-input
            v-model="searchTerm"
            placeholder="Search"
            type="text"
            class="d-inline-block"
          />
        </div>
      </b-form-group>
    </div>

    <!-- table -->
    <vue-good-table
      :columns="columns"
      :rows="rows"
      :rtl="direction"
      :search-options="{
        enabled: true,
        externalQuery: searchTerm }"
      :pagination-options="{
        enabled: true,
        perPage:pageLength
      }"
    >
      <template
        slot="table-row"
        slot-scope="props"
      >

        <!-- Column: Name -->
        <div
          v-if="props.column.field === 'candidate.general.name'"
          class="text-nowrap"
        >
          <b-avatar
            :src="props.row.candidate.general.avatar"
            class="mx-1"
          />
          <span class="text-nowrap">
            <b-button
              size="sm"
              variant="flat-primary"
              @click.prevent="viewCandidate(props.row.id)"
            >
              <feather-icon
                icon="EyeIcon"
                class="mr-50"
              />
              <span>{{ props.row.candidate.general.name }}</span>
            </b-button>
          </span>
        </div>

        <span
          v-else-if="props.column.field === 'time'"
          class="text-nowrap"
        >{{ props.row.time || 'Not set' }}</span>

        <span
          v-else-if="props.column.field === 'form'"
          class="text-nowrap"
        >{{ props.row.form || 'Not set' }}</span>

        <!-- Column: Status -->
        <span v-else-if="props.column.field === 'status'">
          <b-badge :variant="statusVariant(props.row.status)">
            {{ props.row.status }}
          </b-badge>
        </span>

        <div
          v-else-if="props.column.field === 'process.title'"
          class="text-nowrap text-center"
        >
          <b-link
            v-if="props.row.process"
            :to="{ name: 'pages-process-edit', params: { idProject: props.row.project.id, id: props.row.process.id } }"
            class="font-weight-bold"
            target="_blank"
          >
            <b-badge
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
            >
              {{ props.row.process.id }} - {{ props.row.process.title }}
            </b-badge>
          </b-link>
          <b-badge
            v-else
            variant="secondary"
          >
            Not set
          </b-badge>
        </div>

        <!-- Column: Action -->
        <span v-else-if="props.column.field === 'action'">
          <span class="d-flex">
            <b-dropdown
              variant="link"
              toggle-class="text-decoration-none pr-0"
              no-caret
            >
              <template #button-content>
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="text-body align-middle mr-25"
                />
              </template>
              <b-dropdown-item
                v-if="(!props.row.process || props.row.process.id < idProcess) && idProcess"
                @click.prevent="setCreateIP(props.row.id)"
              >
                <feather-icon
                  icon="GridIcon"
                  class="mr-50"
                />
                <span>Create interview with this Process</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="!(props.row.result && props.row.result.company)"
                @click.prevent="setCandidateDelete(props.row.id, 'only')"
              >
                <feather-icon
                  icon="TrashIcon"
                  class="mr-50"
                />
                <span>Delete Only</span>
              </b-dropdown-item>
              <b-dropdown-item
                @click.prevent="setCandidateDelete(props.row.id, 'all')"
              >
                <feather-icon
                  icon="TrashIcon"
                  class="mr-50"
                />
                <span>Delete All</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="!props.row.result"
                :to="{ name: 'interview-meeting-create-test', params: { id: props.row.id } }"
                class="font-weight-bold"
              >
                <feather-icon
                  icon="EyeIcon"
                  class="mr-50"
                />
                <span v-if="!props.row.questions">Create Test</span>
                <span v-else>Edit Test</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="props.row.result || props.row.form === 'Offline'"
                :to="{ name: 'interview-meeting-result', params: { id: props.row.id } }"
                class="font-weight-bold"
                target="_blank"
              >
                <feather-icon
                  icon="BookIcon"
                  class="mr-50"
                />
                <span v-if="props.row.result">View Result</span>
                <span v-else-if="props.row.form === 'Offline'">Create Result</span>
              </b-dropdown-item>
            </b-dropdown>
            <b-button
              v-if="props.row.process && props.row.process.id === idProcess && !(props.row.result && props.row.result.company)"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="flat-primary"
              class="pr-1 pl-1"
              @click.prevent="setCandidateSchedule(props.row.id)"
            >
              <feather-icon
                icon="CalendarIcon"
              />
            </b-button>
          </span>
        </span>

        <!-- Column: Common -->
        <span v-else>
          {{ props.formattedRow[props.column.field] }}
        </span>
      </template>

      <!-- pagination -->
      <template
        slot="pagination-bottom"
        slot-scope="props"
      >
        <div class="d-flex justify-content-between flex-wrap">
          <div class="d-flex align-items-center mb-0 mt-1">
            <span class="text-nowrap">
              Showing 1 to
            </span>
            <b-form-select
              v-model="pageLength"
              :options="['10','25','50']"
              class="mx-1"
              @input="(value)=>props.perPageChanged({currentPerPage:value})"
            />
            <span class="text-nowrap "> of {{ props.total }} entries </span>
          </div>
          <div>
            <b-pagination
              :value="1"
              :total-rows="props.total"
              :per-page="pageLength"
              first-number
              last-number
              align="right"
              prev-class="prev-item"
              next-class="next-item"
              class="mt-1 mb-0"
              @input="(value)=>props.pageChanged({currentPage:value})"
            >
              <template #prev-text>
                <feather-icon
                  icon="ChevronLeftIcon"
                  size="18"
                />
              </template>
              <template #next-text>
                <feather-icon
                  icon="ChevronRightIcon"
                  size="18"
                />
              </template>
            </b-pagination>
          </div>
        </div>
      </template>
    </vue-good-table>

    <b-modal
      id="modal-candidate"
      cancel-title="Cancel"
      scrollable
      size="xl"
      title="Candidate Details"
    >
      <candidate-info :info="candidateView" />
    </b-modal>

    <b-modal
      id="modal-delete"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Alert"
      @ok="deleteCandidate"
    >
      <b-card-text>
        Are you sure you want to delete this candidate?
      </b-card-text>
    </b-modal>

    <b-modal
      id="modal-create-ip"
      ok-variant="warning"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-warning"
      centered
      title="Create interview with this process"
      @ok="createIP"
    >
      <b-card-text>
        Are you sure you want to create an interview with this process?
      </b-card-text>
    </b-modal>

    <b-modal
      id="modal-detail"
      cancel-variant="outline-secondary"
      cancel-title="Close"
      size="xl"
      scrollable
      title="Edit Schedule"
      @hidden="resetData"
    >
      <calendar
        :interview="candidateSchedule"
        :interview-questions="groupQuestionInterviews"
      />
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BDropdown, BDropdownItem, BCardText, VBModal, BLink, BButton,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import store from '@/store/index'
import interview from '@/store/api/Interview'
import groupQs from '@/store/api/GroupQuestion'
import utils from '@/store/utils'
import dashboard from '@/store/api/Dashboard'
import CandidateInfo from './CandidateInfo.vue'
import Calendar from './calendar/Calendar.vue'

export default {
  components: {
    BCard,
    VueGoodTable,
    BAvatar,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BCardText,
    BDropdownItem,
    CandidateInfo,
    Calendar,
    BLink,
    BButton,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  props: {
    idNews: {
      type: Number,
      default: null,
    },
    idProcess: {
      type: Number,
      default: null,
    },
    idProject: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      columns: [
        {
          label: 'Name',
          field: 'candidate.general.name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Name',
          },
        },
        {
          label: 'Email',
          field: 'candidate.general.email',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Email',
          },
        },
        {
          label: 'Time',
          field: 'time',
          filterOptions: {
            enabled: true,
            placeholder: 'Search time',
          },
        },
        {
          label: 'Form',
          field: 'form',
          filterOptions: {
            enabled: true,
            placeholder: 'Search form',
          },
        },
        {
          label: 'Status',
          field: 'status',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Status',
          },
        },
        {
          label: 'Process',
          field: 'process.title',
          filterOptions: {
            enabled: true,
            placeholder: 'Search process',
          },
        },
        {
          label: 'Action',
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
      candidateView: null,
      candidateSchedule: {},
      candidateDelete: null,
      typeDelete: null,
      interviewCreateProcess: null,
      optionsForm: ['Online', 'Offline'],
      creating: false,
      doneInterview: false,
      groupQuestionInterviews: [],
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {
        Scheduled: 'light-primary',
        Completed: 'light-success',
        Passed: 'light-success',
        'Canceled schedule': 'light-danger',
        Failed: 'light-danger',
        Created: 'light-warning',
        'Have test': 'light-dark',
        'Done test': 'light-info',
      }

      return status => statusColor[status]
    },
    direction() {
      if (store.state.appConfig.isRTL) {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.dir = true
        return this.dir
      }
      // eslint-disable-next-line vue/no-side-effects-in-computed-properties
      this.dir = false
      return this.dir
    },
  },
  watch: {
    idProcess() {
      this.getData()
    },
  },
  created() {
    this.getData()
    this.getGroupInterview()
  },
  methods: {
    getData() {
      interview.showByNews({
        news_id: this.idNews,
        process_id: this.idProcess,
      }).then(resp => {
        const rs = resp.data
        this.rows = rs.data.data
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.rows = []
      })
    },
    getGroupInterview() {
      groupQs.showGroupInterviews().then(resp => {
        const rs = resp.data
        this.groupQuestionInterviews = rs.data?.map(item => ({
          id: item.id,
          title: item.title,
        }))
        utils.updateUser(rs.user)
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
      }).catch(err => {
        console.log(err)
        this.groupQuestionInterviews = []
      })
    },
    viewCandidate(id) {
      this.candidateView = this.rows.find(item => item.id === id).candidate
      this.$bvModal.show('modal-candidate')
    },
    setCreateIP(id) {
      this.interviewCreateProcess = this.rows.find(item => item.id === id)
      this.$bvModal.show('modal-create-ip')
    },
    setCandidateSchedule(id) {
      this.candidateSchedule = this.rows.find(item => item.id === id)
      this.doneInterview = !!((this.candidateSchedule.result && this.candidateSchedule.result.company) || (this.candidateSchedule.record && this.candidateSchedule.record.company))
      this.$bvModal.show('modal-detail')
    },
    saveSchedule(bvModalEvent) {
      bvModalEvent.preventDefault()
      this.$refs.scheduleForm.validate().then(success => {
        if (success) {
          this.creating = true
          interview.update(this.candidateSchedule.id, {
            time: this.candidateSchedule.time,
            news_id: this.candidateSchedule.news.id,
            company_id: this.candidateSchedule.company.id,
            candidate_id: this.candidateSchedule.candidate.general.id,
            form: this.candidateSchedule.form,
            address: this.candidateSchedule.address,
          }).then(resp => {
            const rs = resp.data
            utils.updateUser(rs.user)

            const index = this.rows.findIndex(item => item.id === this.candidateSchedule.id)
            this.rows = [
              ...this.rows.slice(0, index),
              rs.data,
              ...this.rows.slice(index + 1),
            ]
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Update schedule success',
                icon: 'CoffeeIcon',
                variant: 'success',
              },
            })
            this.creating = false
            this.$nextTick(() => {
              this.$bvModal.hide('modal-detail')
            })
            this.candidateSchedule = {}
          }).catch(err => {
            console.log(err)
            this.creating = false
            this.$nextTick(() => {
              this.$bvModal.hide('modal-detail')
            })
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
          })
        }
      })
    },
    createIP(bvModalEvent) {
      bvModalEvent.preventDefault()

      if (this.interviewCreateProcess.process) {
        interview.store({
          news_id: this.interviewCreateProcess.news.id,
          company_id: this.interviewCreateProcess.company.id,
          candidate_id: this.interviewCreateProcess.candidate.general.owner.id,
          process_id: this.idProcess,
        }).then(resp => {
          const rs = resp.data
          utils.updateUser(rs.user)

          const index = this.rows.findIndex(item => item.id === this.interviewCreateProcess.id)
          this.rows = [
            ...this.rows.slice(0, index),
            rs.data,
            ...this.rows.slice(index + 1),
          ]
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Create success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.$nextTick(() => {
            this.$bvModal.hide('modal-create-ip')
          })
          this.candidateSchedule = {}
        }).catch(err => {
          console.log(err)
          this.$nextTick(() => {
            this.$bvModal.hide('modal-create-ip')
          })
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
        })
      } else {
        interview.update(this.interviewCreateProcess.id, {
          news_id: this.interviewCreateProcess.news.id,
          company_id: this.interviewCreateProcess.company.id,
          candidate_id: this.interviewCreateProcess.candidate.general.id,
          process_id: this.idProcess,
        }).then(resp => {
          const rs = resp.data
          utils.updateUser(rs.user)

          const index = this.rows.findIndex(item => item.id === this.interviewCreateProcess.id)
          this.rows = [
            ...this.rows.slice(0, index),
            rs.data,
            ...this.rows.slice(index + 1),
          ]
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Create success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.$nextTick(() => {
            this.$bvModal.hide('modal-create-ip')
          })
          this.candidateSchedule = {}
        }).catch(err => {
          console.log(err)
          this.$nextTick(() => {
            this.$bvModal.hide('modal-create-ip')
          })
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
        })
      }
    },
    setCandidateDelete(id, type) {
      this.typeDelete = type
      this.candidateDelete = id
      this.$bvModal.show('modal-delete')
    },
    deleteCandidate(bvModalEvent) {
      bvModalEvent.preventDefault()

      if (this.typeDelete === 'all') {
        interview.delete(this.candidateDelete).then(resp => {
          const rs = resp.data
          const deleteInterview = this.rows.find(item => item.id === this.candidateDelete)
          this.rows = this.rows.filter(item => item.candidate.general.id !== deleteInterview.candidate.general.id || item.news.id !== deleteInterview.news.id)
          utils.updateUser(rs.user)
          dashboard.notify({
            emails: [deleteInterview.candidate.general.email],
            subject: 'Interview cancel',
            name: deleteInterview.candidate.general.name,
            body: `Your interview with ${deleteInterview.news.title} has been cancel by ${deleteInterview.company.general.name}
            <p>Email company: ${deleteInterview.company.general.email}</p>
            <p>Phone company: ${deleteInterview.company.general.phone}</p>
            <br/> <b>Please contact the company if you have any questions!</b>`,
          })
          this.$ability.update([
            {
              action: 'manage',
              subject: rs.user.role,
            },
          ])
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Delete Candidate success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.$nextTick(() => {
            this.$bvModal.hide('modal-delete')
          })
          this.candidateDelete = null
          this.typeDelete = null
        }).catch(err => {
          console.log(err)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              text: "Something error or you don't have permission. Please try again!!!",
            },
          })
          this.candidateDelete = null
          this.typeDelete = null
          this.$nextTick(() => {
            this.$bvModal.hide('modal-delete')
          })
        })
      } else {
        interview.deleteOnly(this.candidateDelete).then(resp => {
          const rs = resp.data
          const deleteInterview = this.rows.find(item => item.id === this.candidateDelete)
          dashboard.notify({
            emails: [deleteInterview.candidate.general.email],
            subject: 'Interview cancel',
            name: deleteInterview.candidate.general.name,
            body: `Your interview with ${deleteInterview.news.title} and process has been cancel by ${deleteInterview.company.general.name}
            <p>Email company: ${deleteInterview.company.general.email}</p>
            <p>Phone company: ${deleteInterview.company.general.phone}</p>
            <br/> <b>Please contact the company if you have any questions!</b>`,
          })
          this.rows = this.rows.filter(item => item.id !== this.candidateDelete)
          utils.updateUser(rs.user)
          this.$ability.update([
            {
              action: 'manage',
              subject: rs.user.role,
            },
          ])
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Delete Candidate success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.$nextTick(() => {
            this.$bvModal.hide('modal-delete')
          })
          this.candidateDelete = null
          this.typeDelete = null
        }).catch(err => {
          console.log(err)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Error',
              icon: 'AlertTriangleIcon',
              variant: 'danger',
              text: "Something error or you don't have permission. Please try again!!!",
            },
          })

          this.$nextTick(() => {
            this.$bvModal.hide('modal-delete')
          })
          this.candidateDelete = null
          this.typeDelete = null
        })
      }
    },
    resetData() {
      if (localStorage.getItem('calendar')) {
        this.getData()
        localStorage.removeItem('calendar')
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-good-table.scss';
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-flatpicker.scss';

.vgt-inner-wrap .d-flex.justify-content-between.flex-wrap {
  padding: 10px;
}
table.vgt-table td {
  vertical-align: middle!important;
}
</style>
