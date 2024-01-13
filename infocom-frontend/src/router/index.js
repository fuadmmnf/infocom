import Vue from 'vue'
import VueRouter from 'vue-router'

import routes from './routes'

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default function ({ store } ) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  store.commit('init')

  Router.beforeEach((to, from, next) => {
    const currentUser = store.getters.getUser
    if (currentUser === null) {
      if(['login','register', 'customer-complain', 'complain-feedback'].includes(to.name)){
        next()
      } else {
        next('/login')
      }
    } else if( ['login', 'register'].includes(to.name)){
      next('/')
    }
    else {
      next()
    }
  })

  return Router
}
