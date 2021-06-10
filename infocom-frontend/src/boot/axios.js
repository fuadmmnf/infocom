import Vue from 'vue'
import axios from 'axios'

export default ({store, router}) => {
  axios.defaults.baseURL = process.env.PROD ? '' : 'http://127.0.0.1:8000/api';


  // axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
  axios.defaults.headers.post['Content-Type'] = 'application/json';
  // axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;


  axios.onRequest((config) => {
    if (store.getters.getUser) {
      config.headers.common.Authorization = 'Bearer ' + store.state.user.token
      store.commit('changeActionRunningState', true)
    }
  })

  axios.onResponse((r) => {
    store.commit('changeActionRunningState', false)
  })

  axios.onError((error) => {
    store.commit('changeActionRunningState', false)
    if (error.response.status === 403 || error.response.status === 401) {
      store.commit('storeUser', null)
      router.push({name: 'login'})
    }
  })

  Vue.prototype.$axios = axios
};

