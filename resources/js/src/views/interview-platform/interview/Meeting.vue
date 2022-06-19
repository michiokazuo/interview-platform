<template>
  <home
    v-if="appState === 'idle'"
    :join-call="joinCall"
  />
  <call
    v-else-if="appState === 'incall' && id && interview"
    :leave-call="leaveCall"
    :name="name"
    :room-url="roomUrl"
    :interview="interview"
  />
  <b-row v-else>
    <b-col cols="12">
      <b-card
        no-body
        class="faq-search pt-5 pb-5"
        :style="{backgroundImage:`url(${require('@/assets/images/banner/banner.png')})`}"
      >
        <b-card-body class="text-center">
          <h2 class="text-primary">
            Nothing to show...
          </h2>
          <b-card-text class="mb-2">
            Please try again!!!
          </b-card-text>
        </b-card-body>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import {
  BCol, BRow, BCard, BCardText, BCardBody,
} from 'bootstrap-vue'
import Call from './components/Call.vue'
import Home from './components/Home.vue'
import interview from '@/store/api/Interview'

export default {
  components: {
    Call,
    Home,
    BCol,
    BRow,
    BCard,
    BCardText,
    BCardBody,
  },
  data() {
    return {
      appState: 'idle',
      name: 'Guest',
      roomUrl: null,
      userOn: {},
      interview: null,
      savePractice: false,
    }
  },
  created() {
    const { id } = this.$route.params
    if (id) {
      this.id = id - 0
      this.getData()
    } else {
      this.id = null
    }
  },
  methods: {
    /**
     * Set name and URL values entered in Home.vue form in data obj
     */
    joinCall(name, url) {
      this.name = name
      this.roomUrl = url
      this.appState = 'incall'
    },
    // Reset app state to return to the home screen after leaving call
    leaveCall() {
      // this.savePractice = true
      // this.appState = 'idle'
    },
    getData() {
      interview.findById(this.id).then(resp => {
        const rs = resp.data
        this.interview = rs.data
        this.userOn = rs.user
        this.$ability.update([
          {
            action: 'manage',
            subject: rs.user.role,
          },
        ])
        this.userOn = rs.user
        this.joinCall(this.userOn.fullName, this.interview.room)
      }).catch(err => {
        console.log(err)
        this.interview = null
      })
    },
  },
}
</script>

<style>
#app .app-content.content {
  padding: 0!important;
}

#app .footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
}
</style>
