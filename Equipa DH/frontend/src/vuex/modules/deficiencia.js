import Vue from 'vue'

const state = {
  lista: []
}

const mutations = {
  setDeficiencias (state, data) {
    state.lista = data
  }
}

const getters = {

}

const actions = {
  getDeficiencias (context) {
    Vue.http.get('api/deficiencia/combo').then(response => {
      context.commit('setDeficiencias', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
