export const state = () => ({
  user: localStorage.getItem('user')
    ? JSON.parse(localStorage.getItem('user'))
    : null,
  isActionRunning: false,
})

export const getters = {
  getUser: (state) => state.user,
  getActionRunningState: (state) => state.isActionRunning,
  hasCustomerAccess: (state) => state.user && (state.user.customer !== undefined || state.user.super_admin !== undefined),
  hasAuthorityAccess: (state) => state.user && (state.user.callcenter_agent !== undefined || state.user.support_agent !== undefined || state.user.super_admin !== undefined),
  hasCallcenterAccess: (state) => state.user && (state.user.callcenter_agent !== undefined || state.user.super_admin !== undefined),
  hasSupportAgentAccess: (state) => state.user && (state.user.support_agent !== undefined || state.user.super_admin !== undefined),
  hasAdminAccess: (state) => state.user && state.user.super_admin !== undefined
}
export const mutations = {
  changeActionRunningState(state, status) {
    state.isActionRunning = status
  },
  storeUser(state, user) {
    state.user = user
    user
      ? localStorage.setItem('user', JSON.stringify(user))
      : localStorage.removeItem('user')
  },

  init(state) {
    console.log("init is being callled ******************************************")
    state.user = localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user'))
      : null
  }
}


export default { state, getters, mutations }
