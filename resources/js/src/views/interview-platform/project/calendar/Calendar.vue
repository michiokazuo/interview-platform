<template>
  <div class="app-calendar overflow-hidden border">
    <div class="row no-gutters">

      <!-- Calendar -->
      <div class="col position-relative">
        <div class="card shadow-none border-0 mb-0 rounded-0">
          <div class="card-body pb-0">
            <full-calendar
              ref="refCalendar"
              :options="calendarOptions"
              class="full-calendar"
            />
          </div>
        </div>
      </div>

      <!-- Sidebar Overlay -->
      <div
        class="body-content-overlay"
        :class="{'show': isCalendarOverlaySidebarActive}"
        @click="isCalendarOverlaySidebarActive = false"
      />
      <calendar-event-handler
        v-model="isEventHandlerSidebarActive"
        :event="event"
        :clear-event-data="clearEventData"
        :interview="interview"
        :interview-questions="interviewQuestions"
        @remove-event="removeEvent"
        @add-event="addEvent"
        @update-event="updateEvent"
      />
    </div>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import { onUnmounted } from '@vue/composition-api'
import store from '@/store'
import calendarStoreModule from './calendarStoreModule'
import CalendarEventHandler from './calendar-event-handler/CalendarEventHandler.vue'
import useCalendar from './useCalendar'

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
    CalendarEventHandler,
  },
  model: {
    prop: 'interview',
    event: 'update:interview',
  },
  props: {
    interview: {
      type: Object,
      default: () => ({}),
    },
    interviewQuestions: {
      type: Array,
      default: () => ([]),
    },
  },
  setup() {
    const CALENDAR_APP_STORE_MODULE_NAME = 'calendar'
    // Register module
    if (!store.hasModule(CALENDAR_APP_STORE_MODULE_NAME)) store.registerModule(CALENDAR_APP_STORE_MODULE_NAME, calendarStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(CALENDAR_APP_STORE_MODULE_NAME)) store.unregisterModule(CALENDAR_APP_STORE_MODULE_NAME)
    })

    const {
      refCalendar,
      isCalendarOverlaySidebarActive,
      event,
      clearEventData,
      addEvent,
      updateEvent,
      removeEvent,
      fetchEvents,
      refetchEvents,
      calendarOptions,

      // ----- UI ----- //
      isEventHandlerSidebarActive,
    } = useCalendar()

    fetchEvents()

    return {
      refCalendar,
      isCalendarOverlaySidebarActive,
      event,
      clearEventData,
      addEvent,
      updateEvent,
      removeEvent,
      refetchEvents,
      calendarOptions,

      // ----- UI ----- //
      isEventHandlerSidebarActive,
    }
  },
  watch: {
    event: {
      handler(newVal) {
        if (!newVal.id) {
          const done = !!((this.interview.result && this.interview.result.company) || (this.interview.record && this.interview.record.company))
          this.event = {
            ...this.interview,
            done,
            start: done ? this.interview.start : newVal.start,
          }
        }
      },
      deep: true,
    },
  },
}
</script>

<style lang="scss">
@import "@core/scss/vue/apps/calendar.scss";
</style>
