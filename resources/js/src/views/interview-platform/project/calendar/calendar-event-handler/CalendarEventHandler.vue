<template>
  <div>
    <b-sidebar
      id="sidebar-add-new-event"
      sidebar-class="sidebar-lg"
      :visible="isEventHandlerSidebarActive"
      bg-variant="white"
      shadow
      backdrop
      no-header
      right
      @change="(val) => $emit('update:is-event-handler-sidebar-active', val)"
    >
      <template #default="{ hide }">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <h5 class="mb-0">
            {{ eventLocal.id ? eventLocal.done ? 'View' : 'Update': 'Add' }} Event
          </h5>
          <div>
            <feather-icon
              v-if="eventLocal.id && !eventLocal.done"
              icon="TrashIcon"
              class="cursor-pointer"
              @click="$emit('remove-event'); hide();"
            />
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="XIcon"
              size="16"
              @click="hide"
            />
          </div>
        </div>

        <!-- Body -->
        <validation-observer
          v-slot="{ handleSubmit }"
          ref="refFormObserver"
        >

          <!-- Form -->
          <b-form
            class="p-2"
            @submit.prevent="handleSubmit(onSubmit)"
            @reset.prevent="resetForm"
          >
            <!-- Address -->
            <validation-provider
              name="title"
              rules="required"
            >
              <b-form-group
                label="Title default"
                label-for="event-title"
              >
                <b-form-input
                  id="event-title"
                  v-model="eventLocal.title"
                  autofocus
                  trim
                  placeholder="Title"
                  disabled
                />
              </b-form-group>
            </validation-provider>

            <!-- Address -->
            <validation-provider
              v-slot="validationContext"
              name="address"
              rules="required"
            >
              <b-form-group
                label="Address"
                label-for="event-address"
              >
                <b-form-input
                  id="event-address"
                  v-model="eventLocal.address"
                  autofocus
                  :state="getValidationState(validationContext)"
                  trim
                  placeholder="Address"
                  :disabled="eventLocal.done"
                />

                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!-- Form -->
            <validation-provider
              v-slot="validationContext"
              name="Form"
              rules="required"
            >

              <b-form-group
                label="Form"
                label-for="calendar"
                :state="getValidationState(validationContext)"
              >
                <v-select
                  v-model="eventLocal.form"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="calendarOptions"
                  placeholder="Form"
                  input-id="calendar"
                  :disabled="eventLocal.done"
                />

                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!-- Start Date -->
            <validation-provider
              v-if="!eventLocal.done && interview && eventLocal.id == interview.id"
              v-slot="validationContext"
              name="start_time"
            >

              <b-form-group
                label="Before Time"
                label-for="start-time"
                :state="getValidationState(validationContext)"
              >
                <flat-pickr
                  v-model="eventLocal.time"
                  class="form-control"
                  :config="{ enableTime: true, dateFormat: 'Y-m-d H:i'}"
                  disabled
                />
              </b-form-group>
            </validation-provider>
            <validation-provider
              v-slot="validationContext"
              name="Time"
              :rules="eventLocal.done ? 'required' : 'required|time-begin' "
            >

              <b-form-group
                label="Start Date"
                label-for="start-date"
                :state="getValidationState(validationContext)"
              >
                <flat-pickr
                  v-model="eventLocal.start"
                  class="form-control"
                  :config="{ enableTime: true, dateFormat: 'Y-m-d H:i'}"
                  :disabled="eventLocal.done"
                />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <b-form-group v-if="eventLocal.form == 'Online' && eventLocal.room">
              <b-link
                :to="{ name: 'interview-meeting', params:{id: eventLocal.id} }"
                class="font-weight-bold mb-2"
              >
                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="success"
                  class="mb-2"
                >
                  Meeting
                </b-button>
              </b-link>
              <b-form-input
                v-model="eventLocal.room"
                readonly
              />
            </b-form-group>
            <b-form-group v-if="eventLocal.form == 'Online' && !eventLocal.room">
              <b-alert
                variant="warning"
                show
              >
                <h4 class="alert-heading">
                  Note
                </h4>
                <div class="alert-body">
                  <p class="p-0 m-0">
                    Please save to create room for this interview
                  </p>
                  <span>If failed, please try again!!! (Because full of room already)</span>
                </div>
              </b-alert>
            </b-form-group>
            <!-- Form Actions -->
            <div class="d-flex mt-2">
              <b-button
                v-if="!eventLocal.done"
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-2"
                type="submit"
              >
                {{ eventLocal.id ? 'Update' : 'Add' }}
              </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                variant="outline-secondary"
              >
                Reset
              </b-button>
            </div>
          </b-form>
        </validation-observer>
      </template>
    </b-sidebar>
  </div>
</template>

<script>
import {
  BSidebar,
  BForm,
  BFormGroup,
  BFormInput,
  BButton,
  BFormInvalidFeedback,
  BAlert,
  BLink,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  required, email, url, timeBegin,
} from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ref, toRefs } from '@vue/composition-api'
import useCalendarEventHandler from './useCalendarEventHandler'

export default {
  components: {
    BButton,
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    vSelect,
    flatPickr,
    ValidationProvider,
    BFormInvalidFeedback,
    ValidationObserver,
    BAlert,
    BLink,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isEventHandlerSidebarActive',
    event: 'update:is-event-handler-sidebar-active',
  },
  props: {
    isEventHandlerSidebarActive: {
      type: Boolean,
      required: true,
    },
    event: {
      type: Object,
      required: true,
    },
    clearEventData: {
      type: Function,
      required: true,
    },
    interview: {
      type: Object,
      required: true,
    },
  },
  setup(props, { emit }) {
    /*
     ? This is handled quite differently in SFC due to deadlock of `useFormValidation` and this composition function.
     ? If we don't handle it the way it is being handled then either of two composition function used by this SFC get undefined as one of it's argument.
     * The Trick:

     * We created reactive property `clearFormData` and set to null so we can get `resetEventLocal` from `useCalendarEventHandler` composition function.
     * Once we get `resetEventLocal` function which is required by `useFormValidation` we will pass it to `useFormValidation` and in return we will get `clearForm` function which shall be original value of `clearFormData`.
     * Later we just assign `clearForm` to `clearFormData` and can resolve the deadlock. 😎

     ? Behind The Scene
     ? When we passed it to `useCalendarEventHandler` for first time it will be null but right after it we are getting correct value (which is `clearForm`) and assigning that correct value.
     ? As `clearFormData` is reactive it is being changed from `null` to corrent value and thanks to reactivity it is also update in `useCalendarEventHandler` composition function and it is getting correct value in second time and can work w/o any issues.
    */
    const clearFormData = ref(null)

    const {
      eventLocal,
      resetEventLocal,
      calendarOptions,

      // UI
      onSubmit,
    } = useCalendarEventHandler(toRefs(props), clearFormData, emit)

    const {
      refFormObserver, getValidationState, resetForm, clearForm,
    } = formValidation(
      resetEventLocal,
      props.clearEventData,
    )

    clearFormData.value = clearForm

    return {
      // Add New Event
      eventLocal,
      calendarOptions,
      onSubmit,

      // Form Validation
      resetForm,
      refFormObserver,
      getValidationState,
    }
  },
  data() {
    return {
      required,
      email,
      url,
      timeBegin,
    }
  },
}
</script>

<style lang="scss">
@import '~@core/scss/vue/libs/vue-select.scss';
@import '~@core/scss/vue/libs/vue-flatpicker.scss';
</style>
