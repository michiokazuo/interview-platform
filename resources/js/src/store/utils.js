export default {
  updateUser(userData) {
    // eslint-disable-next-line no-param-reassign
    userData.ability = [
      {
        action: 'manage',
        resource: 'all',
        // subject: userData.role,
      },
    ]
    localStorage.setItem('userData', JSON.stringify(userData))
  },
}
