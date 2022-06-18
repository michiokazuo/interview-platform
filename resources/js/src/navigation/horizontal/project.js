export default [
  {
    path: '/company/projects',
    name: 'pages-company-projects',
    component: () => import('@/views/interview-platform/project/Project.vue'),
    meta: {
      pageTitle: 'Project List',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'List',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/company-news',
    name: 'pages-company-news',
    component: () => import('@/views/interview-platform/news_artical/AllNews.vue'),
    meta: {
      pageTitle: 'All News',
      breadcrumb: [
        {
          text: 'News',
          to: { name: 'pages-company-news' },
        },
        {
          text: 'List',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/news-list-by-company/:id',
    name: 'pages-company-news-list',
    component: () => import('@/views/interview-platform/news_artical/NewsByUser.vue'),
    meta: {
      pageTitle: 'News By Company',
      breadcrumb: [
        {
          text: 'News',
          to: { name: 'pages-company-news' },
        },
        {
          text: 'List By Company',
          active: true,
        },
      ],
    },
  },
  {
    path: '/user/company-news-detail/:id',
    name: 'pages-news-detail',
    component: () => import('@/views/interview-platform/news_artical/NewsDetail.vue'),
    meta: {
      pageTitle: 'News Detail',
      breadcrumb: [
        {
          text: 'News',
          to: { name: 'pages-company-news' },
        },
        {
          text: 'Detail',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/project/create',
    name: 'pages-project-create',
    component: () => import('@/views/interview-platform/project/ProjectEdit.vue'),
    meta: {
      pageTitle: 'Project Create',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'Create',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/projects/:id',
    name: 'pages-project-detail',
    component: () => import('@/views/interview-platform/project/ProjectDetail.vue'),
    meta: {
      pageTitle: 'Project Detail',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'Detail',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/project/:idProject/news/edit/:id',
    name: 'pages-news-edit',
    component: () => import('@/views/interview-platform/project/NewsDetail.vue'),
    meta: {
      pageTitle: 'News Detail',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'News Detail',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/project/:idProject/news/create',
    name: 'pages-news-create',
    component: () => import('@/views/interview-platform/project/NewsDetail.vue'),
    meta: {
      pageTitle: 'News Create',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'News Create',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/project/:idProject/process/edit/:id',
    name: 'pages-process-edit',
    component: () => import('@/views/interview-platform/project/ProcessEdit.vue'),
    meta: {
      pageTitle: 'Process Edit',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'Process edit',
          active: true,
        },
      ],
    },
  },
  {
    path: '/company/project/:idProject/process/create',
    name: 'pages-process-create',
    component: () => import('@/views/interview-platform/project/ProcessEdit.vue'),
    meta: {
      pageTitle: 'Process Create',
      breadcrumb: [
        {
          text: 'Project',
          to: { name: 'pages-company-projects' },
        },
        {
          text: 'Process Create',
          active: true,
        },
      ],
    },
  },
]
