export default function () {
  return [
    {
      title: 'Dashboard',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-home'}
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
      route: {name: 'dashboard-staffs', params: {type: 'callcenteragents'}}
    },
    {
      title: 'Support Agents',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-staffs', params: {type: 'supportagents'}}
    },
    // {
    //   title: 'Customers',
    //   caption: '',
    //   icon: 'home',
    //   route: {name: 'dashboard-customers'}
    // },
    {
      title: 'Complains',
      caption: '',
      icon: 'home',
      route: {name: 'dashboard-complains'}
    },
    // {
    //   title: 'Working Complains',
    //   caption: '',
    //   icon: 'home',
    //   route: {name: 'dashboard-complains', params: {status: 'working'}}
    // },
    // {
    //   title: 'Feedback Complains',
    //   caption: '',
    //   icon: 'home',
    //   route: {name: 'dashboard-complains', params: {status: 'finished'}}
    // },
    // {
    //   title: 'Complain History',
    //   caption: '',
    //   icon: 'home',
    //   route: {name: 'dashboard-complains', params: {status: 'approved'}}
    // },
  ]
}
