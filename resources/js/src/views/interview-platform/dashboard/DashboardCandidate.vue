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
          :data="data.application"
          :colors="['#7367f0', '#28c76f', '#ea5455']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.practice"
          :colors="['#796fe3', '#00cfe8', '#ff9f43', '#20c997']"
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

    <b-row class="match-height">
      <b-col cols="12">
        <b-card title="Calendar">
          <calendar />
        </b-card>

      </b-col>
    </b-row>

  </section>
</template>

<script>
import { BRow, BCol, BCard } from 'bootstrap-vue'
import CardDashboard from './CardDashboard.vue'
import Calendar from '../project/calendar/Calendar.vue'
import dashboard from '@/store/api/Dashboard'
import utils from '@/store/utils'

export default {
  components: {
    BRow,
    BCol,
    BCard,

    CardDashboard,
    Calendar,
  },
  data() {
    return {
      data: null,
    }
  },
  created() {
    dashboard.getCandidate().then(resp => {
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
