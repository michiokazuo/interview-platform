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

      <account-setting-general
        v-if="userInfo"
        :user-info="userInfo"
      />
    </b-tab>
    <!--/ general tab -->

    <!-- change password tab -->
    <b-tab>

      <!-- title -->
      <template #title>
        <feather-icon
          icon="LockIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">Change Password</span>
      </template>

      <account-setting-password
        :user-info="userInfo"
      />
    </b-tab>
    <!--/ change password tab -->

    <b-tab v-if="userInfo.role.name === 'ROLE_CANDIDATE'">

      <!-- title -->
      <template #title>
        <feather-icon
          icon="LockIcon"
          size="18"
          class="mr-50"
        />
        <span class="font-weight-bold">CV</span>
      </template>

      <account-setting-c-v
        :cv-info="cvInfo"
      />
    </b-tab>
  </b-tabs>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue'
import AccountSettingPassword from './AccountSettingPassword.vue'
import AccountSettingCV from './AccountSettingCV.vue'
import auth from '@/store/api/Auth'
import cv from '@/store/api/CV'
import utils from '@/store/utils'

export default {
  components: {
    BTabs,
    BTab,
    AccountSettingGeneral,
    AccountSettingPassword,
    AccountSettingCV,
  },
  data() {
    return {
      userInfo: {
        avatar: 'images/local/avatar-user.png',
        social_network: {},
        role: {},
      },
      cvInfo: {
        detail: [],
      },
    }
  },
  beforeCreate() {
    auth.getUser().then(response => {
      const resp = response.data
      this.userInfo = resp.data
      utils.updateUser(resp.user)
      this.$ability.update([
        {
          action: 'manage',
          subject: resp.user.role,
        },
      ])
    }).catch(error => {
      console.log(error)
    })

    const userLogin = JSON.parse(localStorage.getItem('userData'))
    if (userLogin.role === 'ROLE_CANDIDATE') {
      cv.getCV().then(response => {
        const resp = response.data
        this.cvInfo = resp.data
        utils.updateUser(resp.user)
      }).catch(error => {
        console.log(error)
      })
    }
  },
}
</script>
