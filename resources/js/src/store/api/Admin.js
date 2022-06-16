import axios from 'axios'

const apiUsers = '/api/admin/all-user'
const apiActive = '/api/admin/active-company/'
const apiDelete = '/api/admin/delete-user/'
const apiCrawler = '/api/admin/crawler'

export default {
  getAllUsers() {
    return axios.get(apiUsers)
  },
  activeCompany(id) {
    return axios.post(`${apiActive}${id}`)
  },
  deleteUser(id) {
    return axios.delete(`${apiDelete}${id}`)
  },
  crawlData(data) {
    return axios.post(apiCrawler, data)
  },
}
