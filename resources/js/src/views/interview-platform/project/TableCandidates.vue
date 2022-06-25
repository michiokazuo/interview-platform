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
          <span class="text-nowrap">{{ props.row.candidate.general.name }}</span>
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

        <!-- Column: Action -->
        <span v-else-if="props.column.field === 'action'">
          <span>
            <b-dropdown
              variant="link"
              toggle-class="text-decoration-none"
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
                @click.prevent="viewCandidate(props.row.id)"
              >
                <feather-icon
                  icon="EyeIcon"
                  class="mr-50"
                />
                <span>View</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="!(props.row.result && props.row.result.company)"
                @click.prevent="setCandidateSchedule(props.row.id)"
              >
                <feather-icon
                  icon="CalendarIcon"
                  class="mr-50"
                />
                <span>Edit schedule</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="!(props.row.result && props.row.result.company)"
                @click.prevent="setCandidateDelete(props.row.id)"
              >
                <feather-icon
                  icon="TrashIcon"
                  class="mr-50"
                />
                <span>Delete</span>
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
                v-if="props.row.result"
                :to="{ name: 'interview-meeting-result', params: { id: props.row.id } }"
                class="font-weight-bold"
              >
                <feather-icon
                  icon="BookIcon"
                  class="mr-50"
                />
                <span>View Result</span>
              </b-dropdown-item>
            </b-dropdown>
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
      id="modal-detail"
      cancel-variant="outline-secondary"
      ok-title="Accept"
      cancel-title="Close"
      centered
      title="Edit Schedule"
      :ok-title-html="creating ? `<span aria-hidden='true' class='spinner-border spinner-border-sm'></span> Creating...` : `Accept`"
      :ok-disabled="creating"
      @ok="saveSchedule"
    >
      <validation-observer
        ref="scheduleForm"
      >
        <b-form>
          <b-form-group>
            <label for="address">Address</label>
            <validation-provider
              v-slot="{ errors }"
              name="address"
              vid="address"
              rules="required"
            >
              <b-form-input
                id="comment-address"
                v-model="candidateSchedule.address"
                placeholder="Address"
                :state="errors.length > 0 ? false:null"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
          <b-form-group
            label="Start time"
            label-for="start-time"
            class="mb-2"
          >
            <validation-provider
              v-slot="{ errors }"
              name="start_time"
              vid="start_time"
              rules="required"
            >
              <flat-pickr
                id="start-time"
                v-model="candidateSchedule.time"
                name="start_time"
                placeholder="start time"
                :state="errors.length > 0 ? false:null"
                class="form-control"
                :config="{ enableTime: true,dateFormat: 'Y-m-d H:i'}"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>
          <b-form-group
            label="Form"
            label-for="form"
          >
            <validation-provider
              v-slot="{ errors }"
              name="form"
              vid="form"
              rules="required"
            >
              <v-select
                id="register-form"
                v-model="candidateSchedule.form"
                name="register-form"
                :state="errors.length > 0 ? false:null"
                placeholder="Form"
                :options="optionsForm"
              />
              <small class="text-danger">{{ errors[0] }}</small>
            </validation-provider>
          </b-form-group>

          <b-form-group v-if="candidateSchedule.form == 'Online' && candidateSchedule.room">
            <b-link
              :to="{ name: 'interview-meeting', params:{id: candidateSchedule.id} }"
              class="font-weight-bold mb-2"
            >
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="success"
                class="mb-2"
              >
                Meeting
              </b-button>
            </b-link>
            <b-form-input
              v-model="candidateSchedule.room"
              readonly
            />
          </b-form-group>
          <b-form-group v-if="candidateSchedule.form == 'Online' && !candidateSchedule.room">
            <b-alert
              variant="warning"
              show
            >
              <h4 class="alert-heading">
                Note
              </h4>
              <div class="alert-body">
                <p class="p-0 m-0">
                  Please save to create room for this interview
                </p>
                <span>If failed, please try again!!! (Because full of room already)</span>
              </div>
            </b-alert>
          </b-form-group>
        </b-form>
      </validation-observer>
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BDropdown, BDropdownItem, BCardText, VBModal, BForm, BAlert, BLink, BButton,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, password } from '@validations'
import flatPickr from 'vue-flatpickr-component'
import vSelect from 'vue-select'
import Ripple from 'vue-ripple-directive'
import store from '@/store/index'
import interview from '@/store/api/Interview'
import utils from '@/store/utils'
import CandidateInfo from './CandidateInfo.vue'

export default {
  components: {
    BAlert,
    BCard,
    VueGoodTable,
    BAvatar,
    BBadge,
    BPagination,
    BForm,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BCardText,
    BDropdownItem,
    CandidateInfo,
    ValidationProvider,
    ValidationObserver,
    flatPickr,
    vSelect,
    BLink,
    BButton,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  props: {
    id: {
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
          label: 'Action',
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
      candidateView: null,
      candidateSchedule: {},
      candidateDelete: null,
      optionsForm: ['Online', 'Offline'],
      creating: false,
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {
        Scheduled: 'light-primary',
        Completed: 'light-success',
        'Canceled schedule': 'light-danger',
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
  created() {
    interview.showByNews(this.id).then(resp => {
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
  methods: {
    viewCandidate(id) {
      this.candidateView = this.rows.find(item => item.id === id).candidate
      this.$bvModal.show('modal-candidate')
    },
    setCandidateSchedule(id) {
      this.candidateSchedule = this.rows.find(item => item.id === id)
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
    setCandidateDelete(id) {
      this.candidateDelete = id
      this.$bvModal.show('modal-delete')
    },
    deleteCandidate(bvModalEvent) {
      bvModalEvent.preventDefault()

      interview.delete(this.candidateDelete).then(resp => {
        const rs = resp.data
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
      })
    },
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-good-table.scss';
@import '~@core/scss/vue/libs/vue-flatpicker.scss';

.vgt-inner-wrap .d-flex.justify-content-between.flex-wrap {
  padding: 10px;
}
table.vgt-table td {
  vertical-align: middle!important;;
}
</style>
