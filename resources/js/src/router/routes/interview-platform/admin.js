export default [
  {
    path: '/admin/manage-user',
    name: 'admin-manage-user',
    component: () => import('@/views/interview-platform/admin/ManageUser.vue'),
  },
  {
    path: '/admin/manage-question',
    name: 'admin-manage-question',
    component: () => import('@/views/interview-platform/admin/ManageQuestion.vue'),
  },
]
