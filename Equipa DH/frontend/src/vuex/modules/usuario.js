import Vue from 'vue'
import JwtToken from '@/services/jwt-token'
import SessionStorage from '@/services/session-storage'

const state = {
  usuario: [],
  usuarios: [],
  usuariosCombo: [],
  usuarioEditar: [],
  usuarioVisualizar: [],
  perfilAtual: '',
  permissoes: [],
  contexto: [],
  tipoPerfil: ''
}

const getters = {
  getUsuario: state => {
    return state.usuario
  },
  getUsuariosCombo: state => {
    return state.usuariosCombo
  },
  getContextoUser: state => {
    return state.contexto
  },
  getToken: state => {
    return state.usuario.token
  },
  validaPermissao: (state) => (permissao) => {
    if (state.permissoes.indexOf(permissao) > -1) {
      return true
    } else {
      return false
    }
  }
}

const mutations = {
  setUsuario (state, data) {
    state.usuario = data
  },
  setUsuarios (state, data) {
    state.usuarios = data
  },
  setUsuariosCombo (state, data) {
    state.usuariosCombo = data
  },
  setUsuarioEditar (state, data) {
    state.usuarioEditar = data
  },
  setUsuarioVisualizar (state, data) {
    state.usuarioVisualizar = data
  },
  setPerfilAtual (state, data) {
    state.perfilAtual = data
  },
  setTipoPerfil (state, data) {
    state.tipoPerfil = data
  },
  atualizaPermissoes (state) {
    state.permissoes = SessionStorage.getObject('permissoes')
  },
  setContexto (state) {
    state.contexto = SessionStorage.getObject('contextoAtual')
  }
}

const actions = {
  getUsuarios (context, config) {
    if (!config.page) {
      config.page = 1
    }

    if (!config.limit) {
      config.limit = 200
    }

    Vue.http.get('api/user?length=' + config.limit + '&page=' + config.page).then(response => {
      context.commit('setUsuarios', response.body.data.data.data)
    })
  },
  getUsuario (context, id) {
    Vue.http.get('api/user/' + id).then(response => {
      context.commit('setUsuario', response.body.data)
      context.commit('setTipoPerfil', response.body.data.perfis[0].tipo_perfil.nome)
    })
  },
  getUsuariosCombo (context) {
    Vue.http.get('api/user/combo').then(response => {
      context.commit('setUsuariosCombo', response.body.data[0])
    })
  },
  login (context, data) {
    return JwtToken.accessToken(data)
  },
  atualizarPermissoes (context) {
    context.commit('atualizaPermissoes')
  },
  atualizarContexto (context) {
    context.commit('setContexto')
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}
