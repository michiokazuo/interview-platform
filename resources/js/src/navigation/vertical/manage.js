export default [
  {
    header: 'Management',
    resource: 'ROLE_ADMIN',
  },
  {
    title: 'Management  ',
    icon: 'EditIcon',
    resource: 'ROLE_ADMIN',
    children: [
      {
        title: 'User',
        icon: 'UsersIcon',
        route: 'admin-manage-user',
        resource: 'ROLE_ADMIN',
      },
      {
        title: 'Question',
        icon: 'DatabaseIcon',
        route: 'admin-manage-question',
        resource: 'ROLE_ADMIN',
      },
    ],
  },
]
