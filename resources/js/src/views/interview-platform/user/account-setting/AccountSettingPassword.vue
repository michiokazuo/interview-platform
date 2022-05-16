<template>
  <b-card>
    <!-- form -->
    <validation-observer
      ref="simpleRules"
      #default="{invalid}"
    >
      <b-form
        @submit.prevent="changePassword"
        @reset.prevent="resetForm"
      >
        <b-row>
          <!-- old password -->
          <b-col md="6">
            <b-form-group
              label="Old Password"
              label-for="account-old-password"
            >
              <validation-provider
                #default="{ errors }"
                name="Old Password"
                vid="OldPassword"
                rules="required|password"
              >
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                    id="account-old-password"
                    v-model="resetPass.old_password"
                    name="account-old-password"
                    :type="passwordFieldTypeOld"
                    :state="errors.length > 0 ? false:null"
                    placeholder="Old Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      :icon="passwordToggleIconOld"
                      class="cursor-pointer"
                      @click="togglePasswordOld"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
        <!--/ old password -->
        </b-row>
        <b-row>
          <!-- new password -->
          <b-col md="6">
            <b-form-group
              label-for="account-new-password"
              label="New Password"
            >
              <validation-provider
                #default="{ errors }"
                name="Password"
                vid="Password"
                rules="required|password"
              >
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                    id="account-new-password"
                    v-model="resetPass.password"
                    :type="passwordFieldTypeNew"
                    name="account-new-password"
                    :state="errors.length > 0 ? false:null"
                    placeholder="New Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      :icon="passwordToggleIconNew"
                      class="cursor-pointer"
                      @click="togglePasswordNew"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ new password -->

          <!-- retype password -->
          <b-col md="6">
            <b-form-group
              label-for="account-retype-new-password"
              label="Retype New Password"
            >
              <validation-provider
                #default="{ errors }"
                name="RetypePassword"
                vid="RetypePassword"
                rules="required|password|confirmed:Password"
              >
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid':null"
                >
                  <b-form-input
                    id="account-retype-new-password"
                    v-model="resetPass.password_confirmation"
                    :type="passwordFieldTypeRetype"
                    name="account-retype-new-password"
                    :state="errors.length > 0 ? false:null"
                    placeholder="New Password"
                  />
                  <b-input-group-append is-text>
                    <feather-icon
                      :icon="passwordToggleIconRetype"
                      class="cursor-pointer"
                      @click="togglePasswordRetype"
                    />
                  </b-input-group-append>
                </b-input-group>
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!--/ retype password -->

          <!-- buttons -->
          <b-col cols="12">
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
              variant="outline-secondary"
              class="mt-1"
            >
              Reset
            </b-button>
          </b-col>
        <!--/ buttons -->
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BInputGroup, BInputGroupAppend,
} from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import { required, password } from '@validations'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import auth from '@/store/api/Auth'

export default {
  components: {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BInputGroup,
    BInputGroupAppend,
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  props: {
    userInfo: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      resetPass: {
        email: '',
        old_password: '',
        password: '',
        password_confirmation: '',
      },

      // validation
      required,
      password,

      passwordFieldTypeOld: 'password',
      passwordFieldTypeNew: 'password',
      passwordFieldTypeRetype: 'password',
    }
  },
  computed: {
    passwordToggleIconOld() {
      return this.passwordFieldTypeOld === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconNew() {
      return this.passwordFieldTypeNew === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
    passwordToggleIconRetype() {
      return this.passwordFieldTypeRetype === 'password' ? 'EyeIcon' : 'EyeOffIcon'
    },
  },
  methods: {
    togglePasswordOld() {
      this.passwordFieldTypeOld = this.passwordFieldTypeOld === 'password' ? 'text' : 'password'
    },
    togglePasswordNew() {
      this.passwordFieldTypeNew = this.passwordFieldTypeNew === 'password' ? 'text' : 'password'
    },
    togglePasswordRetype() {
      this.passwordFieldTypeRetype = this.passwordFieldTypeRetype === 'password' ? 'text' : 'password'
    },
    changePassword() {
      this.$refs.simpleRules.validate().then(success => {
        if (success) {
          this.resetPass.email = this.userInfo.email
          auth.changePassword(this.resetPass).then(response => {
            console.log(response)
            this.$toast({
              component: ToastificationContent,
              props: {
                title: 'Reset password successfully. Please check again!!!',
                icon: 'EditIcon',
                variant: 'success',
              },
            })
            this.resetForm()
          }).catch(error => {
            console.log(error)
            this.$toast({
              component: ToastificationContent,
              position: 'top-right',
              props: {
                title: 'Error',
                icon: 'AlertTriangleIcon',
                variant: 'danger',
                text: 'Something went wrong. Please try again!!!',
              },
            })
          })
        }
      })
    },
    resetForm() {
      this.$refs.simpleRules.reset()
      this.resetPass = {
        email: this.userInfo.email,
        old_password: '',
        password: '',
        password_confirmation: '',
      }
    },
  },
}
</script>
