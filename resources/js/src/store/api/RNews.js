import axios from 'axios'

const apiNews = '/api/project-news/'
const apiNewsChange = '/api/project-news-change/'
const apiNewsEdit = '/api/project-news-edit/'
const apiNewsProject = '/api/news-by-project/'
const apiNewsUser = '/api/news-by-user/'

export default {
  getAll(params) {
    return axios.get(`${apiNews}`, { params })
  },
  store(data) {
    return axios.post(`${apiNews}`, data)
  },
  update(id, data) {
    return axios.put(`${apiNews}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiNews}${id}`)
  },
  changeStatus(id) {
    return axios.post(`${apiNewsChange}${id}`)
  },
  findById(id) {
    return axios.get(`${apiNews}${id}`)
  },
  showToEdit(id) {
    return axios.get(`${apiNewsEdit}${id}`)
  },
  showAllByProject(id) {
    return axios.get(`${apiNewsProject}${id}`)
  },
  showAllByUser(id, params) {
    return axios.get(`${apiNewsUser}${id}`, { params })
  },
}
