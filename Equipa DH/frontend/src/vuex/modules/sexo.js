import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setSexo (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getSexo (context) {
    Vue.http.get('api/sexo/combo').then(response => {
      context.commit('setSexo', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
