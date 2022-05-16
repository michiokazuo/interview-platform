import axios from 'axios'

const apiCV = '/api/cv'

export default {
  getCV() {
    return axios.get(`${apiCV}`)
  },
  storeCV(data) {
    return axios.post(`${apiCV}`, data)
  },
}
