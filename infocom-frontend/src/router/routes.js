const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {path: '', name: 'home', component: () => import('pages/Index.vue')},
      {path: 'login', name: 'login', component: () => import('pages/Login.vue')},
      {path: 'complain', name: 'customer-complain', component: () => import('pages/CustomerComplain.vue')},
    ]
  },

  {
    path: '/dashboard',
    meta: {
      requiresAuth: true
    },
    component: () => import('layouts/DashboardLayout.vue'),
    children: [
      {path: '', name: 'dashboard-home', component: () => import('pages/Dashboard/Index.vue')},
      {path: 'staffs/:type', name: 'dashboard-staffs', component: () => import('pages/Dashboard/Staffs.vue')},
      {path: 'customers', name: 'dashboard-customers', component: () => import('pages/Dashboard/Customers.vue')},
      {path: 'resources', name: 'dashboard-resources', component: () => import('pages/Dashboard/Resources.vue')},
      {path: 'complains', name: 'dashboard-complains', component: () => import('pages/Dashboard/Complains.vue')},
      {path: 'reports', name: 'dashboard-reports', component: () => import('pages/Dashboard/Reports.vue')},
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
