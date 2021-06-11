export const state = () => ({
  user: localStorage.getItem('user')
    ? JSON.parse(localStorage.getItem('user'))
    : null,
  isActionRunning: false,
})

export const getters = {
  getUser: (state) => state.user,
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


export default {state, getters, mutations}
