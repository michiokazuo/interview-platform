import axios from 'axios'

const apiCandidate = '/api/dashboard-candidate'
const apiCompany = '/api/dashboard-company'
const apiAdmins = '/api/dashboard-admin'
const apiGraphAdmin = '/api/admin/graph'

export default {
  getCandidate() {
    return axios.get(`${apiCandidate}`)
  },
  getCompany() {
    return axios.get(`${apiCompany}`)
  },
  getAdmin() {
    return axios.get(`${apiAdmins}`)
  },
  graphAdmin(params) {
    return axios.get(`${apiGraphAdmin}`, { params })
  },
}
