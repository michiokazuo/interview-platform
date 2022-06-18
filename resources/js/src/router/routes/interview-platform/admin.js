export default [
  {
    path: '/admin/manage-user',
    name: 'admin-manage-user',
    component: () => import('@/views/interview-platform/admin/ManageUser.vue'),
    meta: {
      resource: 'ROLE_ADMIN',
    },
  },
  {
    path: '/admin/manage-question',
    name: 'admin-manage-question',
    component: () => import('@/views/interview-platform/admin/ManageQuestion.vue'),
    meta: {
      resource: 'ROLE_ADMIN',
    },
  },
]
