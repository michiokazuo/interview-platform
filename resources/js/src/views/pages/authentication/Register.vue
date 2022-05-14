<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">

      <!-- Brand logo-->
      <b-link class="brand-logo">
        <vuexy-logo />

        <h2 class="brand-text text-primary ml-1">
          Interview Platform
        </h2>
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col
        lg="6"
        class="d-none d-lg-flex align-items-center p-5"
      >
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img
            fluid
            :src="imgUrl"
            alt="Register V2"
          />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Register-->
      <b-col
        lg="6"
        class="d-flex align-items-center auth-bg pt-5"
      >
        <b-row class="mx-auto">
          <b-card-title class="mb-1 w-100">
            Khám phá 🚀
          </b-card-title>
          <b-card-text class="mb-2 w-100">
            Đăng ký tài khoản!
          </b-card-text>

          <!-- form -->
          <validation-observer
            ref="registerForm"
            #default="{invalid}"
          >
            <b-form
              class="auth-register-form mt-2"
              @submit.prevent="register"
            >
              <b-row class="mx-auto justify-content-center">
                <b-col
                  sm="8"
                  md="8"
                  lg="6"
                  class=""
                >
                  <!-- name -->
                  <b-form-group
                    label="Name"
                    label-for="register-username"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Name"
                      vid="name"
                      rules="required"
                    >
                      <b-form-input
                        id="register-username"
                        v-model="user.name"
                        name="register-username"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Tên"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <!-- email -->
                <b-col
                  sm="8"
                  md="8"
                  lg="6"
                  class=""
                >
                  <b-form-group
                    label="Email"
                    label-for="register-email"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Email"
                      vid="email"
                      rules="required|email"
                    >
                      <b-form-input
                        id="register-email"
                        v-model="user.email"
                        name="register-email"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Email"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <!-- password -->
                <b-col
                  sm="8"
                  md="8"
                  lg="6"
                  class=""
                >
                  <b-form-group
                    label-for="register-password"
                    label="Password"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Password"
                      vid="password"
                      rules="required|password"
                    >
                      <b-input-group
                        class="input-group-merge"
                        :class="errors.length > 0 ? 'is-invalid':null"
                      >
                        <b-form-input
                          id="register-password"
                          v-model="user.password"
                          class="form-control-merge"
                          :type="passwordFieldType"
                          :state="errors.length > 0 ? false:null"
                          name="register-password"
                          placeholder="············"
                        />
                        <b-input-group-append is-text>
                          <feather-icon
                            :icon="passwordToggleIcon"
                            class="cursor-pointer"
                            @click="togglePasswordVisibility"
                          />
                        </b-input-group-append>
                      </b-input-group>
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
                  sm="8"
                  md="8"
                  lg="6"
                  class=""
                >
                  <b-form-group
                    label="Facebook"
                    label-for="register-fb"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Facebook"
                      vid="fb"
                      rules="|url"
                    >
                      <b-form-input
                        id="register-fb"
                        v-model="user.social_network.fb"
                        name="register-fb"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Facebook"
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
                    label="Linkedin"
                    label-for="register-linkedin"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Linkedin"
                      vid="linkedin"
                      rules="|url"
                    >
                      <b-form-input
                        id="register-linkedin"
                        v-model="user.social_network.linkedin"
                        name="register-linkedin"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Linkedin"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>
                <b-col
                  sm="8"
                  md="8"
                  lg="6"
                  class="mx-auto"
                >
                  <b-form-group
                    label="Avatar"
                    label-for="register-avatar"
                  >
                    <validation-provider
                      #default="{ errors }"
                      name="Avatar"
                      vid="avatar"
                      rules="|image|size:5120"
                    >
                      <b-form-file
                        id="register-avatar"
                        v-model="user.avatar"
                        name="register-avatar"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Avatar"
                        accept="image/*"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>

                  <b-img
                    fluid
                    :src="avatar"
                    alt="Register V2"
                    class="mx-auto"
                  />
                </b-col>
                <b-col
                  sm="8"
                  md="8"
                  lg="6"
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
                      <b-form-textarea
                        id="register-introduction"
                        v-model="user.introduction"
                        name="register-introduction"
                        :state="errors.length > 0 ? false:null"
                        placeholder="Introduction"
                        rows="3"
                      />
                      <small class="text-danger">{{ errors[0] }}</small>
                    </validation-provider>
                  </b-form-group>
                </b-col>

                <b-col
                  sm="8"
                  md="6"
                  lg="6"
                  class="mt-1"
                >
                  <b-form-group>
                    <b-form-checkbox
                      id="register-privacy-policy"
                      v-model="user.status"
                      name="checkbox-1"
                    >
                      I agree to
                      <b-link>privacy policy & terms</b-link>
                    </b-form-checkbox>
                  </b-form-group>

                  <b-button
                    variant="primary"
                    block
                    type="submit"
                    :disabled="invalid"
                  >
                    Sign up
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
          </validation-observer>

          <b-row class="mx-auto justify-content-center">
            <p class="text-center mt-2">
              <span>Already have an account?</span>
              <b-link :to="{name:'auth-login'}">
                <span>&nbsp;Sign in instead</span>
              </b-link>
            </p>
          </b-row>

          <!-- divider -->
          <!-- <div class="divider my-2">
            <div class="divider-text">
              or
            </div>
          </div>

          <div class="auth-footer-btn d-flex justify-content-center">
            <b-button
              variant="facebook"
              href="javascript:void(0)"
            >
              <feather-icon icon="FacebookIcon" />
            </b-button>
            <b-button
              variant="twitter"
              href="javascript:void(0)"
            >
              <feather-icon icon="TwitterIcon" />
            </b-button>
            <b-button
              variant="google"
              href="javascript:void(0)"
            >
              <feather-icon icon="MailIcon" />
            </b-button>
            <b-button
              variant="github"
              href="javascript:void(0)"
            >
              <feather-icon icon="GithubIcon" />
            </b-button>
          </div> -->
        </b-row>
      </b-col>
      <!-- /Register-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import VuexyLogo from '@core/layouts/components/Logo.vue'
import {
  BRow,
  BCol,
  BLink,
  BButton,
  BForm,
  BFormCheckbox,
  BFormGroup,
  BFormInput,
  BInputGroup,
  BFormTextarea,
  BInputGroupAppend,
  BImg,
  BFormSelect,
  BFormFile,
  BCardTitle,
  BCardText,
} from 'bootstrap-vue'
import {
  required, email, password, alpha, phone, url, image, size,
} from '@validations'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { togglePasswordVisibility } from '@core/mixins/ui/forms'
import auth from '@/store/api/Auth'
import store from '@/store/index'
import useJwt from '@/auth/jwt/useJwt'
import { getHomeRouteForLoggedInUser } from '@/auth/utils'

export default {
  components: {
    VuexyLogo,
    BRow,
    BImg,
    BCol,
    BLink,
    BButton,
    BForm,
    BCardText,
    BCardTitle,
    BFormCheckbox,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BInputGroup,
    BInputGroupAppend,
    BFormFile,
    BFormSelect,
    // validations
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      user: {
        status: '',
        name: '',
        email: '',
        password: '',
        social_network: {
          fb: '',
          linkedin: '',
        },
      },
      sideImg: require('@/assets/images/pages/register-v2.svg'),
      avatar: require('@/assets/images/avatars/avatar-user.png'),
      // validation
      required,
      email,
      password,
      alpha,
      phone,
      url,
      image,
      size,
      options: [
        { value: { id: 3, name: 'ROLE_COMPANY' }, text: 'Company' },
        { value: { id: 2, name: 'ROLE_CANDIDATE' }, text: 'Candidate' },
      ],
    }
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    imgUrl() {
      if (store.state.appConfig.layout.skin === 'dark') {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require('@/assets/images/pages/register-v2-dark.svg')
        return this.sideImg
      }
      return this.sideImg
    },
  },
  watch: {
    user() {
      if (this.user.avatar) {
        const reader = new FileReader()
        reader.onload = e => {
          this.avatar = e.target.result
        }
        reader.readAsDataURL(this.user.avatar)
      }
    },
  },
  methods: {
    register() {
      this.$refs.registerForm.validate().then(success => {
        if (success) {
          const user = { ...this.user }
          user.phone = user.phone.replace('+84', '0')
          user.role_id = this.user.role.id
          user.role_name = this.user.role.name
          user.social_network = JSON.stringify(this.user.social_network)
          delete user.role
          const formData = new FormData()
          /* eslint guard-for-in: "error" */
          for (const key in user) {
            formData.append(key, user[key])
          }

          auth
            .register(formData)
            .then(response => {
              const resp = response.data
              const userData = resp.user
              userData.ability = [
                {
                  action: 'manage',
                  resource: 'all',
                // subject: userData.role,
                },
              ]
              useJwt.setToken(resp.data.accessToken)
              localStorage.setItem('userData', JSON.stringify(userData))
              this.$ability.update([
                {
                  action: 'manage',
                  subject: 'all',
                // subject: userData.role,
                },
              ])

              // ? This is just for demo purpose. Don't think CASL is role based in this case, we used role in if condition just for ease
              this.$router.replace(getHomeRouteForLoggedInUser(userData.role)).then(() => {
                this.$toast({
                  component: ToastificationContent,
                  position: 'top-right',
                  props: {
                    title: `Welcome ${userData.fullName || userData.username}`,
                    icon: 'CoffeeIcon',
                    variant: 'success',
                    text: `You have successfully logged in as ${userData.role}. Now you can start to explore!`,
                  },
                })
              })
            })
            .catch(error => {
              console.log(error)
              this.$refs.registerForm.setErrors([])
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
}
/* eslint-disable global-require */
</script>

<style lang="scss">
@import "~@core/scss/vue/pages/page-auth.scss";
</style>
