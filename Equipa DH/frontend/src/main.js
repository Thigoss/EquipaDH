// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import { sync } from 'vuex-router-sync'
import Vue from 'vue'
import Vuex from 'vuex'
import VueResource from 'vue-resource'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import App from './App'
import routes from '@/router/index'
import VuexStore from '@/vuex/store'
import VueTheMask from 'vue-the-mask'
import ViaCep from 'vue-viacep'
import LoginInterceptors from '@/services/interceptors'
import '@/templates/utils/filters'
import '@/assets/global.css'
import '@fortawesome/fontawesome-free/css/all.css'
import '@fortawesome/fontawesome-free/js/all.js'
import auxMethods from './plugins/auxMethods'
import momment from 'moment'
import {
  startSessionTimer,
  resetSessionTimer,
  addSessionTimerEvents,
  removeSessionTimerEvents
} from './services/sessionManager'
import '@/mixins/vue-editor-config'

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(Vuex)
Vue.use(VueResource)
Vue.use(VueRouter)
Vue.use(VueTheMask)
Vue.use(ViaCep)
Vue.use(auxMethods)

Vue.http.options.root = process.env.ROOT_API1
Vue.prototype.$globalLoading = false
Vue.prototype.$moment = momment

const store = new Vuex.Store(VuexStore)

const router = new VueRouter({
  mode: 'history',
  routes
})

LoginInterceptors.checkAuth()
LoginInterceptors.refreshAuth()
LoginInterceptors.refreshNotifications()

Vue.prototype.$startSessionTimer = startSessionTimer
Vue.prototype.$resetSessionTimer = resetSessionTimer
Vue.prototype.$addSessionTimerEvents = addSessionTimerEvents
Vue.prototype.$removeSessionTimerEvents = removeSessionTimerEvents

sync(store, router)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  watch: {
    '$route' (to, from) {
      this.$store.dispatch('clearRegistries')
    }
  },
  components: { App },
  template: '<App/>'
})
