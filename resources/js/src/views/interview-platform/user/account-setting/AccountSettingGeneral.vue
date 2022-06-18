<template>
  <validation-observer
    ref="updateForm"
    #default="{invalid}"
  >
    <b-form @submit.prevent="updateInfo">
      <b-card>

        <!-- media -->
        <b-media no-body>
          <b-media-aside>
            <b-link>
              <b-img
                ref="previewEl"
                rounded
                :src="user.avatar"
                height="80"
              />
            </b-link>
            <!--/ avatar -->
          </b-media-aside>

          <b-media-body class="mt-75 ml-75">
            <!-- upload button -->
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              size="sm"
              class="mb-75 mr-75"
              @click="$refs.refInputEl.$el.click()"
            >
              Upload
            </b-button>

            <b-form-file
              ref="refInputEl"
              v-model="profileFile"
              accept="image/*"
              :hidden="true"
              plain
              @input="inputImageRenderer"
            />
            <!--/ upload button -->

            <!-- reset -->
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              variant="outline-secondary"
              size="sm"
              class="mb-75 mr-75"
              click.prevent="resetAvatar"
            >
              Reset
            </b-button>
            <!--/ reset -->
            <b-card-text>Allowed Image. Max size of 5MB</b-card-text>
          </b-media-body>
        </b-media>
        <!--/ media -->

        <!-- form -->

        <b-row class="mt-2">
          <b-col
            sm="8"
            md="6"
          >
            <b-form-group
              label="Name"
              label-for="account-name"
            >
              <validation-provider
                #default="{ errors }"
                name="Name"
                vid="name"
                rules="required"
              >
                <b-form-input
                  id="account-name"
                  v-model="user.name"
                  name="account-name"
                  placeholder="Name"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            sm="8"
            md="8"
            lg="6"
          >
            <b-form-group
              label="E-mail"
              label-for="account-e-mail"
            >
              <validation-provider
                #default="{ errors }"
                name="Email"
                vid="email"
                rules="required|email"
              >
                <b-form-input
                  id="account-e-mail"
                  v-model="user.email"
                  name="account-e-mail"
                  placeholder="Email"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            sm="8"
            md="8"
            lg="6"
            class=""
          >
            <b-form-group
              label="Phone"
              label-for="register-phone"
            >
              <validation-provider
                #default="{ errors }"
                name="phone"
                vid="phone"
                rules="required|phone"
              >
                <b-form-input
                  id="register-phone"
                  v-model="user.phone"
                  name="register-phone"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Phone"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            sm="8"
            md="8"
            lg="6"
            class=""
          >
            <b-form-group
              label="Address"
              label-for="register-address"
            >
              <validation-provider
                #default="{ errors }"
                name="Address"
                vid="address"
                rules="required"
              >
                <b-form-input
                  id="register-address"
                  v-model="user.address"
                  name="register-address"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Address"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            sm="8"
            md="8"
            lg="6"
            class=""
          >
            <b-form-group
              label="Role"
              label-for="register-role"
            >
              <validation-provider
                #default="{ errors }"
                name="Role"
                vid="role"
                rules="required"
              >
                <b-form-select
                  id="register-role"
                  v-model="user.role"
                  name="register-role"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Role"
                  :options="options"
                  disabled
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            sm="8"
            md="8"
            lg="6"
            class=""
          >
            <b-form-group
              label="Major"
              label-for="register-major"
            >
              <validation-provider
                #default="{ errors }"
                name="Major"
                vid="major"
                rules="required"
              >
                <b-form-input
                  id="register-major"
                  v-model="user.major"
                  name="register-major"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Major"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            v-if="user.role && user.role.id == 3"
            sm="8"
            md="8"
            lg="6"
            class=""
          >
            <b-form-group
              label="Url"
              label-for="register-url"
            >
              <validation-provider
                #default="{ errors }"
                name="Url"
                vid="url"
                rules="|url"
              >
                <b-form-input
                  id="register-url"
                  v-model="user.url"
                  name="register-url"
                  :state="errors.length > 0 ? false:null"
                  placeholder="url"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col
            cols="12"
            class=""
          >
            <b-form-group
              label="Introduction"
              label-for="register-introduction"
            >
              <validation-provider
                #default="{ errors }"
                name="Introduction"
                vid="introduction"
                rules=""
              >
                <quill-editor
                  id="register-introduction"
                  v-model="user.introduction"
                  name="register-introduction"
                  :state="errors.length > 0 ? false:null"
                  :options="snowOption"
                  aria-placeholder="Introduction"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>

        </b-row>
      </b-card>

      <b-card>
        <b-row>
          <b-col cols="12">
            <div class="d-flex align-items-center mb-2">
              <feather-icon
                icon="LinkIcon"
                size="18"
              />
              <h4 class="mb-0 ml-75">
                Social Links
              </h4>
            </div>
          </b-col>

          <!-- twitter -->
          <b-col
            sm="8"
            md="6"
          >
            <b-form-group
              label-for="account-twitter"
              label="Twitter"
            >
              <validation-provider
                #default="{ errors }"
                name="Twitter"
                vid="twitter"
                rules="|url"
              >
                <b-form-input
                  id="account-twitter"
                  v-model="user.social_network.twitter"
                  name="account-twitter"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Add link"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ twitter -->

          <!-- facebook -->
          <b-col
            sm="8"
            md="6"
          >
            <b-form-group
              label-for="account-facebook"
              label="Facebook"
            >
              <validation-provider
                #default="{ errors }"
                name="Facebook"
                vid="facebook"
                rules="|url"
              >
                <b-form-input
                  id="account-facebook"
                  v-model="user.social_network.facebook"
                  name="account-facebook"
                  placeholder="Add link"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ facebook -->

          <!-- linkedin -->
          <b-col
            sm="8"
            md="6"
          >
            <b-form-group
              label-for="account-linkedin"
              label="LinkedIn"
            >
              <validation-provider
                #default="{ errors }"
                name="Linkedin"
                vid="linkedin"
                rules="|url"
              >
                <b-form-input
                  id="account-linkedin"
                  v-model="user.social_network.linkedIn"
                  name="account-linkedin"
                  placeholder="Add link"
                  :state="errors.length > 0 ? false:null"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!-- linkedin -->
        </b-row>
      </b-card>

      <b-row>
        <b-col
          cols="12 d-flex justify-content-center"
        >
          <!-- buttons -->
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="mt-1 mr-1"
            type="submit"
            :disabled="invalid"
          >
            Save changes
          </b-button>
          <b-button
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            type="reset"
            class="mt-1 ml-25"
            variant="outline-secondary"
            @click.prevent="resetForm"
          >
            Cancel
          </b-button>
        </b-col>
      </b-row>

    </b-form>
  </validation-observer>
</template>

<script>
import {
  BFormFile, BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BCardText, BMedia, BMediaAside, BMediaBody, BLink, BImg,
  BFormTextarea, BFormSelect,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import Ripple from 'vue-ripple-directive'
import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils'
import { ref } from '@vue/composition-api'
import { quillEditor } from 'vue-quill-editor'
import {
  required, email, alpha, phone, url, image, size,
} from '@validations'
import auth from '@/store/api/Auth'
import utils from '@/store/utils'

export default {
  components: {
    BButton,
    BForm,
    BImg,
    BFormFile,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BFormSelect,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BFormTextarea,
    quillEditor,
    // validations
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  props: {
    userInfo: {
      type: Object,
      default: () => ({
        avatar: 'images/local/avatar-user.png',
        social_network: {},
      }),
    },
  },
  data() {
    return {
      options: [
        { value: { id: 3, name: 'ROLE_COMPANY' }, text: 'Company' },
        { value: { id: 2, name: 'ROLE_CANDIDATE' }, text: 'Candidate' },
      ],
      user: { ...this.userInfo },
      optionsLocal: {
        avatar: 'images/local/avatar-user.png',
      },
      profileFile: null,
      snowOption: {
        theme: 'snow',
      },
    }
  },
  watch: {
    userInfo(newVal) {
      this.user = { ...newVal }
      this.user.social_network = { ...newVal.social_network ?? {} }
      this.user.role = { ...newVal.role }
    },
    profileFile() {
      if (this.profileFile) {
        try {
          const reader = new FileReader()
          reader.onload = e => {
            this.user.avatar = e.target.result
          }
          reader.readAsDataURL(this.profileFile)
        } catch (e) {
          console.log(e)
        }
      }
    },
  },
  methods: {
    resetAvatar() {
      this.$refInputEl.inputFile.reset()
      this.user.avatar = this.userInfo.avatar
      this.profileFile = null
    },
    resetForm() {
      this.user = { ...this.userInfo }
      this.user.social_network = { ...this.userInfo.social_network ?? {} }
      this.user.role = { ...this.userInfo.role }
    },
    updateInfo() {
      this.$refs.updateForm.validate().then(success => {
        if (success) {
          const user = { ...this.user }
          user.role = this.userInfo.role
          user.phone = user.phone.replace('+84', '0')
          user.role_id = this.user.role.id
          user.role_name = this.user.role.name
          user.social_network = JSON.stringify(this.user.social_network)
          if (this.profileFile) {
            user.avatar = this.profileFile
          } else {
            delete user.avatar
          }
          delete user.role
          delete user.owner
          const formData = new FormData()
          /* eslint guard-for-in: "error" */
          for (const key in user) {
            formData.append(key, user[key])
          }

          auth.updateUser(formData)
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
                  title: 'Update success',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
              this.$emit('user-info', resp.data)
            })
            .catch(error => {
              console.log(error)
              this.$refs.updateForm.setErrors([])
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'Error',
                  icon: 'AlertTriangleIcon',
                  variant: 'danger',
                  text: 'Please check input again. Some fields is equal to another (email, phone and url(if company) is unique).',
                },
              })
            })
        }
      })
    },
  },
  setup() {
    const refInputEl = ref(null)
    const previewEl = ref(null)

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, previewEl)

    return {
      refInputEl,
      previewEl,
      inputImageRenderer,
    }
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/libs/quill.scss';
</style>
