<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Solicitações de Credenciamento</strong></h3>
            </b-col>
            <!--<b-col md="3">
            <b-button block variant="primary" v-if="can('solicitacao.store')" @click="cadastrar()">
              <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
            </b-button>
          </b-col>-->
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Nº da Solicitação:">
                <b-input-group>
                  <b-form-input v-mask="'#######################'" type="text" class="form-control"
                    v-model.trim="filtros.id" />
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.situacao" placeholder="">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in situacoes" :key="item.id" :value="item.value"> {{ item.text }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Data de:">
                <b-form-input type="date" v-model="filtros.data_cadastro_inicio"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Até:">
                <b-form-input type="date" v-model="filtros.data_cadastro_fim"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
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
          </b-row>
          <b-row>
            <b-col md="12" sm="12">
              <b-form-group tabindex="0" label="Pesquisar por Instituição CNPJ - Razão:">
                <b-input-group>
                  <b-form-input type="text" v-mask="'##.###.###/####-##'" v-model="filtros.cnpj"
                    placeholder="##.###.###/####-##"></b-form-input>
                  <b-form-input type="text" v-model="filtros.instituicao" placeholder="Razão Social"></b-form-input>
                </b-input-group>
              </b-form-group>
            </b-col>
          </b-row>
          <hr>
          <b-row>
            <b-col md="12" sm="12">
              <b-row>
                <b-col md="3" sm="12">
                  <b-form-group tabindex="0" label="Ordenar por:">
                    <b-form-select v-model="filtros.order" placeholder="">
                      <option selected :value="null">Selecione</option>
                      <option value="created_at">Data de Cadastro</option>
                      <option value="updated_at">Data de Atualização</option>
                    </b-form-select>
                  </b-form-group>
                </b-col>
                <b-col md="3" sm="12">
                  <b-form-group tabindex="0" label="Ordem:">
                    <b-form-select v-model="filtros.orderBy" placeholder="">
                      <option selected :value="null">Selecione</option>
                      <option value="asc">Crescente</option>
                      <option value="desc">Decrescente</option>
                    </b-form-select>
                  </b-form-group>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
          <hr>

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
                      <th tabindex="0" scope="col">Solicitação</th>
                      <th tabindex="0" scope="col">Data</th>
                      <th tabindex="0" scope="col">Data de Atualização</th>
                      <th tabindex="0" scope="col">CPF</th>
                      <th tabindex="0" scope="col">Nome</th>
                      <th tabindex="0" scope="col">UF</th>
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">E-mail</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="solicitacoes.data !== undefined">
                    <tr class="action" v-for="solicitacao in solicitacoes.data" :key="solicitacao.id">
                      <td tabindex="0">{{ solicitacao.id }}</td>
                      <td tabindex="0">{{ solicitacao.created_at }}</td>
                      <td tabindex="0">{{ solicitacao.updated_at }}</td>
                      <td tabindex="0">{{ solicitacao.cpf | prepararCpf }}</td>
                      <td tabindex="0">{{ solicitacao.nome }}</td>
                      <td tabindex="0">
                        <span v-if="solicitacao.instituicao">
                          {{ solicitacao.instituicao.estado.sigla }}
                        </span>
                        <span v-else-if="solicitacao.solicitacao_instituicao">
                          {{ solicitacao.solicitacao_instituicao[0].estado.sigla }}
                        </span>
                        <span v-else>
                          Não informado
                        </span>
                      </td>
                      <td tabindex="0">
                        <span v-if="solicitacao.instituicao">
                          {{ solicitacao.instituicao.cnpj + ' - ' + solicitacao.instituicao.razao_social }}
                        </span>
                        <span v-else-if="solicitacao.solicitacao_instituicao">
                          {{ solicitacao.solicitacao_instituicao[0].cnpj + ' ' +
                            solicitacao.solicitacao_instituicao[0].razao_social }}
                        </span>
                        <span v-else>
                          Não informado
                        </span>
                      </td>
                      <td tabindex="0">{{ solicitacao.email }}</td>
                      <td tabindex="0">
                        <b-badge v-if="solicitacao.situacao === 'PE'" variant="info">Pendente</b-badge>
                        <b-badge v-if="solicitacao.situacao === 'AP'" variant="success">Aprovado</b-badge>
                        <b-badge v-if="solicitacao.situacao === 'RE'" variant="danger">Reprovado</b-badge>
                        <b-badge v-if="solicitacao.situacao === 'DE'" variant="warning">Devolvido</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('solicitacao.show')" class="btn btn-sm btn-secondary action"
                          title="Analisar Solicitação" @click="visualizar(solicitacao.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('solicitacao.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(solicitacao.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <!-- <button v-if="can('solicitacao.destroy')" class="btn btn-sm btn-secondary action" title="Excluir" @click="excluir(solicitacao.id)">
                        <em class="fa fa-trash"></em>
                      </button> -->
                        <button v-if="can('solicitacao.show')" class="btn btn-sm btn-secondary action"
                          title="Visualizar Historico" @click="visualizarHistorico(solicitacao.id)">
                          <em class="fa fa-list"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="9">Nenhuma solicitação cadastrada</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="solicitacoes.data !== undefined"
                      :totalPorPagina="totalPorPagina" :resource="resource" :filtros="filtros"
                      :origin="'Solicitacoes'"></pagination-page>
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
      </b-card>
      <!-- Modal com a lista de histórico -->
      <b-modal v-model="modalHistorico" title="Histórico de Solicitação" size="lg" ok-only>
        <b-row>
          <b-col sm="12">
            <b-container fluid>
              <span class="table-responsive">
                <label style="font-size: 13px;" class="form-text text-muted">O identificador N/A em
                  Unidades/Instituições ou Perfis aparece em casos feitos antes da alteração para exibir os
                  mesmos.</label>
                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Data</th>
                      <th tabindex="0" scope="col">Usuário</th>
                      <th tabindex="0" scope="col">Perfil</th>
                      <th tabindex="0" scope="col">Unidade/Instituição</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Detalhes</th>
                    </tr>
                  </thead>
                  <tbody v-if="historico.length !== 0">
                    <tr class="action" v-for="item in historico" :key="item.id">
                      <td tabindex="0">{{ item.created_at }}</td>
                      <td tabindex="0">{{ item.user.cpf + ' - ' + item.user.nome }}</td>
                      <td tabindex="0">
                        {{
                          item.contexto && item.contexto.perfil ?
                            item.contexto.perfil.nome :
                            'N/A'
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
                      <td tabindex="0">
                        <b-badge v-if="item.situacao === 'PE'" variant="info">Pendente</b-badge>
                        <b-badge v-if="item.situacao === 'AP'" variant="success">Aprovado</b-badge>
                        <b-badge v-if="item.situacao === 'RE'" variant="danger">Reprovado</b-badge>
                        <b-badge v-if="item.situacao === 'DE'" variant="warning">Devolvido</b-badge>
                      </td>
                      <td tabindex="0" class="text-center">
                        <b-button v-if="item.situacao === 'RE' || item.situacao === 'DE' || item.situacao === 'PE'"
                          size="sm" @click="openModalDetalhes(item)">
                          <em class="fa fa-search"></em>
                        </b-button>
                        <span v-else>
                          N/A
                        </span>
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
            <p><b>Observação:</b> {{ detalhes.observacao }}</p>
          </b-col>
        </b-row>
        <b-row v-if="detalhes.anexo">
          <b-col sm="12">
            <p>
              <b>Anexo:</b>
              <b-button @click="$abrirDocumento('anexo_historico_solicitacao', detalhes.id)">
                <em class="fa fa-download"></em>
              </b-button>
            </p>
          </b-col>
        </b-row>
      </b-modal>
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
    if (!this.can('solicitacao.index')) {
      this.$router.push('/home')
    }

    let filtros = this.$store.getters.getPageFilter('Solicitacoes')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'solicitacao',
      overlay: false,
      modalDetalhes: false,
      detalhes: null,
      situacoes: [
        { value: 'PE', text: 'Pendente' },
        { value: 'AP', text: 'Aprovado' },
        { value: 'DE', text: 'Devolvido' },
        { value: 'RE', text: 'Reprovado' }
      ],
      filtros: {
        filter: '',
        id: '',
        nome: null,
        email: null,
        cpf: null,
        instituicao: null,
        cnpj: null,
        situacao: '',
        data_cadastro_inicio: null,
        data_cadastro_fim: null,
        order: null,
        orderBy: null
      },
      modalHistorico: false,
      historico: [],
      ptBR: ptBR
    }
  },
  computed: {
    solicitacoes () {
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
    visualizarHistorico (id) {
      this.modalHistorico = true
      this.$http.get('api/solicitacao/' + id + '/historico').then(response => {
        this.historico = response.body.data
      })
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Solicitacoes', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push('/solicitacao/cadastrar')
    },
    editar (id) {
      this.$router.push('/solicitacao/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/solicitacao/' + id + '/visualizar')
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir a solicitação?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/solicitacao/' + id).then(response => {
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
    openModalDetalhes (item) {
      this.detalhes = item
      this.modalDetalhes = true
    },
    navigate (route) {
      this.$router.push(route)
    },
    exportarExcel () {
      this.$store.state.loader.loader = true

      this.$http.post('api/solicitacao/export/xls', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'solicitacoes.xlsx'
          link.click()
        }

        this.$store.state.loader.loader = false
      })
    },
    exportarPDF () {
      this.$store.state.loader.loader = true

      this.$http.post('api/solicitacao/export/pdf', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'solicitacoes.pdf'
          link.click()
        }

        this.$store.state.loader.loader = false
      }).catch(err => {
        console.log(err)
        this.$store.state.loader.loader = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.id = ''
      this.filtros.situacao = ''
      this.filtros.nome = null
      this.filtros.email = null
      this.filtros.cpf = null
      this.filtros.instituicao = null
      this.filtros.cnpj = null
      this.filtros.data_cadastro_inicio = null
      this.filtros.data_cadastro_fim = null
      this.filtros.order = null
      this.filtros.orderBy = null
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
