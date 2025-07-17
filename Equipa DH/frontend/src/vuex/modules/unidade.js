import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setUnidades (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getUnidades (context) {
    Vue.http.get('api/unidade/combo').then(response => {
      context.commit('setUnidades', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
