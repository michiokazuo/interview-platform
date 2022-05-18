import axios from 'axios'

const apiBlog = '/api/blog/'

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
  showByUser() {
    return axios.get('/api/blog-by-user')
  },
  findById(id) {
    return axios.get(`${apiBlog}${id}`)
  },
  showToEdit(id) {
    return axios.get(`/api/blog-edit/${id}`)
  },
}
