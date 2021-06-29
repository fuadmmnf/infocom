export const state = () => ({
  slaplans: [],
  popaddresses: [],
  departments: [],
  helptopics: [],
  services: [],
})

export const getters = {
  getSLAPlans: (state) => state.slaplans,
  getPopAddresses: (state) => state.popaddresses,
  getDepartments: (state) => state.departments,
  getHelpTopics: (state) => state.helptopics,
  getServices: (state) => state.services,
}
export const mutations = {
  setSLAPlans(state, plans) {
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

  setServices(state, services) {
    state.services = services
  },
}


export default {state, getters, mutations}
