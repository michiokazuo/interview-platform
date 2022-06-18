<template>
  <div id="user-profile">
    <b-card
      class="profile-header mb-2"
      :img-src="coverImg"
      img-top
      alt="cover photo"
      body-class="p-0"
    >
      <!-- profile picture -->
      <div class="position-relative">
        <div class="profile-img-container d-flex align-items-center">
          <div class="profile-img">
            <b-img
              :src="headerData.avatar"
              rounded
              fluid
              alt="profile photo"
            />
          </div>
          <!-- profile title -->
          <div class="profile-title ml-3">
            <h2 class="text-white">
              {{ headerData.name || headerData.fullName }}
            </h2>
            <p class="text-white">
              {{ headerData.major || '' }}
            </p>
          </div>
        <!--/ profile title -->
        </div>
      </div>
      <!--/ profile picture -->

      <!-- profile navbar -->
      <div class="profile-header-nav bg-light">
        <b-navbar
          toggleable="md"
        >
          <!-- toggle button -->
          <b-navbar-toggle
            class="ml-auto"
            target="nav-text-collapse"
          >
            <feather-icon
              icon="AlignJustifyIcon"
              size="21"
            />
          </b-navbar-toggle>
          <!--/ toggle button -->
        </b-navbar>
      </div>
    <!--/ profile navbar -->
    </b-card>

    <b-card>
      <!-- collapse -->
      <b-tabs
        pills
        class="mt-1 mt-md-0"
        nav-class="mb-0"
      >
        <b-tab active>
          <template #title>
            <span class="d-none d-sm-inline">Application</span>
          </template>
          <application :applications="interview.applications" />
        </b-tab>

        <b-tab>
          <template #title>
            <span class="d-none d-sm-inline">Practice</span>
          </template>
          <practice :practices="interview.practices" />
        </b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>

<script>
import {
  BCard, BImg, BNavbar, BNavbarToggle, BCollapse, BTabs, BTab,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import Practice from './Practice.vue'
import Application from './Application.vue'
import interview from '@/store/api/Interview'
import utils from '@/store/utils'

export default {
  components: {
    BCard,
    BTabs,
    BNavbar,
    BNavbarToggle,
    BImg,
    Practice,
    Application,
    BTab,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      headerData: {},
      interview: {
        practices: [],
        applications: [],
      },
      coverImg: require('@/assets/images/profile/user-uploads/timeline.jpg'),
    }
  },
  created() {
    this.headerData = JSON.parse(localStorage.getItem('userData'))
    interview.showByUser(this.headerData.candidate_id).then(resp => {
      const rs = resp.data
      this.interview = rs.data
      this.headerData = rs.user
      utils.updateUser(rs.user)
      this.$ability.update([
        {
          action: 'manage',
          subject: rs.user.role,
        },
      ])
    }).catch(err => {
      console.log(err)
      this.interview = {
        practices: [],
        applications: [],
      }
    })
  },
}
</script>

<style lang="scss" >
@import '~@core/scss/vue/pages/page-profile.scss';
</style>
