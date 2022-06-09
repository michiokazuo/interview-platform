import axios from 'axios'

const apiCmt = '/api/comment/'

export default {
  showByUser() {
    return axios.get(`${apiCmt}`)
  },
  store(data) {
    return axios.post(`${apiCmt}`, data)
  },
  update(id, data) {
    return axios.put(`${apiCmt}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiCmt}${id}`)
  },
}
