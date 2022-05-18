import axios from 'axios'

const apiCV = '/api/comment/'

export default {
  showByUser() {
    return axios.get(`${apiCV}`)
  },
  store(data) {
    return axios.post(`${apiCV}`, data)
  },
  update(id, data) {
    return axios.put(`${apiCV}${id}`, data)
  },
  delete(id) {
    return axios.delete(`${apiCV}${id}`)
  },
}
