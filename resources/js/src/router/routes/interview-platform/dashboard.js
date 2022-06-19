export default [
  {
    path: '/admin-home',
    name: 'admin-home',
    component: () => import('@/views/interview-platform/dashboard/faq/Faq.vue'),
    meta: {
      resource: 'ROLE_ADMIN',
    },
  },
  {
    path: '/candidate-home',
    name: 'candidate-home',
    component: () => import('@/views/interview-platform/dashboard/faq/Faq.vue'),
    meta: {
      resource: 'ROLE_CANDIDATE',
    },
  },
  {
    path: '/company-home',
    name: 'company-home',
    component: () => import('@/views/interview-platform/dashboard/faq/Faq.vue'),
    meta: {
      resource: 'ROLE_COMPANY',
    },
  },
  {
    path: '/faq',
    name: 'pages-faq',
    component: () => import('@/views/interview-platform/dashboard/faq/Faq.vue'),
    meta: {
      pageTitle: 'FAQ',
      breadcrumb: [
        {
          text: 'Trang chủ',
          to: '/',
        },
        {
          text: 'FAQ',
          active: true,
        },
      ],
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('@/views/interview-platform/dashboard/Knowledge-base/KnowledgeBase.vue'),
    meta: {
      pageTitle: 'Trang chủ',
      breadcrumb: [
        {
          text: 'Trang chủ',
          active: true,
        },
        {
          text: 'FAQ',
          to: '/faq',
        },
      ],
    },
  },
  {
    path: '/dashboard/:category',
    name: 'dashboard-category',
    component: () => import('@/views/interview-platform/dashboard/Knowledge-base/KnowledgeBaseCategory.vue'),
    meta: {
      pageTitle: 'Chi tiết',
      breadcrumb: [
        {
          text: 'Trang chủ - Chi tiết',
          active: true,
        },
        {
          text: 'FAQ',
          to: '/faq',
        },
      ],
      navActiveLink: 'pages-knowledge-base',
    },
  },
]
