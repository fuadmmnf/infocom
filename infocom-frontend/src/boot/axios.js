import Vue from 'vue'
import axios from 'axios'

export default ({store, router}) => {
  axios.defaults.baseURL = process.env.PROD ? '' : 'http://127.0.0.1:8000/api';


  // axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
  axios.defaults.headers.post['Content-Type'] = 'application/json';
  // axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;


  axios.onRequest((config) => {
    if (store.user.getters.getUser) {
      config.headers.common.Authorization = 'Bearer ' + store.state.user.token
      store.commit('user/changeActionRunningState', true)
    }
  })

  axios.onResponse((r) => {
    store.commit('user/changeActionRunningState', false)
  })

  axios.onError((error) => {
    store.commit('user/changeActionRunningState', false)
    if (error.response.status === 403 || error.response.status === 401) {
      store.commit('user/storeUser', null)
      router.push({name: 'login'})
    }
  })

  Vue.prototype.$axios = axios
};

