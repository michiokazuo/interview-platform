<template>
  <section id="dashboard-ecommerce">
    <b-row
      v-if="data"
      class="match-height"
    >
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.users"
          :colors="['#7367f0', '#28c76f', '#ea5455']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.qat"
          :colors="['#796fe3', '#00cfe8', '#20c997']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.blog_cmt"
          :colors="['#00cfe8', '#ff9f43']"
        />
      </b-col>
    </b-row>
    <b-row
      v-if="data && data.graph"
      class="match-height"
    >
      <b-col
        v-if="data.graph.user"
        md="6"
        cols="12"
      >
        <graph-current
          :data="data.graph.user"
          :categories="data.graph.categories"
          :colors="['#7367f0', '#28c76f']"
        />
      </b-col>
      <b-col
        v-if="data.graph.blog_cmt"
        md="6"
        cols="12"
      >
        <graph-current
          :data="data.graph.blog_cmt"
          :categories="data.graph.categories"
          :colors="['#00cfe8', '#ff9f43']"
        />
      </b-col>
    </b-row>
    <b-row class="match-height">
      <b-col cols="12">
        <graph-interview />
      </b-col>
    </b-row>

  </section>
</template>

<script>
import { BRow, BCol } from 'bootstrap-vue'
import CardDashboard from './CardDashboard.vue'
import GraphCurrent from './GraphCurrent.vue'
import GraphInterview from './GraphInterview.vue'
import dashboard from '@/store/api/Dashboard'
import utils from '@/store/utils'

export default {
  components: {
    BRow,
    BCol,

    CardDashboard,
    GraphCurrent,
    GraphInterview,
  },
  data() {
    return {
      data: null,
    }
  },
  created() {
    dashboard.getAdmin().then(resp => {
      const rs = resp.data
      this.data = rs.data
      utils.updateUser(rs.user)
      this.$ability.update([
        {
          action: 'manage',
          subject: rs.user.role,
        },
      ])
    }).catch(err => {
      console.log(err)
      this.data = null
    })
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/pages/dashboard-ecommerce.scss';
@import '~@core/scss/vue/libs/chart-apex.scss';
</style>
