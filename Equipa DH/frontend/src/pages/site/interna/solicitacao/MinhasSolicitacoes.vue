<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="8">
              <h3 tabindex="0"><strong>Minhas Solicitações de Credenciamento</strong></h3>
            </b-col>
            <b-col md="4">
              <b-button block variant="primary" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Solicitar Credenciamento ao Sistema</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <br>

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
                      <th tabindex="0" scope="col">CPF</th>
                      <th tabindex="0" scope="col">Nome</th>
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
                      <td tabindex="0">{{ solicitacao.cpf }}</td>
                      <td tabindex="0">{{ solicitacao.nome }}</td>
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
                        <button v-if="solicitacao.situacao == 'DE'" class="btn btn-sm btn-secondary action"
                          title="Editar" @click="editar(solicitacao.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="solicitacao.situacao == 'DE' || solicitacao.situacao == 'RE'"
                          class="btn btn-sm btn-secondary action" title="Justificar"
                          @click="openModalJustificativa(solicitacao.id)">
                          <em class="fa fa-comment"></em>
                        </button>
                        <button class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizar(solicitacao.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="solicitacao.situacao == 'PE'" class="btn btn-sm btn-secondary action"
                          title="Excluir" @click="excluir(solicitacao.id)">
                          <em class="fa fa-trash"></em>
                        </button>
                        <button class="btn btn-sm btn-secondary action" title="Visualizar Historico"
                          @click="visualizarHistorico(solicitacao.id)">
                          <em class="fa fa-list"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="8">Nenhuma solicitação cadastrada</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="solicitacoes.data !== undefined"
                      :totalPorPagina="totalPorPagina" :resource="resource" :filtros="filtros"
                      :origin="'MinhasSolicitacoes'"></pagination-page>
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
                      <td tabindex="0">{{ item.user.nome }}</td>
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

      <!-- Modal para enviar justificativa com texto e anexo -->
      <b-modal v-model="modalJustificativa" title="Justificativa" size="lg" ok-only @ok="enviarJustificativa">
        <b-row>
          <b-col sm="12">
            <b-form-group label="Justificativa: *">
              <b-form-textarea id="justificativa" v-model="form_justificativa.observacao" rows="3"
                placeholder="Digite a justificativa" required></b-form-textarea>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-form-group label="Anexo" label-for="anexo">
              <b-form-file ref="docAnexoJustificativa" class="fileInput" accept=".doc, .docx, .pdf"
                v-on:change="uploadAnexoJustificativa" v-model="form_justificativa.anexo" browse-text="Selecione"
                placeholder="Upload"></b-form-file>
            </b-form-group>
          </b-col>
        </b-row>
      </b-modal>
    </b-overlay>
  </span>
</template>

<script>
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage'

miniToastr.init()

export default {
  components: {
    Pagination,
    PaginationPage,
    Multiselect,
    Datepicker
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'minhas-solicitacoes',
      overlay: false,
      modalDetalhes: false,
      modalJustificativa: false,
      form_justificativa: {
        observacao: '',
        anexo: null
      },
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
        situacao: ''
      },
      modalHistorico: false,
      historico: [],
      ptBR: ptBR
    }
  },
  created () {
    let filtros = this.$store.getters.getPageFilter('MinhasSolicitacoes')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
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
      this.$store.dispatch('updateFilter', { page: 'MinhasSolicitacoes', filter: this.filtros })
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
    openModalJustificativa (id) {
      this.form_justificativa.id = id
      this.modalJustificativa = true
    },
    uploadAnexoJustificativa (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docHabilitacao.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form_justificativa.anexo = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    enviarJustificativa () {
      if (this.form_justificativa.justificativa === '') {
        this.erro('Justificativa é obrigatória.')
        return
      }
      this.$http.put('api/solicitacao/' + this.form_justificativa.id + '/justificativa', this.form_justificativa).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)
          this.modalJustificativa = false
          this.pesquisar()
        }
      }).catch(erro => {
        if (!erro.body.success) {
          this.erro(erro.body.msg)
          this.pesquisar()
        }
      })
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.id = ''
      this.filtros.situacao = ''
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
