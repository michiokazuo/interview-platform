import axios from 'axios'

const apiGQ = '/api/group-question/'
const apiInterview = '/api/group-interview'

export default {
  showByUser() {
    return axios.get(`${apiGQ}`)
  },
  store(data) {
    return axios.post(`${apiGQ}`, data)
  },
  update(id, data) {
    return axios.put(`${apiGQ}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiGQ}${id}`)
  },
  findById(id) {
    return axios.get(`${apiGQ}${id}`)
  },
  showGroupInterviews() {
    return axios.get(`${apiInterview}`)
  },
}
