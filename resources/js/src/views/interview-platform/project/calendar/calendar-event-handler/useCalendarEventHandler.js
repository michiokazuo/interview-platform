import { ref, computed, watch } from '@vue/composition-api'
import store from '@/store'

export default function useCalendarEventHandler(props, clearForm, emit) {
  // ------------------------------------------------
  // eventLocal
  // ------------------------------------------------
  const eventLocal = ref(JSON.parse(JSON.stringify(props.event.value)))
  const resetEventLocal = () => {
    eventLocal.value = JSON.parse(JSON.stringify(props.event.value))
  }
  watch(props.event, () => {
    resetEventLocal()
  })

  // ------------------------------------------------
  // isEventHandlerSidebarActive
  // * Clear form if sidebar is closed
  // ------------------------------------------------
  watch(props.isEventHandlerSidebarActive, val => {
    // ? Don't reset event till transition is finished
    if (!val) {
      setTimeout(() => {
        clearForm.value()
      }, 500)
    }
  })
  // ------------------------------------------------
  // calendarOptions
  // ------------------------------------------------
  const calendarOptions = [
    'Online', 'Offline',
  ]

  const onSubmit = () => {
    const eventData = JSON.parse(JSON.stringify(eventLocal))

    // * If event has id => Edit Event
    // Emit event for add/update event
    if (props.event.value.id) emit('update-event', eventData.value)
    else emit('add-event', eventData.value)

    // Close sidebar
    emit('update:is-event-handler-sidebar-active', false)
  }

  // *===============================================---*
  // *--------- UI ---------------------------------------*
  // *===============================================---*

  // ------------------------------------------------
  // guestOptions
  // ------------------------------------------------

  return {
    eventLocal,
    resetEventLocal,
    calendarOptions,

    onSubmit,
  }
}
