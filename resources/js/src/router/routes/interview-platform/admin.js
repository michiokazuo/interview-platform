export default [
  {
    path: '/admin/manage-user',
    name: 'admin-manage-user',
    component: () => import('@/views/interview-platform/admin/ManageUser.vue'),
    meta: {
      resource: 'ROLE_ADMIN',
      pageTitle: 'Manage User',
      breadcrumb: [
        {
          text: 'Users',
          to: { name: 'admin-manage-user' },
        },
        {
          text: 'List',
          active: true,
        },
      ],
    },
  },
  {
    path: '/admin/manage-question',
    name: 'admin-manage-question',
    component: () => import('@/views/interview-platform/admin/ManageQuestion.vue'),
    meta: {
      resource: 'ROLE_ADMIN',
      pageTitle: 'Manage Question',
      breadcrumb: [
        {
          text: 'Questions',
          to: { name: 'admin-manage-question' },
        },
        {
          text: 'List',
          active: true,
        },
      ],
    },
  },
]
