<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Políticas Públicas</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" v-if="can('programa.store')" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Pesquisar por Nome da Política Pública:">
                <b-form-input type="text" v-model="filtros.filter"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.ativo" placeholder="" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in situacoes" :key="item.id" :value="item.value"> {{ item.text }}</option>
                </b-form-select>
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
              <span>
                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Nome da Políticas Pública</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="programas.data !== undefined">
                    <tr class="action" v-for="programa in programas.data" :key="programa.id">
                      <td tabindex="0">{{ programa.nome }}</td>
                      <td tabindex="0">
                        <b-badge v-if="programa.ativo" variant="success">Ativo</b-badge>
                        <b-badge v-if="!programa.ativo" variant="danger">Inativo</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('programa.show')" class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizar(programa.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('programa.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(programa.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="programa.ativo && can('user.inativar')" class="btn btn-sm btn-secondary action"
                          title="Desativar" @click="alterarSituacao(programa.id, false)">
                          <em class="fa fa-toggle-off"></em>
                        </button>
                        <button v-if="!programa.ativo && can('user.ativar')" class="btn btn-sm btn-secondary action"
                          title="Ativar" @click="alterarSituacao(programa.id, true)">
                          <em class="fa fa-toggle-on"></em>
                        </button>
                        <button v-if="can('programa.destroy')" class="btn btn-sm btn-secondary action" title="Excluir"
                          @click="excluir(programa.id)">
                          <em class="fa fa-trash"></em>
                        </button>
                        <b-button v-if="can('programa.show')" size="sm" variant="secondary" class="action"
                          title="Histórico" @click="historico(programa)">
                          <em class="fa fa-history"></em>
                        </b-button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="3">Nenhuma política pública registrada.</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="programas.data !== undefined" :totalPorPagina="totalPorPagina"
                      :resource="resource" :filtros="filtros" :origin="'Programas'"></pagination-page>
                  </b-col>
                </b-row>
              </span>
            </b-container>
          </b-col>
        </b-row>
      </b-card>
    </b-overlay>
    <!-- Modal com a lista de histórico -->
    <b-modal v-model="modalHistorico" title="Histórico de Alterações" size="lg" ok-only>
      <b-row>
        <b-col sm="12">
          <b-container fluid>
            <span class="table-responsive text-center">
              <label style="font-size: 13px;" class="form-text text-muted">O identificador N/A em Unidades/Instituições
                ou Perfis aparece em casos feitos antes da alteração para exibir os mesmos.</label>
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th tabindex="0" scope="col">Data</th>
                    <th tabindex="0" scope="col">Usuário</th>
                    <th tabindex="0" scope="col">Perfil</th>
                    <th tabindex="0" scope="col">Unidade/Instituição</th>
                    <th tabindex="0" scope="col">Ação</th>
                    <th tabindex="0" scope="col">Detalhes</th>
                  </tr>
                </thead>
                <tbody v-if="historicos.length !== 0">
                  <tr class="action" v-for="item in historicos" :key="item.id">
                    <td tabindex="0">{{ $moment(item.created_at).format('DD/MM/YYYY HH:mm') }}</td>
                    <td tabindex="0">{{ item.user.cpf + ' - ' + item.user.nome }}</td>
                    <td tabindex="0">
                      {{
                        item.contexto && item.contexto.perfil ?
                          item.contexto.perfil.nome :
                          'Sem perfil'
                      }}
                    </td>
                    <td tabindex="0">
                      {{
                        item.contexto && item.contexto.unidade ?
                          item.contexto.unidade.nome :
                          (
                            item.instituicao ?
                              item.instituicao.razao_social :
                              'N/A'
                          )
                      }}
                    </td>
                    <td tabindex="0">{{ item.observacao }}</td>
                    <td tabindex="0">
                      <b-button size="sm" @click="openModalDetalhes(item)">
                        <em class="fa fa-search"></em>
                      </b-button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr class="action">
                    <td tabindex="0" colspan="4">Nenhum histórico cadastrado</td>
                  </tr>
                </tbody>
              </table>
            </span>
          </b-container>
        </b-col>
      </b-row>
    </b-modal>
    <b-modal v-if="detalhes" v-model="modalDetalhes" title="Detalhes" size="lg" ok-only>
      <b-row>
        <b-col sm="12">
          <p><b>Nome:</b> {{ detalhes.nome }}</p>
        </b-col>
      </b-row>
      <b-row>
        <b-col sm="12">
          <p><b>Descrição:</b> <br><span v-html="detalhes.descricao"></span></p>
        </b-col>
      </b-row>
      <b-row>
        <b-col sm="12">
          <b>Logomarca:</b>
          <b-button v-if="detalhes.logomarca" size="sm" variant="primary"
            @click="$abrirDocumento('programa_historico', detalhes.id)">
            <em class="fa fa-download"></em>
          </b-button>
          <p v-else>
            Sem logomarca.
          </p>
        </b-col>
      </b-row>
    </b-modal>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'
import Datepicker from 'vuejs-datepicker';
import { ptBR } from 'vuejs-datepicker/dist/locale'
import moment from 'moment'
import PaginationPage from '@/components/shared/PaginationPage'

miniToastr.init()

export default {
  components: {
    Pagination,
    PaginationPage,
    Multiselect,
    Datepicker
    /* formatDateToBR: formatDateToBR */
  },
  created () {
    if (!this.can('programa.index')) {
      this.$router.push('/home')
    }

    let filtros = this.$store.getters.getPageFilter('Programas')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'programa',
      overlay: false,
      historicos: [],
      modalHistorico: false,
      modalDetalhes: false,
      detalhes: null,
      situacoes: [
        { value: false, text: 'Inativo' },
        { value: true, text: 'Ativo' }
      ],
      filtros: {
        filter: '',
        id: '',
        ativo: ''
      },
      ptBR: ptBR
    }
  },
  computed: {
    programas () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Programas', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push('/programa/cadastrar')
    },
    editar (id) {
      this.$router.push('/programa/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/programa/' + id + '/visualizar')
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir a política pública?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/programa/' + id).then(response => {
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
          let url = 'api/programa/' + id + (situacao ? '/ativo' : '/inativo')
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
    historico (programa) {
      this.historicos = []
      this.$http.get('api/programa-historico/' + programa.id).then(response => {
        if (response.body.success) {
          this.historicos = response.body.data
          this.modalHistorico = true
        } else {
          this.erro(response.body.msg)
        }
      }).catch(erro => {
        if (!erro.body.success) {
          this.erro(erro.body.msg)
        }
      })
    },
    openModalDetalhes (item) {
      this.detalhes = item
      this.modalDetalhes = true
    },
    download (file) {

    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.id = ''
      this.filtros.ativo = ''
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
