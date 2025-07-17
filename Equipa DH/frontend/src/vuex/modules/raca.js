import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setRacas (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getRacas (context) {
    Vue.http.get('api/raca/combo').then(response => {
      context.commit('setRacas', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
