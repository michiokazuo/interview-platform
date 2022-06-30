<template>
  <b-card>
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center">
        <div class="d-flex align-items-center mr-1">
          <b-link
            v-if="process.prev_step"
            @click.prevent="goToStep(process.prev_step)"
          >
            <div class="d-inline-flex align-items-center text-primary">
              <feather-icon
                icon="ArrowLeftIcon"
                size="18"
                class="mr-50"
              />
              <span>Prev Step</span>
            </div>
          </b-link>
        </div>
      </div>

      <!-- dropdown -->
      <div class="blog-detail-share">
        <b-link
          v-if="process.next_step"
          @click.prevent="goToStep(process.next_step)"
        >
          <div class="d-inline-flex align-items-center text-primary">
            <span>Next Step</span>
            <feather-icon
              icon="ArrowRightIcon"
              size="18"
              class="mr-50"
            />
          </div>
        </b-link>
      </div>
      <!--/ dropdown -->
    </div>

    <!-- eslint-enable -->
    <hr class="my-2">
    <b-tabs
      pills
    >

      <!-- Tab: Information -->
      <b-tab :active="hash !== '#candidate'">
        <template #title>
          <feather-icon
            icon="InfoIcon"
            size="16"
            class="mr-0 mr-sm-50"
          />
          <span class="d-none d-sm-inline">Information</span>
        </template>
        <process-edit
          :id="id"
          :id-project="idProject"
          class="mt-2 pt-75"
          :process="process"
        />
      </b-tab>

      <!-- Tab: Candidates -->
      <b-tab
        v-if="id && idProject"
        :active="hash === '#candidate'"
      >
        <template #title>
          <feather-icon
            icon="UserIcon"
            size="16"
            class="mr-0 mr-sm-50"
          />
          <span class="d-none d-sm-inline">Candidates</span>
        </template>
        <table-candidates
          :id-process="id"
          :id-project="idProject"
        />
      </b-tab>

    </b-tabs>
  </b-card>
</template>

<script>
import {
  BTab, BTabs, BCard, BLink,
} from 'bootstrap-vue'
import ProcessEdit from './ProcessEdit.vue'
import TableCandidates from './TableCandidates.vue'
import process from '@/store/api/RProcess'
import utils from '@/store/utils'

export default {
  components: {
    BTab,
    BTabs,
    BCard,
    BLink,

    TableCandidates,
    ProcessEdit,
  },
  data() {
    return {
      id: null,
      idProject: null,
      hash: null,
      process: {},
    }
  },
  mounted() {
    this.hash = this.$route.hash
  },
  created() {
    const { id } = this.$route.params
    const { idProject } = this.$route.params
    this.idProject = idProject - 0
    if (id) {
      this.id = id - 0
    } else {
      this.id = null
    }
    if (this.id && this.idProject) {
      this.getData()
    } else {
      this.process = {
        user: JSON.parse(localStorage.getItem('userData')),
        start_time: new Date(),
      }
    }
  },
  methods: {
    getData() {
      process.findById(this.id).then(resp => {
        const rs = resp.data
        this.process = rs.data
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
        this.process = null
      })
    },
    goToStep(step) {
      this.id = step
      this.getData()
    },
  },
}
</script>
