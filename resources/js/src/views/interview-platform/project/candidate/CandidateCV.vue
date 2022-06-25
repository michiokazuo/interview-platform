<template>
  <b-row v-if="cv">
    <b-card class="col-12">
      <!-- about -->
      <b-button
        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
        variant="primary"
      >
        <b-link
          v-if="cv.link"
          :href="cv.link"
          class="font-weight-bold mb-2 text-white"
          target="_blank"
        >
          Link CV preference
        </b-link>
        <span v-else> No preference </span>
      </b-button>
    </b-card>
    <b-card
      v-for="(field, f_index) in items_fields"
      :key="f_index"
      class="col-12"
    >
      <b-card-title class="text-capitalize">
        {{ field }}
      </b-card-title>
      <!-- about -->
      <div
        v-for="(item, index) in items[`${field}`]"
        :key="item.id"
        :class="index ? 'mt-2':''"
      >
        <h5 class="text-capitalize mb-75">
          {{ item.key }}
        </h5>
        <b-card-text>
          {{ item.value }}
        </b-card-text>
      </div>
    </b-card>
  </b-row>
  <b-row v-else>
    <b-card class="col-12">
      <b-card-text>
        No CV
      </b-card-text>
    </b-card>
  </b-row>
</template>

<script>
import {
  BCard,
  BCardText,
  BCardTitle,
  BButton,
  BLink,
  BRow,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'

export default {
  components: {
    BCard,
    BCardText,
    BCardTitle,
    BButton,
    BLink,
    BRow,
  },
  directives: {
    Ripple,
  },
  props: {
    cvInfo: {
      type: Object,
      default: () => ({
        detail: {},
      }),
    },
  },
  data() {
    return {
      cv: {
        ...this.cvInfo,
      },
      items_fields: ['profile', 'education', 'experience', 'skills', 'others'],
      items: {
        experience: [],
        education: [],
        skills: [],
        others: [],
        profile: [],
      },
    }
  },
  created() {
    if (this.cv) {
      this.convertDataToShow(this.cv)
    }
  },
  methods: {
    convertDataToShow(data) {
      this.cv.link = data.link
      const cvDetail = data.detail ?? []
      // eslint-disable-next-line no-restricted-syntax
      for (const item of cvDetail) {
        this.items[`${item.type}`].push(item)
      }
    },
  },
}
</script>

<style lang="scss">
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>

<style lang="scss" scoped>
.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
</style>
