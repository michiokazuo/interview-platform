<template>
  <b-card>

    <b-tabs
      pills
    >

      <!-- Tab: Account -->
      <b-tab>
        <template #title>
          <feather-icon
            icon="UserIcon"
            size="16"
            class="mr-0 mr-sm-50"
          />
          <span class="d-none d-sm-inline">General</span>
        </template>
        <project-edit :id="id" />
      </b-tab>

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
        <project-information
          :id="id"
          class="mt-2 pt-75 bg-light"
        />
      </b-tab>

    </b-tabs>
  </b-card>
</template>

<script>
import {
  BTab, BTabs, BCard,
} from 'bootstrap-vue'
import ProjectEdit from './ProjectEdit.vue'
import ProjectInformation from './ProjectInformation.vue'

export default {
  components: {
    BTab,
    BTabs,
    BCard,

    ProjectEdit,
    ProjectInformation,
  },
  data() {
    return {
      id: null,
    }
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id - 0
    } else {
      this.id = null
    }
  },
}
</script>

<style>

</style>
