<template>
  <!-- <div> -->
  <home
    v-if="appState === 'idle'"
    :join-call="joinCall"
  />
  <call
    v-else-if="appState === 'incall'"
    :leave-call="leaveCall"
    :name="name"
    :room-url="roomUrl"
  />
  <!-- </div> -->
</template>

<script>
import Call from './components/Call.vue'
import Home from './components/Home.vue'

export default {
  components: {
    Call,
    Home,
  },
  data() {
    return {
      appState: 'idle',
      name: 'Guest',
      roomUrl: null,
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
      this.appState = 'idle'
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
