export default function () {
  return [
    {
      title: 'Dashboard',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard'}
    },
    {
      title: 'Resources',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-resources'}
    },
    {
      title: 'Callcenter Agents',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-staffs', params: {type: 'callcenter'}}
    },
    {
      title: 'Support Agents',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-staffs', params: {type: 'supportagent'}}
    },
    {
      title: 'Customers',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-customers'}
    },
    {
      title: 'Pending Complains',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-complains', params: {status: 'pending'}}
    },
    {
      title: 'Working Complains',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-complains', params: {status: 'working'}}
    },
    {
      title: 'Feedback Complains',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-complains', params: {status: 'finished'}}
    },
    {
      title: 'Completed Complains',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-complains', params: {status: 'approved'}}
    },
  ]
}
