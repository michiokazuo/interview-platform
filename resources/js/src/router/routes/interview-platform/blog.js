export default [
  {
    path: '/user/blog/list',
    name: 'pages-blog-list',
    component: () => import('@/views/interview-platform/blog/BlogList.vue'),
    meta: {
      pageTitle: 'Blog List',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'List',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/blog/list-by-user',
    name: 'pages-user-blog-list',
    component: () => import('@/views/interview-platform/blog/BlogListByUser.vue'),
    meta: {
      pageTitle: 'Blog List By User',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'List By User',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/blog/list-by-user/:id',
    name: 'pages-another-user-blog-list',
    component: () => import('@/views/interview-platform/blog/BlogListByAnotherUser.vue'),
    meta: {
      pageTitle: 'Blog List By Another User',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'List By Another User',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/blog/edit/:id',
    name: 'pages-blog-edit',
    component: () => import('@/views/interview-platform/blog/BlogEdit.vue'),
    meta: {
      pageTitle: 'Blog Edit',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'Edit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/blog/create',
    name: 'pages-blog-create',
    component: () => import('@/views/interview-platform/blog/BlogEdit.vue'),
    meta: {
      pageTitle: 'Blog Create',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'Create',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/blog/:id',
    name: 'pages-blog-detail',
    component: () => import('@/views/interview-platform/blog/BlogDetail.vue'),
    meta: {
      pageTitle: 'Blog Detail',
      breadcrumb: [
        {
          text: 'Blog',
          to: { name: 'pages-blog-list' },
        },
        {
          text: 'Detail',
          active: true,
        },
      ],
    },
  },
]
