import Vue from 'vue'

const state = {
  lista: [],
  listaInativos: []
}

const mutations = {
  setInstituicoes (state, data) {
    state.lista = data.filter(instituicao => instituicao.ativo === true)
    state.listaInativos = data.filter(instituicao => instituicao.ativo === false)
  }
}

const getters = {
  getAllInstituicoes: state => {
    return state.lista.concat(state.listaInativos)
  }
}

const actions = {
  getInstituicoes (context) {
    Vue.http.get('api/instituicao/combo').then(response => {
      context.commit('setInstituicoes', response.body.data)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
