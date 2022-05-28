<template>
  <b-card>

    <b-tabs
      pills
    >

      <!-- Tab: Information -->
      <b-tab active>
        <template #title>
          <feather-icon
            icon="InfoIcon"
            size="16"
            class="mr-0 mr-sm-50"
          />
          <span class="d-none d-sm-inline">Information</span>
        </template>
        <news-edit
          :id="id"
          :id-project="idProject"
          class="mt-2 pt-75"
        />
      </b-tab>

      <!-- Tab: Candidates -->
      <b-tab v-if="id && idProject">
        <template #title>
          <feather-icon
            icon="UserIcon"
            size="16"
            class="mr-0 mr-sm-50"
          />
          <span class="d-none d-sm-inline">Candidates</span>
        </template>
        <table-candidates
          :id="id"
          :id-project="idProject"
        />
      </b-tab>

    </b-tabs>
  </b-card>
</template>

<script>
import {
  BTab, BTabs, BCard,
} from 'bootstrap-vue'
import NewsEdit from './NewsEdit.vue'
import TableCandidates from './TableCandidates.vue'

export default {
  components: {
    BTab,
    BTabs,
    BCard,

    TableCandidates,
    NewsEdit,
  },
  data() {
    return {
      id: null,
      idProject: null,
    }
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
  },
}
</script>
