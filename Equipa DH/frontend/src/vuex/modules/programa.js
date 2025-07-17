import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setProgramas (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getProgramas (context) {
    Vue.http.get('api/programa/combo').then(response => {
      context.commit('setProgramas', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
