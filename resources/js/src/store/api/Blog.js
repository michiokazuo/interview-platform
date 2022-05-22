import axios from 'axios'

const apiBlog = '/api/blog/'
const apiBlogShowEdit = '/api/blog-edit/'
const apiBlogShowByUser = '/api/blog-by-user/'

export default {
  getAll(params) {
    return axios.get(`${apiBlog}`, { params })
  },
  store(data) {
    return axios.post(`${apiBlog}`, data)
  },
  update(id, data) {
    return axios.put(`${apiBlog}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiBlog}${id}`)
  },
  showByUser(id, params) {
    return axios.get(`${apiBlogShowByUser}${id}`, { params })
  },
  findById(id) {
    return axios.get(`${apiBlog}${id}`)
  },
  showToEdit(id) {
    return axios.get(`${apiBlogShowEdit}${id}`)
  },
}
