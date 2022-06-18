export default [
  {
    path: '/meeting',
    name: 'interview-meeting-base',
    component: () => import('@/views/interview-platform/interview/Meeting.vue'),
    meta: {
      resource: 'ROLE_USER',
    },
  },
  {
    path: '/meeting/:id',
    name: 'interview-meeting',
    component: () => import('@/views/interview-platform/interview/Meeting.vue'),
    meta: {
      resource: 'ROLE_USER',
    },
  },
  {
    path: '/meeting/:id/practice-test',
    name: 'interview-meeting-practice-test',
    component: () => import('@/views/interview-platform/interview/PracticeTest.vue'),
    meta: {
      resource: 'ROLE_USER',
    },
  },
  {
    path: '/meeting/:id/result',
    name: 'interview-meeting-result',
    component: () => import('@/views/interview-platform/interview/PracticeTestAnswer.vue'),
    meta: {
      resource: 'ROLE_USER',
    },
  },
  {
    path: '/meeting/:id/create-test',
    name: 'interview-meeting-create-test',
    component: () => import('@/views/interview-platform/interview/EditTest.vue'),
    meta: {
      resource: 'ROLE_USER',
    },
  },
]
