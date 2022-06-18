export default [
  {
    header: 'Blogs Interview',
    resource: 'ROLE_CANDIDATE',
  },
  {
    header: 'Blogs Interview ',
    resource: 'ROLE_COMPANY',
  },
  {
    header: 'Blogs Interview  ',
    resource: 'ROLE_ADMIN',
  },
  {
    title: 'Blogs',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'pages-blog-list',
        resource: 'ROLE_CANDIDATE',
      },
      {
        title: 'Create',
        route: 'pages-blog-create',
        resource: 'ROLE_CANDIDATE',
      },
      {
        title: 'Yours',
        route: 'pages-user-blog-list',
        resource: 'ROLE_CANDIDATE',
      },
    ],
    resource: 'ROLE_CANDIDATE',
  },
  {
    title: 'Blogs ',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'pages-blog-list',
        resource: 'ROLE_COMPANY',
      },
      {
        title: 'Create',
        route: 'pages-blog-create',
        resource: 'ROLE_COMPANY',
      },
      {
        title: 'Yours',
        route: 'pages-user-blog-list',
        resource: 'ROLE_COMPANY',
      },
    ],
    resource: 'ROLE_COMPANY',
  },
  {
    title: 'Blogs  ',
    icon: 'FileTextIcon',
    children: [
      {
        title: 'List',
        route: 'pages-blog-list',
        resource: 'ROLE_ADMIN',
      },
      {
        title: 'Create',
        route: 'pages-blog-create',
        resource: 'ROLE_ADMIN',
      },
      {
        title: 'Yours',
        route: 'pages-user-blog-list',
        resource: 'ROLE_ADMIN',
      },
    ],
    resource: 'ROLE_ADMIN',
  },
]
