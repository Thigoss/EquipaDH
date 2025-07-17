import SessionStorage from '@/services/session-storage'
import { Jwt } from '@/services/resources'
export default {
  get token () {
    return SessionStorage.get('token')
  },
  set token (value) {
    SessionStorage.set('token', value)
  },
  get nome () {
    return SessionStorage.get('nome')
  },
  set nome (value) {
    SessionStorage.set('nome', value)
  },
  get usuario () {
    return SessionStorage.get('usuario')
  },
  set usuario (value) {
    SessionStorage.set('usuario', value)
  },
  get permissoes () {
    return SessionStorage.get('permissoes')
  },
  set permissoes (value) {
    SessionStorage.setObject('permissoes', value)
  },
  get contextos () {
    return SessionStorage.get('contextos')
  },
  set contextos (value) {
    SessionStorage.setObject('contextos', value)
  },
  get contextoAtual () {
    return SessionStorage.get('contextoAtual')
  },
  set contextoAtual (value) {
    SessionStorage.setObject('contextoAtual', value)
  },
  get perfil_id () {
    return SessionStorage.get('perfil_id')
  },
  set perfil_id (value) {
    SessionStorage.set('perfil_id', value)
  },
  get perfil_nome () {
    return SessionStorage.get('perfil_nome')
  },
  set perfil_nome (value) {
    SessionStorage.set('perfil_nome', value)
  },
  get instituicao_nome () {
    return SessionStorage.get('instituicao_nome')
  },
  set instituicao_nome (value) {
    SessionStorage.set('instituicao_nome', value)
  },
  get unidade_id () {
    return SessionStorage.get('unidade_id')
  },
  set unidade_id (value) {
    SessionStorage.set('unidade_id', value)
  },
  get unidade_nome () {
    return SessionStorage.get('unidade_nome')
  },
  set unidade_nome (value) {
    SessionStorage.set('unidade_nome', value)
  },
  accessToken (data) {
    this.token = data.token
    this.nome = data.nome
    this.usuario = data.user
    this.perfil_id = data.perfil_id
    this.perfil_nome = data.perfil_nome
    this.contextos = data.contextos
    this.contextoAtual = data.contexto_atual
    this.unidade_id = data.unidade_id
    this.unidade_nome = data.unidade_nome
    this.instituicao_nome = data.instituicao_nome

    if (data.permissoes.length > 0) {
      this.permissoes = data.permissoes
    } else {
      this.permissoes = []
    }
  },
  refreshToken () {
    return Jwt.refreshToken().then(response => {
      this.token = response.data.token
      return response
    })
  },
  getAuthorizationHeader () {
    return `Bearer ${this.token}`
  }
}
