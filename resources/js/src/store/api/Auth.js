import axios from 'axios'

const apiUser = '/api/auth/'

export default {
  login(data) {
    return axios.post(`${apiUser}login`, data)
  },
  register(data) {
    return axios.post(`${apiUser}register`, data)
  },
  logout() {
    return axios.get(`${apiUser}logout`)
  },
  getUser() {
    return axios.get(`${apiUser}user`)
  },
  updateUser(data) {
    return axios.put(`${apiUser}user`, data)
  },
  forgotPassword(data) {
    return axios.post(`${apiUser}forgot-password`, data)
  },
  resetPassword(data) {
    return axios.post(`${apiUser}reset-password`, data)
  },
}
