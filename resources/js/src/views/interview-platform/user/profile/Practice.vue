<template>
  <b-card>
    <div class="col-12">
      <b-link
        class="font-weight-bold mb-2"
      >
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          v-b-modal.modal-practice
          variant="primary"
          class="mb-2"
        >
          Create new Practice
        </b-button>
      </b-link>
    </div>
    <!-- table -->
    <vue-good-table
      :columns="columns"
      :rows="rows"
      :rtl="direction"
      :pagination-options="{
        enabled: true,
        perPage: pageLength
      }"
    >
      <template
        slot="table-row"
        slot-scope="props"
      >

        <!-- Column: Name -->
        <div
          v-if="props.column.field === 'record'"
          class="text-nowrap text-center"
        >
          <b-badge
            v-if="props.row.record"
            variant="primary"
          >
            <b-link
              :to="props.row.record.candidate"
              target="_blank"
            >
              View record
            </b-link>
          </b-badge>
          <b-badge
            v-else
            variant="secondary"
          >
            No record
          </b-badge>
        </div>

        <div
          v-else-if="props.column.field === 'result'"
          class="text-nowrap text-center"
        >
          <b-link
            v-if="props.row.questions && props.row.result && props.row.result.candidate"
            :to="{ name: 'interview-meeting-result', params: { id: props.row.id } }"
            class="font-weight-bold"
          >
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="success"
            >
              View result
            </b-button>
          </b-link>
          <b-badge
            v-else
            variant="secondary"
          >
            No result
          </b-badge>
        </div>

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
      id="modal-practice"
      cancel-variant="outline-secondary"
      ok-title="Accept"
      cancel-title="Close"
      centered
      :ok-title-html="creating ? `<span aria-hidden='true' class='spinner-border spinner-border-sm'></span> Creating...` : `Accept`"
      :ok-disabled="creating"
      title="Create practice"
      @ok="createPractice"
    >

      <b-form>
        <b-form-group>
          <b-alert
            variant="warning"
            :show="!create.meeting && !create.questions"
          >
            <h4 class="alert-heading">
              Note
            </h4>
            <div class="alert-body">
              <span>Please select one or more condition</span>
            </div>
          </b-alert>
        </b-form-group>
        <b-form-group>
          <b-form-checkbox
            v-model="create.meeting"
          >
            Have meeting
          </b-form-checkbox>
        </b-form-group>
        <b-form-group>
          <b-form-checkbox
            v-model="create.questions"
          >
            Have questions
          </b-form-checkbox>
        </b-form-group>
        <b-form-group v-if="create.questions">
          <v-select
            v-model="create.tags"
            multiple
            label="name"
            :options="tags"
            placeholder="Tags"
          />
        </b-form-group>
        <b-form-group v-if="create.questions">
          <label for="demo-sb">Number of questions</label>
          <b-form-spinbutton
            id="demo-sb"
            v-model="create.number"
            min="1"
            max="100"
            placeholder="Number questions"
          />
        </b-form-group>
      </b-form>
    </b-modal>
  </b-card>
</template>

<script>
import {
  BCard, BBadge, BPagination, BFormSelect, BLink, BButton, BModal, BFormGroup,
  BFormCheckbox, VBModal, BForm, BAlert, BFormSpinbutton,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { VueGoodTable } from 'vue-good-table'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import vSelect from 'vue-select'
import store from '@/store/index'
import interview from '@/store/api/Interview'
import qat from '@/store/api/QAT'
import utils from '@/store/utils'

export default {
  components: {
    BCard,
    VueGoodTable,
    BBadge,
    BPagination,
    BLink,
    BButton,
    BFormSelect,
    BModal,
    BFormGroup,
    BFormCheckbox,
    BForm,
    BAlert,
    vSelect,
    BFormSpinbutton,
  },
  directives: {
    'b-modal': VBModal,
    Ripple,
  },
  props: {
    practices: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      tags: [],
      userData: JSON.parse(localStorage.getItem('userData')),
      create: {
        meeting: false,
        questions: false,
        tags: [],
        number: 20,
      },
      pageLength: 10,
      dir: false,
      columns: [
        {
          label: 'Start time',
          field: 'time',
          filterOptions: {
            enabled: true,
            placeholder: 'Search Start',
          },
        },
        {
          label: 'Record',
          field: 'record',
        },
        {
          label: 'Result',
          field: 'result',
        },
      ],
      rows: [...this.practices],
      creating: false,
    }
  },
  computed: {
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
    practices() {
      this.rows = [...this.practices]
    },
  },
  created() {
    this.getTags()
  },
  methods: {
    getTags() {
      qat.getTags().then(resp => {
        const rs = resp.data
        this.tags = rs.data
        utils.updateUser(rs.user)
        this.userData = rs.user
      })
    },
    createPractice(bvModalEvent) {
      bvModalEvent.preventDefault()
      const { create } = this
      if (create.meeting || create.questions) {
        this.creating = true
        interview.store({
          time: new Date(),
          candidate_id: this.userData.candidate_id,
          form: 'Online',
          interview_test: create.questions,
          interview_meeting: create.meeting,
          interview_questions_tags: create.tags.map(s => s.id),
          number_of_questions: create.number ?? 20,
        }).then(resp => {
          const rs = resp.data
          utils.updateUser(rs.user)
          this.userData = rs.user
          this.rows.push(rs.data)
          this.$toast({
            component: ToastificationContent,
            position: 'top-right',
            props: {
              title: 'Create practice success',
              icon: 'CoffeeIcon',
              variant: 'success',
            },
          })
          this.creating = false
          this.$nextTick(() => {
            this.$bvModal.hide('modal-practice')
          })
          this.create = {}
          if (rs.data.room) {
            this.$router.push({ name: 'interview-meeting', params: { id: rs.data.id } })
          } else {
            this.$router.push({ name: 'interview-meeting-practice-test', params: { id: rs.data.id } })
          }
        }).catch(err => {
          console.log(err)
          this.creating = false
          this.$nextTick(() => {
            this.$bvModal.hide('modal-practice')
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
      }
    },
  },
}
</script>

<style lang="scss" >
@import '~@core/scss/vue/libs/vue-good-table.scss';
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-flatpicker.scss';
</style>

<style scoped lang="css">
.vgt-inner-wrap .d-flex.justify-content-between.flex-wrap {
  padding: 10px;
}
</style>
