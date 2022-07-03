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
          :data="data.interview"
          :colors="['#7367f0', '#28c76f', '#ea5455']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.questions"
          :colors="['#796fe3', '#00cfe8']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.news"
          :colors="['#00cfe8', '#ff9f43']"
        />
      </b-col>
      <b-col
        xl="4"
        md="6"
      >
        <card-dashboard
          :data="data.blog_cmt"
          :colors="['#ff9f43', '#20c997']"
        />
      </b-col>
      <b-col
        xl="8"
        md="6"
      >
        <d-b-company-cpn :data="data.others" />
      </b-col>
    </b-row>

    <b-row class="match-height">
      <b-col cols="12">
        <b-card title="Calendar">
          <calendar :interview-questions="groupQuestionInterviews" />
        </b-card>

      </b-col>
    </b-row>

  </section>
</template>

<script>
import { BRow, BCol, BCard } from 'bootstrap-vue'
import CardDashboard from './CardDashboard.vue'
import Calendar from '../project/calendar/Calendar.vue'
import DBCompanyCpn from './DBCompanyCpn.vue'
import dashboard from '@/store/api/Dashboard'
import utils from '@/store/utils'
import groupQs from '@/store/api/GroupQuestion'

export default {
  components: {
    BRow,
    BCol,
    BCard,

    CardDashboard,
    Calendar,
    DBCompanyCpn,
  },
  data() {
    return {
      data: null,
      groupQuestionInterviews: [],
    }
  },
  created() {
    dashboard.getCompany().then(resp => {
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
    groupQs.showGroupInterviews().then(resp => {
      const rs = resp.data
      this.groupQuestionInterviews = rs.data?.map(item => ({
        id: item.id,
        title: item.title,
      }))
      utils.updateUser(rs.user)
      this.$ability.update([
        {
          action: 'manage',
          subject: rs.user.role,
        },
      ])
    }).catch(err => {
      console.log(err)
      this.groupQuestionInterviews = []
    })
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/pages/dashboard-ecommerce.scss';
@import '~@core/scss/vue/libs/chart-apex.scss';
</style>
