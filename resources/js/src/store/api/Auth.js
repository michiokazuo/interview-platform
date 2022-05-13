import axios from 'axios'

const apiUser = '/api/auth/'

export default {
  login(data) {
    return axios.post(`${apiUser}login`, data)
  },
}
