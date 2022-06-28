export default [
  {
    header: 'Projects',
    resource: 'ROLE_COMPANY',
  },
  {
    title: 'Management ',
    icon: 'FolderIcon',
    children: [
      {
        title: 'Project List',
        icon: 'ListIcon',
        route: 'pages-company-projects',
        resource: 'ROLE_COMPANY',
      },
      {
        title: 'Project Create',
        icon: 'PlusIcon',
        route: 'pages-project-create',
        resource: 'ROLE_COMPANY',
      },
      {
        title: 'Interview Questions',
        icon: 'LayersIcon',
        route: 'pages-manage-interview-question',
        resource: 'ROLE_COMPANY',
      },
    ],
    resource: 'ROLE_COMPANY',
  },
]
