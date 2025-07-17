import Vue from 'vue'

const state = {
  listaEstados: [],
  listaEstados2: [],
  listaEstados3: [],
  listaEstados4: [],
  listaEstadosPorUsuario: []
}

const mutations = {
  setEstados (state, data) {
    state.listaEstados = (data || []).sort((a, b) => a.sigla.localeCompare(b.sigla))
  },
  setEstados2 (state, data) {
    state.listaEstados2 = data
  },
  setEstados3 (state, data) {
    state.listaEstados3 = data
  },
  setEstados4 (state, data) {
    state.listaEstados4 = data
  },
  setEstadosPorUsuario (state, data) {
    state.listaEstadosPorUsuario = data
  }
}

const getters = {
  getListaFiltroUf: state => {
    let valores = Object.entries(state.listaEstados)
    let valoresNovos = []
    let i
    for (i = 0; i < valores.length; i++) {
      valoresNovos.push(valores[i][1])
    }
    return valoresNovos
  }
}

const actions = {
  buscarEstados (context) {
    Vue.http.get('api/estado').then(response => {
      context.commit('setEstados', response.data.data)
    })
  },
  buscarEstados2 (context) {
    Vue.http.get('api/estado').then(response => {
      context.commit('setEstados2', response.data.data)
    })
  },
  buscarEstados3 (context) {
    Vue.http.get('api/estado').then(response => {
      context.commit('setEstados3', response.data.data)
    })
  },
  buscarEstados4 (context) {
    Vue.http.get('api/estado').then(response => {
      context.commit('setEstados4', response.data.data)
    })
  },
  buscarEstadosPorRegiao (context) {
    Vue.http.get('api/estado/buscarEstadosPorRegiao').then(response => {
      context.commit('setEstados', response.data.data.estados)
    })
  },
  buscarEstadosPorUsuario (context, userId) {
    Vue.http.get('api/estado/buscarEstadosPorUsuario', { params: { user_id: userId } }).then(response => {
      context.commit('setEstadosPorUsuario', response.data.data.estados)
    })
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}
