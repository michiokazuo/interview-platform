import axios from 'axios'
import useJwt from './jwt/useJwt'
// import store from '@/store'

/**
 * Return if user is logged in
 * This is completely up to you and how you want to store the token in your frontend application
 * e.g. If you are using cookies to store the application please update this function
 */
export const isUserLoggedIn = () => localStorage.getItem('userData')
  && localStorage.getItem(useJwt.jwtConfig.storageTokenKeyName)

export const getUserData = () => JSON.parse(localStorage.getItem('userData'))

export const setToken = token => useJwt.setToken(token)

export const setUser = user => localStorage.setItem('userData', JSON.stringify(user))

export const removeUser = () => localStorage.removeItem('userData')

export const removeToken = () => localStorage.removeItem(useJwt.jwtConfig.storageTokenKeyName)

export const checkUserLoggedIn = () => axios.get('/api/user')

/**
 * This function is used for demo purpose route navigation
 * In real app you won't need this function because your app will navigate to same route for each users regardless of ability
 * Please note role field is just for showing purpose it's not used by anything in frontend
 * We are checking role just for ease
 * NOTE: If you have different pages to navigate based on user ability then this function can be useful. However, you need to update it.
 * @param {String} userRole Role of user
 */
export const getHomeRouteForLoggedInUser = userRole => {
  if (userRole === 'ROLE_ADMIN') return { name: 'admin-home' }
  if (userRole === 'ROLE_CANDIDATE') return { name: 'candidate-home' }
  if (userRole === 'ROLE_COMPANY') return { name: 'company-home' }
  return { name: 'access-control' }
}
