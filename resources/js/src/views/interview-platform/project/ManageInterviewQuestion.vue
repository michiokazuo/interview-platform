<template>
  <b-card>
    <b-col cols="12">
      <b-link
        :to="{ name: 'manage-interview-question-create' }"
        class="font-weight-bold mb-2"
      >
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="primary"
          class="mb-2"
        >
          Create new Group questions
        </b-button>
      </b-link>
    </b-col>

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
        <span
          v-if="props.column.field === 'created_at'"
          class="text-nowrap"
        >{{ props.row.created_at || 'Not set' }}</span>

        <!-- Column: Status -->
        <span v-else-if="props.column.field === 'type'">
          <b-badge :variant="statusVariant(props.row.type)">
            {{ props.row.type }}
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
                v-if="props.row.interview && props.row.interview.news_id"
                :to="{ name: 'pages-news-detail', params: { id: props.row.interview.news_id } }"
                class="font-weight-bold"
              >
                <feather-icon
                  icon="EyeIcon"
                  class="mr-50"
                />
                <span>View News</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="props.row.interview"
                :to="!props.row.interview.result ? { name: 'interview-meeting-create-test', params: { id: props.row.interview.id } } : { name: 'interview-meeting-result', params: { id: props.row.interview.id } }"
                class="font-weight-bold"
                target="_blank"
              >
                <feather-icon
                  icon="BookIcon"
                  class="mr-50"
                />
                <span v-if="props.row.interview && !props.row.interview.result">View Edit</span>
                <span v-else>View Result</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="props.row.is_interview"
                :to="{ name: 'manage-interview-question-edit', params: { id: props.row.id } }"
                class="font-weight-bold"
              >
                <feather-icon
                  icon="Edit2Icon"
                  class="mr-50"
                />
                <span>Edit</span>
              </b-dropdown-item>
              <b-dropdown-item
                v-if="props.row.is_interview"
                @click.prevent="setGroupDelete(props.row.id)"
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

    <b-modal
      id="modal-delete"
      ok-variant="danger"
      ok-title="Accept"
      cancel-title="Cancel"
      modal-class="modal-danger"
      centered
      title="Delete Alert"
      @ok="deleteGroup"
    >
      <b-card-text>
        Are you sure you want to delete this candidate?
      </b-card-text>
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect,
  BDropdown, BDropdownItem, BCardText, VBModal, BCol, BButton, BLink,
} from 'bootstrap-vue'
import { VueGoodTable } from 'vue-good-table'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import store from '@/store/index'
import groupQS from '@/store/api/GroupQuestion'
import utils from '@/store/utils'

export default {
  components: {
    BCard,
    VueGoodTable,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BCardText,
    BDropdownItem,
    BCol,
    BButton,
    BLink,
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
          label: 'Title',
          field: 'title',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Title',
          },
        },
        {
          label: 'Number question',
          field: 'number_question',
          filterOptions: {
            enabled: true,
            placeholder: 'Search number',
          },
        },
        {
          label: 'Created at',
          field: 'created_at',
          filterOptions: {
            enabled: true,
            placeholder: 'Search time',
          },
        },
        {
          label: 'Type',
          field: 'type',
          filterOptions: {
            enabled: true,
            placeholder: 'Search type',
          },
        },
        {
          label: 'Action',
          field: 'action',
        },
      ],
      rows: [],
      searchTerm: '',
      groupDeleteId: null,
    }
  },
  computed: {
    statusVariant() {
      const statusColor = {
        Interview: 'light-primary',
        'Candidate Test': 'light-warning',
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
    this.getData()
  },
  methods: {
    getData() {
      groupQS.showByUser().then(resp => {
        const rs = resp.data
        this.rows = rs.data
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
    setGroupDelete(id) {
      this.groupDeleteId = id
      this.$bvModal.show('modal-delete')
    },
    deleteGroup(bvModalEvent) {
      bvModalEvent.preventDefault()

      groupQS.delete(this.groupDeleteId).then(resp => {
        const rs = resp.data
        this.rows = this.rows.filter(item => item.id !== this.groupDeleteId)
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
        this.groupDeleteId = null
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
