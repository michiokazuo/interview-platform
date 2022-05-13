import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '../libs/acl/routeProtection'
import {
  isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser,
  setToken, setUser, removeToken, removeUser, checkUserLoggedIn, checkRouterRole,
} from '../auth/utils'
import apps from './routes/apps'
import dashboard from './routes/dashboard'
import uiElements from './routes/ui-elements/index'
import pages from './routes/pages'
import chartsMaps from './routes/charts-maps'
import formsTable from './routes/forms-tables'
import others from './routes/others'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    { path: '/', redirect: { name: 'dashboard' } },
    ...apps,
    ...dashboard,
    ...pages,
    ...chartsMaps,
    ...formsTable,
    ...uiElements,
    ...others,
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

const checkLogin = () => {
  let isLogged = false
  let status
  let checkingLogin
  // eslint-disable-next-line no-restricted-globals
  if (location.pathname === '/dashboard' && location.search !== '') {
    status = 'checking'
    // eslint-disable-next-line no-restricted-globals
    const { search } = location
    const accessToken = new URLSearchParams(search).get('accessToken')
    setToken(accessToken)
    checkingLogin = checkUserLoggedIn()
      .then(response => {
        isLogged = true
        setUser(response.data)
        setToken(response.data.accessToken)
        status = 'done'
      })
      .catch(() => {
        removeToken()
        removeUser()
        status = 'error'
      })
  }
  return () => {
    if (status === 'checking') {
      throw checkingLogin
    } else if (status === 'error') {
      return false
    }

    return isLogged
  }
}

router.beforeEach((to, _, next) => {
  const isLoggedIn = isUserLoggedIn()
  const checkLoginRs = checkLogin()
  const oAuthLogin = checkLoginRs ?? false

  if (!canNavigate(to)) {
    // Redirect to log in if not logged in
    if (!(isLoggedIn && oAuthLogin)) return next({ name: 'auth-login' })

    // If logged in => not authorized
    return next({ name: 'misc-not-authorized' })
  }

  // Redirect if logged in
  const userData = getUserData()
  if (to.meta.redirectIfLoggedIn && isLoggedIn && oAuthLogin) {
    next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
  }

  return checkRouterRole(userData ? userData.role : null, next)
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
