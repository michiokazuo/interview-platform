import axios from 'axios'

const apiInterview = '/api/interview/'
const apiInterviewUpdate = '/api/interview-update/'
const apiUser = '/api/interviews-by-user/'
const apiNews = '/api/interviews-by-news-process'
const apiEdit = '/api/interview-edit/'
const apiFindNews = '/api/interview-find-news/'
const createTest = '/api/create-test/'
const apiSaveTest = '/api/save-test/'
const apiByCompany = '/api/interviews-by-company'
const apiDeleteOne = '/api/delete-interview/'

export default {
  store(data) {
    return axios.post(`${apiInterview}`, data)
  },
  update(id, data) {
    return axios.post(`${apiInterviewUpdate}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiInterview}${id}`)
  },
  deleteOnly(id) {
    return axios.delete(`${apiDeleteOne}${id}`)
  },
  showByUser(id) {
    return axios.get(`${apiUser}${id}`)
  },
  showByNews(params) {
    return axios.get(`${apiNews}`, { params })
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
  createTest(id, data) {
    return axios.post(`${createTest}${id}`, data)
  },
  saveRSTest(id, data) {
    return axios.post(`${apiSaveTest}${id}`, data)
  },
  showAllByCompany() {
    return axios.get(`${apiByCompany}`)
  },
}
