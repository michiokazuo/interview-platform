<template>
  <main class="d-flex align-items-center justify-content-center h-100 pt-3">
    <div class="wrapper">
      <h1 class="text-center">Meeting interview</h1>

      <form
        class="join-form d-flex flex-column m-auto"
        @submit="joinWithName"
      >
        <div class="flex-column">
          <label for="name">Your name</label>
          <input
            id="name"
            v-model="name"
            type="text"
            required
            placeholder="Enter your name"
            class="form-control"
          >
          <label for="name">Meeting URL</label>
          <input
            id="url"
            v-model="url"
            type="text"
            class="form-control"
            placeholder="https://your-daily-domain.daily.co/room-name"
            required
          >
        </div>
        <div class="submit-container">
          <button type="submit">
            Start meeting
          </button>
        </div>
      </form>

      <p class="text-secondary mt-1">
        If prompted, select "Allow" to use your camera and mic for this call
      </p>
    </div>
  </main>
</template>

<script>
export default {
  name: 'Home',
  props: {
    joinCall: {
      type: Function,
    },
  },
  data() {
    return {
      name: '',
      url: '',
    }
  },
  methods: {
    // Submit form with prop method defined in App.vue
    joinWithName() {
      this.joinCall(this.name, this.url)
    },
  },
}
</script>

<style scoped>
.wrapper {
  max-width: 1200px;
  margin: auto;
  padding: 0 16px;
}
.join-form label {
  font-size: 12px;
  color: #6b7785;
  text-align: left;
  margin: 8px 0 4px;
  font-weight: bold;
}
.join-form div {
  display: flex;
  justify-content: center;
}
.join-form .submit-container button {
  padding: 7px 16px 9px;
  font-size: 15px;
  font-weight: bold;
  border: none;
  border-radius: 12px;
  margin-top: 16px;
  margin-bottom: 12px;
  background-color: #1bebb9;
  cursor: pointer;
}
</style>
