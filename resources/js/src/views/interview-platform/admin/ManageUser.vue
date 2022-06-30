<template>
  <b-row>
    <b-col cols="12">
      <b-card title="General">
        <b-row>
          <b-col
            md="4"
            class="mb-75"
            cols="6"
          >
            <h5 class="text-capitalize">
              Total users
            </h5>
            <b-card-text>
              {{ total }}
            </b-card-text>
          </b-col>
          <b-col
            md="4"
            class="mb-75"
            cols="6"
          >
            <h5 class="text-capitalize ">
              Number candidates
            </h5>
            <b-card-text class="text-capitalize">
              {{ candidates }}
            </b-card-text>
          </b-col>
          <b-col
            md="4"
            class="mb-75"
            cols="6"
          >
            <h5 class="text-capitalize ">
              Number Companies
            </h5>
            <b-card-text>
              {{ companies }}
            </b-card-text>
          </b-col>
          <b-col
            md="4"
            class="mb-75"
            cols="6"
          >
            <h5 class="text-capitalize ">
              Number Companies inactive
            </h5>
            <b-card-text>
              {{ inactive }}
            </b-card-text>
          </b-col>
        </b-row>
      </b-card>
    </b-col>
    <b-col cols="12">
      <b-card title="User in system">

        <!-- input search -->
        <div class="custom-search d-flex justify-content-end">
          <b-form-group>
            <div class="d-flex align-items-center">
              <label class="mr-1 h4 font-weight-bold">Search</label>
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
              v-if="props.column.field === 'general.name'"
              class="text-nowrap"
            >
              <b-avatar
                :src="props.row.general.avatar"
                class="mx-1"
              />
              <span class="text-nowrap">{{ props.row.general.name }}</span>
            </div>

            <!-- Column: Status -->
            <span v-else-if="props.column.field === 'general.status'">
              <b-badge :variant="statusVariant(props.row.general.status)">
                {{ props.row.general.status }}
              </b-badge>
            </span>

            <span v-else-if="props.column.field === 'general.role.name'">
              <div class="text-nowrap">
                <feather-icon
                  :icon="resolveUserRoleIcon(props.row.general.role.name)"
                  size="18"
                  class="mr-50"
                  :class="`text-${resolveUserRoleVariant(props.row.general.role.name)}`"
                />
                <span class="align-text-top text-capitalize">{{ props.row.general.role.name }}</span>
              </div>
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
                    @click.prevent="viewUser(props.row.general.id)"
                  >
                    <feather-icon
                      icon="EyeIcon"
                      class="mr-50"
                    />
                    <span>View</span>
                  </b-dropdown-item>
                  <b-dropdown-item
                    v-if="props.row.general.role.name === 'ROLE_COMPANY' && !props.row.general.owner.is_active"
                    @click.prevent="setActive(props.row.general.id)"
                  >
                    <feather-icon
                      icon="RefreshCcwIcon"
                      class="mr-50"
                    />
                    <span>Active</span>
                  </b-dropdown-item>
                  <b-dropdown-item
                    v-if="props.row.general.role.name === 'ROLE_COMPANY'"
                    :to="{ name: 'pages-company-news-list', params: { id: props.row.general.owner.id } }"
                  >
                    <feather-icon
                      icon="BriefcaseIcon"
                      class="mr-50"
                    />
                    <span>View News</span>
                  </b-dropdown-item>
                  <b-dropdown-item
                    :to="{ name: 'pages-another-user-blog-list', params: { id: props.row.general.id } }"
                    class="font-weight-bold"
                  >
                    <feather-icon
                      icon="BookOpenIcon"
                      class="mr-50"
                    />
                    <span>View Blog</span>
                  </b-dropdown-item>
                  <b-dropdown-item
                    @click.prevent="setDelete(props.row.general.id)"
                  >
                    <feather-icon
                      icon="TrashIcon"
                      class="mr-50"
                    />
                    <span>Delete</span>
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
      </b-card>
    </b-col>

    <b-modal
      id="modal-user"
      cancel-title="Cancel"
      scrollable
      size="xl"
      title="User Details"
    >
      <candidate-info :info="userView" />
    </b-modal>

    <b-modal
      id="modal-delete"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Alert"
      @ok="deleteUser"
    >
      <b-card-text>
        Are you sure you want to delete this user?
      </b-card-text>
    </b-modal>

    <b-modal
      id="modal-active"
      cancel-variant="outline-secondary"
      ok-title="Accept"
      cancel-title="Close"
      centered
      title="Active company"
      @ok="confirmActiveCompany"
    >
      <b-card-text>
        Are you sure you want to change this company?
      </b-card-text>
    </b-modal>
  </b-row>
</template>

<script>
import {
  BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem, BRow, BCol, BCard, VBModal, BCardText,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import store from '@/store/index'
import admin from '@/store/api/Admin'
import utils from '@/store/utils'
import CandidateInfo from '../project/CandidateInfo.vue'

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
    BDropdownItem,
    BRow,
    BCol,
    BCardText,
    CandidateInfo,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  data() {
    return {
      pageLength: 10,
      dir: false,
      columns: [
        {
          label: 'Name',
          field: 'general.name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Name',
          },
        },
        {
          label: 'Email',
          field: 'general.email',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Email',
          },
        },
        {
          label: 'Date',
          field: 'general.created_at',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Date',
          },
        },
        {
          label: 'Role',
          field: 'general.role.name',
          filterOptions: {
            enabled: true,
            placeholder: 'Search role',
          },
        },
        {
          label: 'Status',
          field: 'general.status',
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
      userView: {},
      activeCompany: {},
      total: 0,
      candidates: 0,
      companies: 0,
      inactive: 0,
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {
        /* eslint-disable key-spacing */
        Inactive : 'light-secondary',
        Active : 'light-success',
        /* eslint-enable key-spacing */
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
    admin.getAllUsers().then(resp => {
      const rs = resp.data
      this.rows = rs.data.data
      this.total = rs.data.total
      this.candidates = rs.data.candidates
      this.companies = rs.data.companies
      this.inactive = rs.data.inactive
      utils.updateUser(rs.user)
      this.$ability.update([
        {
          action: 'manage',
          subject: rs.user.role,
        },
      ])
      if (rs.data.inactive) {
        this.$toast({
          component: ToastificationContent,
          position: 'top-right',
          props: {
            title: `Have ${rs.data.inactive} companies are inactive. Please contact them to activate.`,
            icon: 'CoffeeIcon',
            variant: 'warning',
          },
        })
      }
    }).catch(err => {
      console.log(err)
      this.rows = []
    })
  },
  methods: {
    resolveUserRoleVariant(role) {
      if (role === 'ROLE_CANDIDATE') return 'primary'
      if (role === 'ROLE_COMPANY') return 'warning'
      return 'primary'
    },
    resolveUserRoleIcon(role) {
      if (role === 'ROLE_CANDIDATE') return 'UserIcon'
      if (role === 'ROLE_COMPANY') return 'SettingsIcon'
      return 'UserIcon'
    },
    viewUser(id) {
      this.userView = this.rows.find(item => item.general.id === id)
      this.$bvModal.show('modal-user')
    },
    setActive(id) {
      this.activeCompany = this.rows.find(item => item.general.id === id)
      this.$bvModal.show('modal-active')
    },
    confirmActiveCompany(bvModalEvent) {
      bvModalEvent.preventDefault()

      admin.activeCompany(this.activeCompany.general.owner.id)
        .then(resp => {
          const rs = resp.data
          this.inactive -= 1
          utils.updateUser(rs.user)

          this.activeCompany.general.status = 'Active'
          this.activeCompany.general.owner.is_active = true
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Update company success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.$nextTick(() => {
            this.$bvModal.hide('modal-active')
          })
          this.candidateSchedule = {}
        }).catch(err => {
          console.log(err)
          this.$nextTick(() => {
            this.$bvModal.hide('modal-active')
          })
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
        })
    },
    setDelete(id) {
      this.userDelete = id
      this.$bvModal.show('modal-delete')
    },
    deleteUser(bvModalEvent) {
      bvModalEvent.preventDefault()

      admin.deleteUser(this.userDelete).then(resp => {
        const rs = resp.data
        this.total -= 1
        const deleteUser = this.rows.find(item => item.general.id === this.userDelete)
        if (deleteUser) {
          // eslint-disable-next-line no-unused-expressions
          deleteUser.general.role.name === 'ROLE_CANDIDATE' ? this.candidates -= 1 : this.companies -= 1
        }
        this.rows = this.rows.filter(item => item.general.id !== this.userDelete)
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
            title: 'Delete User success',
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
<style lang="scss" >
@import '~@core/scss/vue/libs/vue-good-table.scss';

.vgt-inner-wrap .d-flex.justify-content-between.flex-wrap {
  padding: 10px;
}
table.vgt-table td {
  vertical-align: middle!important;;
}
</style>
