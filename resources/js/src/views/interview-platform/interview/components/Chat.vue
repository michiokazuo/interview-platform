<template>
  <div :class="chatIsOpen ? 'chat-wrapper open' : 'chat-wrapper'">
    <div>
      <button
        class="chat"
        @click="handleChatClick"
      >
        <img
          v-if="chatIsOpen"
          class="icon"
          :src="close"
          alt=""
        >
        <img
          v-else
          class="icon"
          :src="chat"
          alt=""
        >
      </button>
    </div>

    <div class="chat-container">
      <div
        class="messages"
      >
        <p
          v-for="(mess, i) in messages"
          :key="i"
          class="chat-message"
        >
          <span class="chat-name"><strong>{{ mess.name ? mess.name : '' }}: </strong></span>{{ mess.message ? mess.message : '' }}
        </p>
      </div>

      <form @submit="submitForm">
        <div class="input">
          <textarea
            id="message"
            v-model="text"
            type="text"
            class="form-control"
            placeholder="Type a message..."
            @keydown.enter="submitForm"
          />
        </div>

        <button
          class="submit-button"
          type="submit"
        >
          <img
            :src="send"
            alt=""
          >
        </button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Chat',
  // eslint-disable-next-line vue/require-prop-types
  props: ['sendMessage', 'messages'],
  data() {
    return {
      chatIsOpen: false,
      close: require('@/assets/images/meeting/x.svg'),
      chat: require('@/assets/images/meeting/chat.svg'),
      send: require('@/assets/images/meeting/send.svg'),
      text: '',
    }
  },
  methods: {
    // Toggle chat's view (open/closed)
    handleChatClick() {
      this.chatIsOpen = !this.chatIsOpen
    },
    // Send chat message using prop method from Call.vue
    submitForm(e) {
      e.preventDefault()

      this.sendMessage(this.text)
      this.text = ''
    },
  },
}
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Ropa+Sans&display=swap");

.chat-wrapper {
  position: absolute;
  right: 0;
  top: 0;
  width: 348px;
  height: 100%;
  transition: right 0.5s ease-out;
  right: -300px;
  display: flex;
  align-items: center;
  z-index: 100;
}
.chat-wrapper.open {
  right: 0;
}
.chat-container {
  background-color: #cbc6c6;
  box-shadow: 0 4px 24px 0 rgb(34 41 47 / 10%);
  border-radius: 10px;
  width: 300px;
  display: flex;
  flex-direction: column;
  padding: 24px;
  height: calc(100% - 48px);
}
button.chat {
  background-color: #cbc6c6;
  border: none;
  cursor: pointer;
  border-radius: 16px 0 0 16px;
  padding: 16px 14px 13px 18px;
  box-shadow: 0 5px 25px 0 #1a2d3fa6;
}
.messages {
  flex: 1;
  padding-right: 32px;
}
.input {
  display: flex;
  flex-direction: column;
  flex: 1;
  align-items: flex-start;
}
.input textarea {
  width: 100%;
  border: none;
  resize: none;
  outline: none;
  font-family: "Ropa Sans", sans-serif;
  font-size: 16px;
  border-radius: 10px;
  background-color: #f7f7f7;
}
.input textarea::placeholder {
  font-family: "Ropa Sans", sans-serif;
  font-size: 16px;
}
form {
  display: flex;
  border-bottom: 2px solid #c8d1dc;
}

.submit-button {
  padding: 4px;
  margin: 0 0 0 16px;
  border: none;
  outline: none;
  border-radius: 10px;
  background-color: #f7f7f7;
}

.chat-message {
  color: #121a24;
  text-align: left;
  font-size: 14px;
  line-height: 18px;
  margin: 0 0 20px;
}
.chat-message .chat-name {
  color: #6b7785;
}

@media screen and (max-width: 700px) {
  .chat-container {
    width: calc(100% - 104px);
    right: calc((100% + 56px) * -1);
  }
}
</style>
