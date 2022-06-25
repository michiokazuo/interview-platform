<template>
  <validation-observer
    ref="formCV"
    v-slot="{invalid}"
  >
    <b-form @submit.prevent="storeCV">
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
                v-slot="{ errors }"
                name="Link"
                rules="|url"
              >
                <b-form-input
                  id="link"
                  v-model="cv.link"
                  placeholder="Link CV"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
        <!--/ website -->
        </b-row>

      </b-card>

      <b-card
        v-for="(field, f_index) in items_fields"
        :key="f_index"
      >
        <b-row>
          <b-col cols="12">
            <div class="d-flex align-items-center mb-2">
              <feather-icon
                icon="InfoIcon"
                size="18"
              />
              <h4 class="mb-0 ml-75 text-capitalize">
                CV Information - {{ field }}
              </h4>
            </div>
          </b-col>
          <b-col cols="12">
            <div>
              <b-form
                :ref="`${field}_form`"
                :style="{height: trHeight}"
                class="repeater-form"
                @submit.prevent="repeatAgain"
              >

                <!-- Row Loop -->
                <b-row
                  v-for="(item, index) in items[`${field}`]"
                  :id="item.id"
                  :key="item.id"
                  :ref="`${field}_row`"
                  class="justify-content-end align-items-center"
                >

                  <!-- Item Key -->
                  <b-col
                    lg="3"
                    md="4"
                    sm="5"
                  >
                    <b-form-group
                      label="Key"
                      label-for="item-key"
                    >
                      <validation-provider
                        v-slot="{ errors }"
                        name="Key"
                        rules="required"
                      >
                        <b-form-input
                          id="item-key"
                          v-model="cv.detail[`key-${item.id}`]"
                          type="text"
                          placeholder="Key"
                          :state="errors.length > 0 ? false:null"
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>

                  <!-- Item Value -->
                  <b-col
                    lg="6"
                    md="5"
                    sm="7"
                  >
                    <b-form-group
                      label="Value"
                      label-for="item-value"
                    >
                      <validation-provider
                        v-slot="{ errors }"
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
                        />
                        <small class="text-danger">{{ errors[0] }}</small>
                      </validation-provider>
                    </b-form-group>
                  </b-col>

                  <!-- Remove Button -->
                  <b-col
                    lg="3"
                    md="3"
                    sm="5"
                    class="mb-50 d-flex justify-content-end"
                  >
                    <b-button
                      v-ripple.400="'rgba(234, 84, 85, 0.15)'"
                      variant="outline-danger"
                      class="mt-0 mt-md-2"
                      @click="removeItem(item.id, index, field)"
                    >
                      <feather-icon
                        icon="XIcon"
                        class="mr-25"
                      />
                      <span>Delete</span>
                    </b-button>
                  </b-col>
                  <b-col cols="12">
                    <hr>
                  </b-col>
                </b-row>

              </b-form>
            </div>
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              @click="repeatAgain(field)"
            >
              <feather-icon
                icon="PlusIcon"
                class="mr-25"
              />
              <span>Add New</span>
            </b-button>
          </b-col>
        </b-row>
      </b-card>

      <b-col cols="12 d-flex justify-content-center">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          type="submit"
          variant="primary"
          class="mt-1 mr-1"
          :disabled="invalid"
        >
          Save changes
        </b-button>
        <b-button
          v-ripple.400="'rgba(186, 191, 199, 0.15)'"
          type="reset"
          class="mt-1"
          variant="outline-secondary"
          @click.prevent="resetForm"
        >
          Reset
        </b-button>
      </b-col>
    </b-form>
  </validation-observer>
</template>

<script>
import {
  BButton,
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
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import cv from '@/store/api/CV'
import utils from '@/store/utils'

export default {
  components: {
    BButton,
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
        detail: [],
      }),
    },
  },
  data() {
    return {
      cv: {
        detail: {
        },
      },
      items_fields: ['profile', 'education', 'experience', 'skills', 'others'],
      items: {
        experience: [],
        education: [],
        skills: [],
        others: [],
        profile: [],
      },
      nextTodoId: 1,
    }
  },
  watch: {
    cvInfo(val) {
      this.convertDataToShow(val)
    },
  },
  mounted() {
    this.initTrHeight()
  },
  created() {
    window.addEventListener('resize', this.initTrHeight)
  },
  destroyed() {
    window.removeEventListener('resize', this.initTrHeight)
  },
  methods: {
    resetForm() {
      this.initTrHeight()
      this.items = {
        experience: [],
        education: [],
        skills: [],
        others: [],
        profile: [],
      }
      this.nextTodoId = 1
      this.cv.detail = {}
      this.convertDataToShow(this.cvInfo)
    },
    repeatAgain(type) {
      this.items[`${type}`].push({
        id: `${type}_${this.nextTodoId += this.nextTodoId}`,
      })

      this.$nextTick(() => {
        this.trAddHeight(this.$refs[`${type}_row`][0].offsetHeight)
      })
    },
    removeItem(id, index, field) {
      delete this.cv.detail[`key-${id}`]
      delete this.cv.detail[`value-${id}`]
      this.items[`${field}`].splice(index, 1)
      this.trTrimHeight(this.$refs[`${field}_row`][0].offsetHeight)
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
        this.repeatAgain(item.type)
        const { id } = this.items[`${item.type}`].slice(-1)[0]
        this.cv.detail[`key-${id}`] = item.key
        this.cv.detail[`value-${id}`] = item.value
      }
    },
    convertDataToSave() {
      const rs = {
        link: '',
        detail: [],
      }
      rs.link = this.cv.link
      const cvDetail = this.cv.detail ?? {}

      for (const item in this.items) {
        for (const val of this.items[item]) {
          rs.detail.push({
            key: cvDetail[`key-${val.id}`],
            value: cvDetail[`value-${val.id}`],
            type: item,
          })
        }
      }

      return rs
    },
    storeCV() {
      this.$refs.formCV.validate().then(success => {
        if (success) {
          const data = this.convertDataToSave(this.cv)
          console.log(data)
          cv.storeCV(data)
            .then(response => {
              const resp = response.data
              utils.updateUser(resp.user)
              this.$ability.update([
                {
                  action: 'manage',
                  subject: resp.user.role,
                },
              ])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Update CV success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })

              this.$emit('cv-info', resp.data)
            })
            .catch(error => {
              console.log(error)
              this.$refs.formCV.setErrors([])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Error',
                  icon: 'AlertTriangleIcon',
                  variant: 'danger',
                  text: 'Something error. Please try again!!!',
                },
              })
            })
        }
      })
    },
  },
}
</script>

<style lang="scss" scoped>
@import "~@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/vue/libs/vue-flatpicker.scss";
</style>

<style lang="scss" scoped>
.repeater-form {
  overflow: hidden;
  transition: .35s height;
}
</style>
