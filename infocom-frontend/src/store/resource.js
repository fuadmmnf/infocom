export const state = () => ({
  slaplans: [],
  departments: [],
  helptopics: [],
})

export const getters = {
  getSlaPlans: (state) => state.slaplans,
  getDepartments: (state) => state.departments,
  getHelpTopics: (state) => state.helptopics,
}
export const mutations = {
  setSlaPlans(state, plans) {
    state.slaplans = plans
  },
  setDepartments(state, departments) {
    state.departments = departments
  },
  setHelpTopics(state, topics) {
    state.helptopics = topics
  },
}


export default {state, getters, mutations}
