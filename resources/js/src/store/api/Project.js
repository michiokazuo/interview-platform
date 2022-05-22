import axios from 'axios'

const apiProject = '/api/project/'
const apiProjectChange = '/api/project-change/'

export default {
  getAll(params) {
    return axios.get(`${apiProject}`, { params })
  },
  store(data) {
    return axios.post(`${apiProject}`, data)
  },
  update(id, data) {
    return axios.put(`${apiProject}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiProject}${id}`)
  },
  changeStatus(id) {
    return axios.post(`${apiProjectChange}${id}`)
  },
  findById(id) {
    return axios.get(`${apiProject}${id}`)
  },
}
