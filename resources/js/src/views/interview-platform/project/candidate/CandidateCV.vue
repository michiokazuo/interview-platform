<template>
  <validation-observer
    ref="formCV"
  >
    <b-form>
      <b-card>
        <!-- form -->

        <b-row>
          <!-- website -->
          <b-col cols="12">
            <b-form-group
              label-for="link"
              label="Link"
            >
              <validation-provider
                #default="{ errors }"
                name="Link"
                rules="|url"
              >
                <b-form-input
                  id="link"
                  v-model="cv.link"
                  placeholder="Link CV"
                  :state="errors.length > 0 ? false:null"
                  disabled
                  readonly
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
        <!--/ website -->
        </b-row>

      </b-card>

      <b-card>
        <b-row>
          <b-col cols="12">
            <div class="d-flex align-items-center mb-2">
              <feather-icon
                icon="InfoIcon"
                size="18"
              />
              <h4 class="mb-0 ml-75">
                CV Information
              </h4>
            </div>
          </b-col>
          <b-col cols="12">
            <div>
              <b-form
                ref="form"
                :style="{height: trHeight}"
                class="repeater-form"
              >

                <!-- Row Loop -->
                <b-row
                  v-for="(item) in items"
                  :id="item.id"
                  :key="item.id"
                  ref="row"
                  class="justify-content-end align-items-center"
                >

                  <!-- Item Key -->
                  <b-col sm="6">
                    <b-form-group
                      label="Key"
                      label-for="item-key"
                    >
                      <validation-provider
                        #default="{ errors }"
                        name="Key"
                        rules="required"
                      >
                        <b-form-input
                          id="item-key"
                          v-model="cv.detail[`key-${item.id}`]"
                          type="text"
                          placeholder="Key"
                          :state="errors.length > 0 ? false:null"
                          disabled
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>

                  <!-- Item Value -->
                  <b-col sm="6">
                    <b-form-group
                      label="Value"
                      label-for="item-value"
                    >
                      <validation-provider
                        #default="{ errors }"
                        name="Value"
                        rules="required"
                      >
                        <b-form-textarea
                          id="item-value"
                          v-model="cv.detail[`value-${item.id}`]"
                          type="text"
                          placeholder="Value"
                          :state="errors.length > 0 ? false:null"
                          rows="2"
                          disabled
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>
                </b-row>

              </b-form>
            </div>
          </b-col>
        </b-row>
      </b-card>
    </b-form>
  </validation-observer>
</template>

<script>
import {
  BForm,
  BFormGroup,
  BFormInput,
  BRow,
  BCol,
  BCard,
  BFormTextarea,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { heightTransition } from '@core/mixins/ui/transition'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

export default {
  components: {
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BFormTextarea,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  mixins: [heightTransition],
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
      items: [],
      nextTodoId: 2,
    }
  },
  mounted() {
    this.initTrHeight()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
    if (this.cv) {
      this.convertDataToShow(this.cv)
    }
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {
    resetForm() {
      this.convertData(this.cvInfo)
    },
    repeatAgain() {
      this.items.push({
        id: this.nextTodoId += this.nextTodoId,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs.row[0].offsetHeight)
      })
    },
    removeItem(id, index) {
      delete this.cv.detail[`key-${id}`]
      delete this.cv.detail[`value-${id}`]
      this.items.splice(index, 1)
      this.trTrimHeight(this.$refs.row[0].offsetHeight)
    },
    initTrHeight() {
      this.trSetHeight(null)
      this.$nextTick(() => {
        this.trSetHeight(this.$refs.formCV.scrollHeight)
      })
    },
    convertDataToShow(data) {
      this.cv.link = data.link
      const cvDetail = data.detail ?? []
      // eslint-disable-next-line no-restricted-syntax
      for (const item of cvDetail) {
        this.repeatAgain()
        const { id } = this.items.slice(-1)[0]
        this.cv.detail[`key-${id}`] = item.key
        this.cv.detail[`value-${id}`] = item.value
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
