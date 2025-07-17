import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setCronogramas (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getCronogramas (context) {
    Vue.http.get('api/cronograma/combo').then(response => {
      context.commit('setCronogramas', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
