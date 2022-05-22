import axios from 'axios'

const apiProcess = '/api/project-process/'
const apiProcessChange = '/api/project-process-change/'

export default {
  getAll(params) {
    return axios.get(`${apiProcess}`, { params })
  },
  store(data) {
    return axios.post(`${apiProcess}`, data)
  },
  update(id, data) {
    return axios.put(`${apiProcess}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiProcess}${id}`)
  },
  changeStatus(id) {
    return axios.post(`${apiProcessChange}${id}`)
  },
  findById(id) {
    return axios.get(`${apiProcess}${id}`)
  },
}
