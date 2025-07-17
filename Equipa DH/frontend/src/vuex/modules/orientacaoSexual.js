import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setOrientacaoSexual (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getOrientacaoSexual (context) {
    Vue.http.get('api/orientacao-sexual/combo').then(response => {
      context.commit('setOrientacaoSexual', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
