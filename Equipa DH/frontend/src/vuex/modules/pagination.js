import Vue from 'vue'

export default {
  state: {
    getList: [],
    lists: {},
    getListModal: []
  },
  mutations: {
    atualizarLista (state, data) {
      state.getList = data
    },
    atualizarListaModal (state, data) {
      state.getListModal = data
    },
    setList (state, payload) {
      Vue.set(state.lists, payload.name, payload.data)
    }
  },
  actions: {
    clearRegistries (context) {
      context.commit('atualizarLista', [])
    },
    getRegistros (context, config) {
      if (!config.page) {
        config.page = 1
      }
      if (!config.limit) {
        config.limit = 200
      }
      Vue.http.get('api/' + config.resource + '?length=' + config.limit + '&page=' + config.page).then(response => {
        context.commit('atualizarLista', response.body.data.data)
      })
    },
    getRegistrosSearch (context, config) {
      if (!config.page) {
        config.page = 1
      }
      if (!config.limit) {
        config.limit = 200
      }
      if (!config.filtros) {
        config.filtros = []
      }

      this.state.loader.loader = true

      Vue.http.get('api/' + config.resource + '?length=' + config.limit + '&page=' + config.page, { params: config.filtros }).then(response => {
        if (config.list) {
          context.commit('setList', { name: config.list, data: response.body.data })
        } else {
          context.commit('atualizarLista', response.body.data.data)
        }
      }).catch(() => {
        this.state.loader.loader = false
      }).finally(() => {
        this.state.loader.loader = false
      })
    },
    getRegistrosSearchModal (context, config) {
      if (!config.page) {
        config.page = 1
      }
      if (!config.limit) {
        config.limit = 200
      }
      if (!config.filtros) {
        config.filtros = []
      }
      Vue.http.get('api/' + config.resource + '?length=' + config.limit + '&page=' + config.page, { params: config.filtros }).then(response => {
        context.commit('atualizarListaModal', response.body.data.data)
      })
    }
  }
}
