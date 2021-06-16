export default function () {
  return [
    {
      title: 'Dashboard',
      caption: '',
      icon: 'home',
      permission: null,
      route: {name: 'dashboard-home'}
    },
    {
      title: 'Resources',
      caption: '',
      icon: 'home',
      permission: 'hasAdminAccess',
      route: {name: 'dashboard-resources'}
    },
    {
      title: 'Callcenter Agents',
      caption: '',
      icon: 'home',
      permission: 'hasAdminAccess',
      route: {name: 'dashboard-staffs', params: {type: 'callcenteragents'}}
    },
    {
      title: 'Support Agents',
      caption: '',
      icon: 'home',
      permission: 'hasAdminAccess',
      route: {name: 'dashboard-staffs', params: {type: 'supportagents'}}
    },
    {
      title: 'Customers',
      caption: '',
      icon: 'home',
      permission: 'hasCallcenterAccess',
      route: {name: 'dashboard-customers'}
    },

    {
      title: 'Complains',
      caption: '',
      icon: 'home',
      permission: null,
      route: {name: 'dashboard-complains'}
    },
    {
      title: 'Reports',
      caption: '',
      icon: 'home',
      permission: null,
      route: {name: 'dashboard-reports'}
    },
  ]
}
