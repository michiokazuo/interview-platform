export default [
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/interview-platform/error/Error404.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      action: 'read',
    },
  },
  {
    path: '/login',
    name: 'auth-login',
    component: () => import('@/views/interview-platform/auth/Login.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/register',
    name: 'auth-register',
    component: () => import('@/views/interview-platform/auth/Register.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/forgot-password',
    name: 'auth-forgot-password',
    component: () => import('@/views/interview-platform/auth/ForgotPassword.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
      redirectIfLoggedIn: true,
    },
  },
  {
    path: '/reset-password',
    name: 'auth-reset-password',
    component: () => import('@/views/interview-platform/auth/ResetPassword.vue'),
    meta: {
      layout: 'full',
    },
  },
  {
    path: '/coming-soon',
    name: 'misc-coming-soon',
    component: () => import('@/views/interview-platform/auth/miscellaneous/ComingSoon.vue'),
    meta: {
      layout: 'full',
    },
  },
  {
    path: '/not-authorized',
    name: 'misc-not-authorized',
    component: () => import('@/views/interview-platform/auth/miscellaneous/NotAuthorized.vue'),
    meta: {
      layout: 'full',
      resource: 'Auth',
    },
  },
  {
    path: '/under-maintenance',
    name: 'misc-under-maintenance',
    component: () => import('@/views/interview-platform/auth/miscellaneous/UnderMaintenance.vue'),
    meta: {
      layout: 'full',
    },
  },
  {
    path: '/error',
    name: 'misc-error',
    component: () => import('@/views/interview-platform/auth/miscellaneous/Error.vue'),
    meta: {
      layout: 'full',
    },
  },
]
