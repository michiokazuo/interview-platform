import axios from 'axios'

const apiTags = '/api/qat/tags'
const apiQuestions = '/api/qat/questions'

export default {
  getTags() {
    return axios.get(`${apiTags}`)
  },
  getQuestions(data) {
    return axios.post(`${apiQuestions}`, data)
  },
}
