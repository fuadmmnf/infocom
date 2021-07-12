const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      {path: '', name: 'home', component: () => import('pages/Index.vue')},
      {path: 'login', name: 'login', component: () => import('pages/Login.vue')},
      {path: 'complain/:complain_code', name: 'complain-feedback', component: () => import('pages/CustomerComplainFeedback.vue')},
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
      {path: 'customers/:customer_id', name: 'dashboard-customer-detail', component: () => import('pages/Dashboard/CustomerDetail.vue')},
      {path: 'resources', name: 'dashboard-resources', component: () => import('pages/Dashboard/Resources.vue')},
      {path: 'notices', name: 'dashboard-notices', component: () => import('pages/Dashboard/Notices.vue')},
      {path: 'complains', name: 'dashboard-complains', component: () => import('pages/Dashboard/Complains.vue')},
      {path: 'reports', name: 'dashboard-reports', component: () => import('pages/Dashboard/Reports.vue')},
      {path: 'profile', name: 'dashboard-profile', component: () => import('pages/Dashboard/Profile.vue')},
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
