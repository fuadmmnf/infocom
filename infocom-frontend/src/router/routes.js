const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {path: '', component: () => import('pages/Index.vue')},
      {path: 'login', component: () => import('pages/Login.vue')},
      {path: 'complain', component: () => import('pages/CustomerComplain.vue')},
    ]
  },

  {
    path: '/dashboard',
    meta: {
      requiresAuth: true
    },
    component: () => import('layouts/DashboardLayout.vue'),
    children: [
      {path: '', component: () => import('pages/Index.vue')},
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
