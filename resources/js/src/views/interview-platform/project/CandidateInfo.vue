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
          icon="UserIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">General</span>
      </template>

      <candidate-general
        v-if="info && info.general"
        :user-info="info.general"
      />
    </b-tab>
    <!--/ general tab -->

    <b-tab v-if="info && info.general && Object.entries(info.general).length && info.general.role.name === 'ROLE_CANDIDATE'">
      <!-- title -->
      <template #title>
        <feather-icon
          icon="LockIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">CV</span>
      </template>

      <candidate-c-v
        v-if="info && info.cv"
        :cv-info="info.cv"
      />
    </b-tab>
  </b-tabs>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import CandidateCV from './candidate/CandidateCV.vue'
import CandidateGeneral from './candidate/CandidateGeneral.vue'

export default {
  components: {
    BTabs,
    BTab,
    CandidateCV,
    CandidateGeneral,
  },
  props: {
    info: {
      type: Object,
      default: () => ({
        general: {},
        cv: {},
      }),
    },
  },
}
</script>
