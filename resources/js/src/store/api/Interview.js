import axios from 'axios'

const apiInterview = '/api/interview/'
const apiUser = '/api/interviews-by-user/'
const apiNews = '/api/interviews-by-news/'
const apiEdit = '/api/interview-edit/'
const apiFindNews = '/api/interview-find-news/'

export default {
  store(data) {
    return axios.post(`${apiInterview}`, data)
  },
  update(id, data) {
    return axios.put(`${apiInterview}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiInterview}${id}`)
  },
  showByUser(id) {
    return axios.get(`${apiUser}${id}`)
  },
  showByNews(id) {
    return axios.get(`${apiNews}${id}`)
  },
  showToEdit(id) {
    return axios.get(`${apiEdit}${id}`)
  },
  findById(id) {
    return axios.get(`${apiInterview}${id}`)
  },
  findByNewsId(id) {
    return axios.get(`${apiFindNews}${id}`)
  },
}