export default [
  {
    path: '/user-settings',
    name: 'pages-setting',
    component: () => import('@/views/interview-platform/user/account-setting/AccountSetting.vue'),
    meta: {
      pageTitle: 'Account Setting',
      breadcrumb: [
        {
          text: 'Account Setting',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user-profile',
    name: 'pages-profile',
    component: () => import('@/views/pages/profile/Profile.vue'),
    meta: {
      pageTitle: 'Profile',
      breadcrumb: [
        {
          text: 'Pages',
        },
        {
          text: 'Profile',
          active: true,
        },
      ],
    },
  },
]
