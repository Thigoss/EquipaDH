import Vue from 'vue'

const state = {
  listaCidades: [],
  listaCidadesPorEstado: [],
  listaCidadesPorEstado2: [],
  listaCidadesPorEstado3: [],
  listaCidadesPorEstado4: [],
  listaCidadesPorEstadoAtuacao: [],
  listaCidadesPorConferencia: [],
  listaCidadesPorUsuario: []
}

const mutations = {
  setCidades (state, data) {
    state.listaCidades = data
  },
  setCidadesPorEstado (state, data) {
    state.listaCidadesPorEstado = data
  },
  setCidadesPorEstado2 (state, data) {
    state.listaCidadesPorEstado2 = data
  },
  setCidadesPorEstado3 (state, data) {
    state.listaCidadesPorEstado3 = data
  },
  setCidadesPorEstado4 (state, data) {
    state.listaCidadesPorEstado4 = data
  },
  setCidadesPorEstadoAtuacao (state, data) {
    state.listaCidadesPorEstadoAtuacao = data
  },
  setCidadesPorConferencia (state, data) {
    state.listaCidadesPorConferencia = data
  },
  setCidadesPorUsuario (state, data) {
    state.listaCidadesPorUsuario = data
  }
}

const getters = {}

const actions = {
  buscarCidades (context, id) {
    if (id !== null) {
      Vue.http.get('api/cidade/' + id).then(response => {
        context.commit('setCidades', response.data.data.cidades)
      })
    } else {
      context.commit('setCidades', [])
    }
  },
  buscarCidadesPorEstado (context, id) {
    if (id !== null) {
      Vue.http.get('api/cidade/' + id).then(response => {
        context.commit('setCidadesPorEstado', response.body.data)
      })
    } else {
      context.commit('setCidadesPorEstado', [])
    }
  },
  buscarCidadesPorEstado2 (context, id) {
    if (id !== null) {
      Vue.http.get('api/cidade/buscarCidades/' + id).then(response => {
        context.commit('setCidadesPorEstado2', response.data.data.cidades)
      })
    } else {
      context.commit('setCidadesPorEstado2', [])
    }
  },
  buscarCidadesPorEstado3 (context, id) {
    if (id !== null) {
      Vue.http.get('api/cidade/buscarCidades/' + id).then(response => {
        context.commit('setCidadesPorEstado3', response.data.data.cidades)
      })
    } else {
      context.commit('setCidadesPorEstado3', [])
    }
  },
  buscarCidadesPorEstado4 (context, id) {
    if (id !== null) {
      Vue.http.get('api/cidade/buscarCidades/' + id).then(response => {
        context.commit('setCidadesPorEstado4', response.data.data.cidades)
      })
    } else {
      context.commit('setCidadesPorEstado4', [])
    }
  },
  buscarCidadesPorEstadoAtuacao (context, id) {
    Vue.http.get('api/cidade/buscarCidades/' + id).then(response => {
      context.commit('setCidadesPorEstadoAtuacao', response.data.data.cidades)
    })
  },
  buscarCidadesPorConferencia (context, id) {
    Vue.http.get('api/cidade/buscarCidadesPorConferencia/' + id).then(response => {
      context.commit('setCidadesPorConferencia', response.data.data.cidades)
    })
  },
  buscarCidadesPorUsuario (context, user_id) {
    Vue.http.get('api/cidade/buscarCidadesPorUsuario', { params: { user_id: user_id } }).then(response => {
      context.commit('setCidadesPorUsuario', response.data.data.cidades)
    })
  },
  buscarCidadesPorUf (context, id) {
    Vue.http.get('api/cidade/buscarCidades/' + id).then(response => {
      context.commit('setCidadesPorUsuario', response.data.data.cidades)
    })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
