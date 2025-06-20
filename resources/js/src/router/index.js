import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import { canNavigate } from '../libs/acl/routeProtection'
import {
  isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser,
} from '../auth/utils'
// theme
import apps from './routes/apps'
import dashboard1 from './routes/dashboard'
import uiElements from './routes/ui-elements/index'
// import pages1 from './routes/pages'
import chartsMaps from './routes/charts-maps'
import formsTable from './routes/forms-tables'
import others from './routes/others'
import store from '@/store'
// custom routes
import auth from './routes/interview-platform/auth'
import dashboard from './routes/interview-platform/dashboard'
import pages from './routes/interview-platform/pages'
import blog from './routes/interview-platform/blog'
import project from './routes/interview-platform/project'
import interview from './routes/interview-platform/interview'
import admin from './routes/interview-platform/admin'

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
    ...dashboard1,
    // ...pages1,
    ...chartsMaps,
    ...formsTable,
    ...uiElements,
    ...others,

    ...admin,
    ...interview,
    ...auth,
    ...dashboard,
    ...pages,
    ...blog,
    ...project,
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

router.beforeEach(async (to, _, next) => {
  const isLoggedIn = isUserLoggedIn()

  const pathname = to.path

  if (pathname.startsWith('/admin')
    || pathname.startsWith('/candidate')
    || pathname.startsWith('/company')
    || pathname.startsWith('/user')) {
    // eslint-disable-next-line no-const-assign
    store.commit('appConfig/UPDATE_NAV_MENU_HIDDEN', false)
    store.commit('appConfig/UPDATE_NAVBAR_CONFIG', { type: 'floating' })
  } else {
    store.commit('appConfig/UPDATE_NAV_MENU_HIDDEN', true)
    store.commit('appConfig/UPDATE_NAVBAR_CONFIG', { type: 'hidden' })
  }

  if (!canNavigate(to, isLoggedIn)) {
    // Redirect to log in if not logged in
    if (!isLoggedIn) return next({ name: 'auth-login' })

    // If logged in => not authorized
    return next({ name: 'misc-not-authorized' })
  }

  // Redirect if logged in
  const userData = getUserData()
  if (to.meta.redirectIfLoggedIn && isLoggedIn) {
    next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
  }

  return next()
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
