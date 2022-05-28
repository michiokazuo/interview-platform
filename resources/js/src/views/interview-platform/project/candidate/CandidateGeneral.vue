<template>
  <validation-observer
    ref="updateForm"
  >
    <b-form>
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
              <div
                class="blog-content"
                v-html="user.description"
              />
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
                  disabled
                  readonly
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
                  disabled
                  readonly
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
                  disabled
                  readonly
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <!-- linkedin -->
        </b-row>
      </b-card>
    </b-form>
  </validation-observer>
</template>

<script>
import {
  BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BMedia, BMediaAside, BLink, BImg, BFormSelect,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import Ripple from 'vue-ripple-directive'
import {
  required, email, alpha, phone, url, image, size,
} from '@validations'

export default {
  components: {
    BForm,
    BImg,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BFormSelect,
    BCard,
    BMedia,
    BMediaAside,
    BLink,
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
    }
  },
  watch: {
    userInfo(newVal) {
      this.user = { ...newVal }
      this.user.social_network = { ...newVal.social_network ?? {} }
      this.user.role = { ...newVal.role }
    },
  },
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/vue/libs/quill.scss';
</style>
