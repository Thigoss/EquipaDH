<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Usuários</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" v-if="can('user.store')" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Novo</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Nome:">
                <b-form-input type="text" v-model="filtros.nome"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="E-mail:">
                <b-form-input type="text" v-model="filtros.email"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="CPF:">
                <b-form-input type="text" v-model="filtros.cpf" v-mask="'###.###.###-##'"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Perfil:">
                <b-form-select v-model="filtros.perfil_id" :options="perfis" value-field="id" text-field="nome">
                  <option :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Unidade:">
                <b-form-select v-model="filtros.unidade_id" :options="unidades" value-field="id" text-field="nome">
                  <option :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Instituição:">
                <b-form-select v-model="filtros.instituicao_id" :options="instituicoes" value-field="id"
                  text-field="razao_social">
                  <option :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Tipo de Usuário:">
                <b-form-select v-model="filtros.tipo"
                  :options="[{ value: 'I', text: 'Interno' }, { value: 'E', text: 'Externo' }]"
                  placeholder="Selecione"></b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.situacao" :options="situacoes" placeholder="Selecione"></b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col sm="2">
              <b-button block @click="limpar">
                <em class="fa fa-eraser"></em>&nbsp;&nbsp;<span>Limpar filtros</span>
              </b-button>
            </b-col>
            <b-col sm="2">
              <b-button block variant="primary" @click="pesquisar">
                <em class="fa fa-search"></em>&nbsp;&nbsp;<span>Pesquisar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-body>
        <b-row>
          <b-col sm="12">
            <b-container fluid>
              <span class="table-responsive">
                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">CPF</th>
                      <th tabindex="0" scope="col">Nome do Usuário</th>
                      <th tabindex="0" scope="col">Unidade</th>
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">E-mail</th>
                      <th tabindex="0" scope="col">Tipo de Usuário</th>
                      <th tabindex="0" scope="col">Perfil</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="usuarios.data !== undefined">
                    <tr class="action" v-for="usuario in usuarios.data" :key="usuario.id">
                      <td tabindex="0">{{ usuario.cpf | prepararCpf }}</td>
                      <td tabindex="0">{{ usuario.nome }}</td>
                      <td tabindex="0">
                        <span v-if="usuario.perfis">
                          <span v-for="perfil in usuario.perfis" :key="`${usuario.id}_${perfil.id}`">
                            <span v-if="perfil.unidade">{{ perfil.unidade.nome }}<br></span>
                          </span>
                        </span>
                      </td>
                      <td tabindex="0">
                        <span v-if="usuario.instituicoes">
                          <span v-for="instituicao in usuario.instituicoes" :key="`${usuario.id}_${instituicao.id}`">
                            <span v-if="instituicao.razao_social">{{ instituicao.razao_social }}<br></span>
                          </span>
                        </span>
                      </td>
                      <td tabindex="0">{{ usuario.email }}</td>
                      <td tabindex="0">{{ usuario.tipo == 'I' ? 'Interno' : 'Externo' }}</td>
                      <td tabindex="0">
                        <span v-if="usuario.perfis">
                          <span v-for="perfil in usuario.perfis" :key="`${usuario.id}_${perfil.id}`">
                            {{ perfil.perfil.nome }}<br>
                          </span>
                        </span>
                      </td>
                      <td tabindex="0">
                        <b-badge v-if="usuario.ativo" variant="success">Ativo</b-badge>
                        <b-badge v-if="!usuario.ativo" variant="danger">Inativo</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('user.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(usuario.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="can('user.update') && usuario.tipo === 'E'"
                          class="btn btn-sm btn-secondary action" title="Atualizar Instituição"
                          @click="modalAtualizarInstituicao(usuario.id)">
                          <em class="fa fa-building"></em>
                        </button>
                        <button v-if="can('user.update') && usuario.tipo === 'I'"
                          class="btn btn-sm btn-secondary action" title="Vincular Usuário"
                          @click="abrirModalVincular(usuario)">
                          <em class="fa fa-link"></em>
                        </button>
                        <button v-if="can('user.show')" class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizar(usuario.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('user.destroy')" class="btn btn-sm btn-secondary action" title="Excluir"
                          @click="excluir(usuario.id)">
                          <em class="fa fa-trash"></em>
                        </button>
                        <button v-if="usuario.ativo && can('user.inativar')" class="btn btn-sm btn-secondary action"
                          title="Desativar" @click="alterarSituacao(usuario.id, false)">
                          <em class="fa fa-toggle-off"></em>
                        </button>
                        <button v-if="!usuario.ativo && can('user.ativar')" class="btn btn-sm btn-secondary action"
                          title="Ativar" @click="alterarSituacao(usuario.id, true)">
                          <em class="fa fa-toggle-on"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="9">Nenhum usuário cadastrado</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="usuarios.data !== undefined" :totalPorPagina="totalPorPagina"
                      :resource="resource" :filtros="filtros" :origin="'Usuarios'">
                    </pagination-page>
                  </b-col>
                </b-row>

                <!-- Botão de exportar pdf e excel -->
                <b-row>
                  <b-col md="4">
                    <b-button block variant="primary" @click="exportarExcel()">
                      <em class="fa fa-file" aria-hidden="true"></em>&nbsp;&nbsp;<span>Exportar em Excel</span>
                    </b-button>
                  </b-col>
                  <b-col md="4">
                    <b-button block variant="primary" @click="exportarPDF()">
                      <em class="fa fa-file-pdf" aria-hidden="true"></em>&nbsp;&nbsp;<span>Exportar em PDF</span>
                    </b-button>
                  </b-col>
                </b-row>
              </span>
            </b-container>
          </b-col>
        </b-row>

        <b-modal @cancel="fecharModalInstituicao" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
          :hide-footer="true" cancel-title="Voltar" centered v-model="modalInstituicao" size="lg"
          title="Atualizar Instituição">
          <b-container fluid>
            <b-row>
              <b-col cols="12">
                <b-form-group tabindex="0" label="Instituição: *">
                  <b-form-select v-model="form_instituicao.instituicao_id" placeholder="Selecione" class="mb-3">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in instituicoes" :key="item.id" :value="item.id">{{ item.razao_social }}</option>
                  </b-form-select>
                </b-form-group>
              </b-col>
            </b-row>
            <hr>
            <b-row>
              <b-col cols="4">
                <b-button block variant="primary" @click="salvarInstituicao">
                  <em class="fa fa-plus"></em>&nbsp;
                  <span>Atualizar</span>
                </b-button>
              </b-col>
              <b-col cols="4">
                <b-button block variant="secondary" @click="fecharModalInstituicao">
                  <span>Fechar</span>
                </b-button>
              </b-col>
            </b-row>
          </b-container>
        </b-modal>

        <b-modal @hidden="limparModalVincular" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
          cancel-title="Cancelar" ok-title="Vincular" :busy="modalVincular.loading" v-model="modalVincular.show"
          size="lg" title="Vincular Usuário" @ok="vincular">
          <b-container v-if="modalVincular.usuario" fluid>
            <b-row>
              <b-col cols="12">
                <b-form-group label="Usuário" label-for="usuario">
                  <b-form-input id="usuario" :value="modalVincular.usuario.nome" readonly></b-form-input>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <b-form-group label="Unidade: *" label-for="unidade">
                  <b-form-select id="unidade" v-model="modalVincular.form.unidade_id" :options="unidades"
                    value-field="id" text-field="nome" :disabled="modalVincular.loading"
                    :state="modalVincular.form.unidade_id !== null">
                    <option :value="null" disabled>Selecione</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="modalVincular.form.unidade_id !== null">Campo
                    obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <b-form-group label="Perfil: *" label-for="perfil">
                  <b-form-select id="perfil" v-model="modalVincular.form.perfil_id" :options="perfisVincular"
                    value-field="id" text-field="nome" :disabled="modalVincular.loading"
                    :state="modalVincular.form.perfil_id !== null">
                    <option :value="null" disabled>Selecione</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="modalVincular.form.perfil_id !== null">Campo
                    obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <hr>
            <b-row class="mb-3">
              <b-col>
                <b>Vinculos Atuais do Usuário</b>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <table class="table table-bordered table-striped table-sm text-center">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Perfil</th>
                      <th tabindex="0" scope="col">Unidade</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="modalVincular.usuario.perfis.length !== 0">
                    <tr class="action" v-for="item in modalVincular.usuario.perfis" :key="item.id">
                      <td tabindex="0">{{ item.perfil ? item.perfil.nome : ' - ' }}</td>
                      <td tabindex="0">{{ item.unidade ? item.unidade.nome : ' - ' }}</td>
                      <td tabindex="0">
                        <b-badge v-if="item.ativo" variant="success">Ativo</b-badge>
                        <b-badge v-else variant="danger">Inativo</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('user.update')" class="btn btn-sm btn-secondary action"
                          :title="item.ativo ? 'Inativar' : 'Ativar'" @click="alterarVinculo(item)">
                          <span v-if="item.ativo" :key="`item-${item.id}-inativo-${item.ativo}`">
                            <em class="fa fa-toggle-off"></em>
                          </span>
                          <span v-if="!item.ativo" :key="`item-${item.id}-ativo-${item.ativo}`">
                            <em class="fa fa-toggle-on"></em>
                          </span>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="4">Nenhum vínculo cadastrado.</td>
                    </tr>
                  </tbody>
                </table>
              </b-col>
            </b-row>
          </b-container>
        </b-modal>
      </b-card>
    </b-overlay>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage'

miniToastr.init()

function modalVincularDefault () {
  return {
    show: false,
    loading: false,
    usuario: null,
    form: {
      usuario_id: null,
      unidade_id: null,
      perfil_id: null
    }
  }
}

export default {
  name: 'Usuarios',
  components: {
    Pagination,
    PaginationPage,
    Multiselect,
    Datepicker
    /* formatDateToBR: formatDateToBR */
  },
  created () {
    if (!this.can('user.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getInstituicoes')
    this.$store.dispatch('getPerfis')
    this.$store.dispatch('getUnidades')

    let filtros = this.$store.getters.getPageFilter('Usuarios')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'user',
      overlay: false,
      situacoes: [
        { value: true, text: 'Ativo' },
        { value: false, text: 'Inativo' }
      ],
      filtros: {
        filter: '',
        id: '',
        nome: null,
        email: null,
        cpf: null,
        tipo: null,
        situacao: null,
        unidade_id: null,
        perfil_id: null,
        instituicao_id: null
      },
      modalInstituicao: false,
      form_instituicao: {
        instituicao_id: null
      },
      modalHistorico: false,
      modalVincular: modalVincularDefault(),
      historico: [],
      ptBR: ptBR
    }
  },
  computed: {
    usuarios () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    },
    perfis () {
      return this.$store.state.perfil.lista
    },
    perfisVincular () {
      let perfis = this.$store.state.perfil.lista

      perfis = perfis.filter(perfil => perfil.ativo === true)

      return perfis
    },
    unidades () {
      return this.$store.state.unidade.lista
    },
    instituicoes () {
      return this.$store.state.instituicao.lista
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Usuarios', filter: this.filtros })
    },
    alterarSituacao (id, situacao) {
      let msg = ''
      if (situacao) {
        msg = 'Confirma a ativação do registro selecionado?'
      } else {
        msg = 'Confirma a inativação do registro selecionado?'
      }
      this.$bvModal.msgBoxConfirm(msg, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          let url = 'api/user/' + id + (situacao ? '/ativo' : '/inativo')
          let data = {
            id: id
          }
          Vue.http.put(url, data).then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
            }
          }).catch(erro => {
            if (!erro.body.success) {
              this.erro(erro.body.msg)
              this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push('/usuario/cadastrar')
    },
    editar (id) {
      this.$router.push('/usuario/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/usuario/' + id + '/visualizar')
    },
    modalAtualizarInstituicao (id) {
      this.modalInstituicao = true
      this.form_instituicao.id = id
      this.form_instituicao.instituicao_id = this.usuarios.data.find(usuario => usuario.id === id).instituicao_id
    },
    salvarInstituicao () {
      this.$http.put('api/user/' + this.form_instituicao.id + '/instituicao', { instituicao_id: this.form_instituicao.instituicao_id }).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)
          this.modalInstituicao = false
          this.pesquisar()
        }
      })
    },
    fecharModalInstituicao () {
      this.modalInstituicao = false
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir o usuário?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/user/' + id).then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.pesquisar()
            }
          }).catch(erro => {
            if (!erro.body.success) {
              this.erro(erro.body.msg)
              this.pesquisar()
            }
          })
        }
      })
    },
    limparModalVincular () {
      this.modalVincular = modalVincularDefault()
    },
    abrirModalVincular (usuario) {
      this.modalVincular = modalVincularDefault()
      this.modalVincular.usuario = usuario
      this.modalVincular.form.usuario_id = usuario.id
      this.modalVincular.show = true
    },
    vincular (bvModalEvt) {
      bvModalEvt.preventDefault()

      if (!this.modalVincular.form.unidade_id || !this.modalVincular.form.perfil_id) {
        this.erro('Preencha todos os campos obrigatórios')
        return
      }

      if (this.modalVincular.usuario.perfis.find(perfil => perfil.unidade_id === this.modalVincular.form.unidade_id)) {
        this.erro('Usuário já vinculado a esta unidade')
        return
      }

      this.modalVincular.loading = true
      this.$http.post('api/user-vinculos', this.modalVincular.form).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)
          this.modalVincular.loading = false
          this.modalVincular.show = false
          this.pesquisar()
        }
      }).catch(erro => {
        if (!erro.body.success) {
          this.erro(erro.body.msg)
          this.modalVincular.loading = false
        }
      })
    },
    alterarVinculo (vinculo) {
      this.$bvModal.msgBoxConfirm(`Tem certeza que deseja ${(vinculo.ativo ? 'inativar' : 'ativar')} o vínculo selecionado?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.put(`api/users-unidade/alterar-vinculo/${vinculo.id}`).then((result) => {
            if (result.body.success) {
              this.sucesso(result.body.msg)
              this.modalVincular.show = false
              this.pesquisar()
            }
          }).catch((error) => {
            this.erro(error.body.msg)
          })
        }
      }).catch(error => {
        console.log(error)
      })
    },
    exportarExcel () {
      this.$store.state.loader.loader = true

      this.$http.post('api/user/export/xls', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'usuarios.xlsx'
          link.click()
        }

        this.$store.state.loader.loader = false
      }).catch(err => {
        console.log(err)
        this.$store.state.loader.loader = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },
    exportarPDF () {
      this.$store.state.loader.loader = true

      this.$http.post('api/user/export/pdf', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'usuarios.pdf'
          link.click()
        }

        this.$store.state.loader.loader = false
      }).catch(err => {
        console.log(err)
        this.$store.state.loader.loader = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.id = ''
      this.filtros.situacao = ''
      this.filtros.tipo = null
      this.filtros.nome = null
      this.filtros.email = null
      this.filtros.cpf = null
      this.filtros.perfil_id = null
      this.filtros.unidade_id = null
      this.filtros.instituicao_id = null
      this.pesquisar()
    },
    sucesso (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Sucesso',
        autoHideDelay: 10000,
        appendToast: true,
        variant: 'info'
      })
    },
    erro (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Erro',
        autoHideDelay: 10000,
        appendToast: true,
        variant: 'danger'
      })
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect.invalid .multiselect__tags,
.multiselect.invalid .multiselect__tags span,
.multiselect.invalid .multiselect__tags input {
  background: red;
}
</style>
