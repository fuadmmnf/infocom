export const state = () => ({
  slaplans: [],
  popaddresses: [],
  departments: [],
  helptopics: [],
})

export const getters = {
  getSlaPlans: (state) => state.slaplans,
  getPopAddresses: (state) => state.popaddresses,
  getDepartments: (state) => state.departments,
  getHelpTopics: (state) => state.helptopics,
}
export const mutations = {
  setSlaPlans(state, plans) {
    state.slaplans = plans
  },
  setPopAddresses(state, popaddresses){
    state.popaddresses = popaddresses
  },
  setDepartments(state, departments) {
    state.departments = departments
  },
  setHelpTopics(state, topics) {
    state.helptopics = topics
  },
}


export default {state, getters, mutations}
