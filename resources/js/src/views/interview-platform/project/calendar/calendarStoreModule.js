import interview from '@/store/api/Interview'

export default {
  namespaced: true,
  state: {
    calendarOptions: [
      {
        color: 'danger',
        label: 'Canceled schedule',
      },
      {
        color: 'primary',
        label: 'Scheduled',
      },
      {
        color: 'warning',
        label: 'Created',
      },
      {
        color: 'success',
        label: 'Completed',
      },
      {
        color: 'info',
        label: 'Done test',
      },
      {
        color: 'dark',
        label: 'Have test',
      },
    ],
    selectedCalendars: ['Scheduled', 'Completed', 'Created', 'Canceled schedule', 'Have test', 'Done test'],
  },
  getters: {},
  mutations: {
    SET_SELECTED_EVENTS(state, val) {
      state.selectedCalendars = val
    },
  },
  actions: {
    fetchEvents(ctx, { calendars }) {
      const userData = JSON.parse(localStorage.getItem('userData'))
      if (userData && userData.role === 'ROLE_CANDIDATE') {
        return new Promise((resolve, reject) => {
          interview.showByUser(userData.candidate_id)
            .then(response => resolve(response))
            .catch(error => reject(error))
        })
      }
      return new Promise((resolve, reject) => {
        interview.showAllByCompany()
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    addEvent(ctx, { event }) {
      return new Promise((resolve, reject) => {
        interview.update(event.id, {
          time: event.start,
          news_id: event.news.id,
          company_id: event.id,
          candidate_id: event.candidate.general.owner.id,
          form: event.form,
          address: event.address,
        })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    updateEvent(ctx, { event }) {
      return new Promise((resolve, reject) => {
        interview.update(event.id, {
          time: event.start,
          news_id: event.news.id,
          company_id: event.id,
          candidate_id: event.candidate.general.owner.id,
          form: event.form,
          address: event.address,
          gq_interview_id: event.interview_question?.id ?? null,
        })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    removeEvent(ctx, { event }) {
      return new Promise((resolve, reject) => {
        interview.update(event.id, {
          time: null,
          news_id: event.news.id,
          company_id: event.id,
          candidate_id: event.candidate.general.owner.id,
          form: null,
          address: null,
        })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
