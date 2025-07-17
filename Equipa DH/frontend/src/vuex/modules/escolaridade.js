import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setEscolaridades (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getEscolaridades (context) {
    Vue.http.get('api/escolaridade/combo').then(response => {
      context.commit('setEscolaridades', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
