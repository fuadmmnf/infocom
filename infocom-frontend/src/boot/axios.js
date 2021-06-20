import Vue from 'vue'
import axios from 'axios'

export default ({store, router}) => {
  // axios.defaults.baseURL = process.env.PROD ? 'http://221.120.96.121/api' : 'http://127.0.0.1:8000/api';
  axios.defaults.baseURL = process.env.PROD ? 'http://127.0.0.1:8000/api' : 'http://127.0.0.1:8000/api';


  // axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
  axios.defaults.headers.post['Content-Type'] = 'application/json';
  // axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;


  axios.interceptors.request.use((config) => {
    if (store.getters.getUser) {
      config.headers.common.Authorization = 'Bearer ' + store.getters.getUser.token
    }
    store.commit('changeActionRunningState', true)
    return config
  })

  axios.interceptors.response.use((r) => {
    store.commit('changeActionRunningState', false)
    return r
  }, error => {
    store.commit('changeActionRunningState', false)
    if (error.status === 403 || error.status === 401) {
      store.commit('storeUser', null)
      router.push({name: 'login'})
    }
    return error
  })

  Vue.prototype.$axios = axios
};

