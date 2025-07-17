<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Solicitações de Análise de Documentos</strong></h3>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Pesquisar por Política ou Instituição:">
                <b-form-input type="text" v-model="filtros.filter"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Cronograma:">
                <b-input-group class="mb-3">
                  <b-form-input type="text" class="form-control" v-model.trim="filtros.numero_cronograma" />
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Nº da Solicitação:">
                <b-input-group class="mb-3">
                  <b-form-input type="text" class="form-control" v-model.trim="filtros.id" />
                </b-input-group>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="CNPJ Instituição:">
                <b-input-group class="mb-3">
                  <b-form-input v-model="filtros.cnpj" type="text" v-mask="'##.###.###/####-##'"></b-form-input>
                </b-input-group>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
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
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.situacao" placeholder="" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in situacoes" :key="item.id" :value="item.value"> {{ item.text }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Tipo de Solicitação:">
                <b-form-select v-model="filtros.tipo" placeholder="" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in tipoAdesao" :key="item.value + item.text" :value="item.value"> {{ item.text }}
                  </option>
                </b-form-select>
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
                      <th tabindex="0" scope="col">UF</th>
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">Política Pública</th>
                      <th tabindex="0" scope="col">Cronograma</th>
                      <th tabindex="0" scope="col">Data de início</th>
                      <th tabindex="0" scope="col">Data de encerramento</th>
                      <th tabindex="0" scope="col">Tipo de solicitação</th>
                      <th tabindex="0" scope="col">Situação da Solicitação</th>
                      <th tabindex="0" scope="col">Fase</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="adesoes.data !== undefined">
                    <tr class="action" v-for="adesao in adesoes.data" :key="adesao.id">
                      <td tabindex="0">{{ adesao.id }}</td>
                      <td tabindex="0">{{ adesao.created_at }}</td>
                      <td tabindex="0">{{ adesao.updated_at }}</td>
                      <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                      <td tabindex="0">{{ `${adesao.instituicao.cnpj} - ${adesao.instituicao.razao_social}` }}</td>
                      <td tabindex="0">{{ adesao.cronograma.programa.nome }}</td>
                      <td tabindex="0">{{ adesao.cronograma.numero }}</td>
                      <td tabindex="0">{{ adesao.cronograma.data_publicacao_inicio | formatDateToBR }}</td>
                      <td tabindex="0">{{ adesao.cronograma.data_divulgacao_lista | formatDateToBR }}</td>
                      <td tabindex="0">
                        <span v-if="adesao.tipo === 'AD'">Adesão</span>
                        <span v-if="adesao.tipo === 'RA'">Recurso de Adesão</span>
                        <span v-if="adesao.tipo === 'RC'">Recurso de Classificação</span>
                        <span v-if="adesao.tipo === 'CV'">Convocação</span>
                      </td>
                      <td tabindex="0">
                        <b-badge v-if="adesao.situacao == 'PE'" variant="secondary">Pendente</b-badge>
                        <b-badge v-if="adesao.situacao == 'DV'" variant="warning">Devolvido</b-badge>
                        <b-badge v-if="adesao.situacao == 'RE'" variant="danger">Recusado</b-badge>
                        <b-badge v-if="adesao.situacao == 'AP'" variant="success">Aprovado</b-badge>
                        <b-badge v-if="adesao.situacao == 'RC'" variant="warning">Recurso</b-badge>
                      </td>
                      <td tabindex="0">
                        <b-badge v-if="adesao.cronograma.fase === 'NI'" variant="secondary">Não Iniciado</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'PU'" variant="info">Publicado</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'AD'" variant="info">Adesão e Habilitação</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'AF'" variant="info">Validação das Adesões</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'DA'" variant="info">Divulgação da Adesão</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'RA'" variant="info">Recurso Adesão</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'RF'" variant="info">Validação dos Recursos</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'DH'" variant="info">Divulgação de
                          Habilitados</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'RH'" variant="info">Recurso de Habilitados</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'HF'" variant="info">Validação de Recursos de
                          Habilitados</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'DL'" variant="info">Divulgação da Lista</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'EN'" variant="info">Encerrado</b-badge>
                        <b-badge v-if="adesao.cronograma.fase === 'CO'" variant="info">Convocação</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button
                          v-if="can('adesao.aprovar') && can('adesao.devolver') && can('adesao.recusar') && adesao.tipo === 'AD'"
                          class="btn btn-sm btn-secondary action" title="Avaliar" @click="avaliar(adesao.id)">
                          <em class="fa fa-check"></em>
                        </button>
                        <button
                          v-if="can('adesao.aprovar') && can('adesao.devolver') && can('adesao.recusar') && adesao.tipo === 'RA'"
                          class="btn btn-sm btn-warning action" title="Avaliar Recurso de Adesão"
                          @click="avaliarRecursoHabilitacao(adesao.id)">
                          <em class="fa fa-check"></em>
                        </button>
                        <button
                          v-if="can('adesao.aprovar') && can('adesao.devolver') && can('adesao.recusar') && adesao.tipo === 'RC'"
                          class="btn btn-sm btn-warning action" title="Avaliar Recurso de Classificação"
                          @click="avaliarRecursoClassificacao(adesao.id)">
                          <em class="fa fa-check"></em>
                        </button>
                        <button
                          v-if="can('adesao.aprovar') && can('adesao.devolver') && can('adesao.recusar') && adesao.tipo === 'CV'"
                          class="btn btn-sm btn-warning action" title="Avaliar Convocação"
                          @click="avaliarConvocacao(adesao.id)">
                          <em class="fa fa-check"></em>
                        </button>
                        <button
                          v-if="can('adesao.aprovar') && contextoAtual && contextoAtual.perfil && contextoAtual.perfil.tipo == 1"
                          class="btn btn-sm btn-secondary action" title="Alterar Instituição"
                          @click="modalChangeInstitute(adesao)">
                          <em class="fa fa-building"></em>
                        </button>
                        <button v-if="can('adesao.historico')" class="btn btn-sm btn-secondary action"
                          title="Visualizar Historico" @click="visualizarHistorico(adesao.id)">
                          <em class="fa fa-list"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action text-center">
                      <td tabindex="0" colspan="13">Nenhuma solicitação cadastrada</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="adesoes.data !== undefined" :totalPorPagina="totalPorPagina"
                      :resource="resource" :filtros="filtros" :origin="'Adesoes'"></pagination-page>
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
                      <th tabindex="0" scope="col">Análise</th>
                      <th tabindex="0" scope="col">Tipo</th>
                      <th tabindex="0" scope="col">Documento</th>
                      <th tabindex="0" scope="col">Formulário</th>
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
                        <b-badge v-if="item.situacao === 'RE'" variant="danger">Recusado</b-badge>
                        <b-badge v-if="item.situacao === 'DV'" variant="warning">Devolvido</b-badge>
                        <b-badge v-if="item.situacao === 'CV'" variant="warning">Convocado</b-badge>
                      </td>
                      <td tabindex="0">{{ item.ciclo ? item.ciclo + 'º' : '' }}</td>
                      <td tabindex="0">
                        <span v-if="item.tipo === 'AD'">Adesão</span>
                        <span v-if="item.tipo === 'RA'">Recurso de Adesão</span>
                        <span v-if="item.tipo === 'RC'">Recurso de Classificação</span>
                        <span v-if="item.tipo === 'CV'">Convocação</span>
                      </td>
                      <td tabindex="0" class="text-center">
                        <b-button v-if="item.arquivo" size="sm"
                          @click="$abrirDocumento('documento_historico_adesoes', item.id)">
                          <em class="fa fa-download"></em>
                        </b-button>
                        <span v-else>
                          N/A
                        </span>
                      </td>
                      <td tabindex="0" class="text-center">
                        <b-button v-if="item.formulario_habilitacao" size="sm"
                          @click="$abrirDocumento('formulario_historico_adesoes', item.id)">
                          <em class="fa fa-download"></em>
                        </b-button>
                        <span v-else>
                          N/A
                        </span>
                      </td>
                      <td tabindex="0" class="text-center">
                        <b-button v-if="item.situacao === 'RE' || item.situacao === 'DV'" size="sm"
                          @click="openModalDetalhes(item)">
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
                      <td tabindex="0" colspan="6">Nenhum histórico cadastrado</td>
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
            <p><b>Justificativa:</b> {{ detalhes.justificativa }}</p>
          </b-col>
        </b-row>
        <b-row v-if="detalhes.anexo">
          <b-col sm="12">
            <p>
              <b>Anexo:</b>
              <b-button @click="$abrirDocumento('anexo_historico_adesoes', detalhes.id)">
                <em class="fa fa-download"></em>
              </b-button>
            </p>
          </b-col>
        </b-row>
      </b-modal>

      <b-modal v-model="changeInstitute.modal" :hide-header-close="true" :busy="changeInstitute.loading"
        title="Atualizar Instituição" ok-title="Alterar" cancel-title="Voltar" no-close-on-backdrop no-close-on-esc
        centered @ok="handleChangeInstituteOk" @cancel="closeChangeModal">
        <b-container fluid>
          <b-row>
            <b-col cols="12">
              <b-form-group tabindex="0" label="Instituição: *">
                <b-form-select v-model="changeInstitute.form.institute_id" placeholder="Selecione"
                  :disabled="changeInstitute.loading">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in instituicoes" :key="item.id" :value="item.id">{{ item.razao_social }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
        </b-container>
      </b-modal>
    </b-overlay>
  </span>
</template>

<script>
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage'
import { tipoAdesao } from '@/constants/adesao'

export default {
  components: {
    Pagination,
    PaginationPage,
    Multiselect,
    Datepicker
    /* formatDateToBR: formatDateToBR */
  },
  created () {
    if (!this.can('adesao.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getInstituicoes')

    let filtros = this.$store.getters.getPageFilter('Adesoes')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'adesao',
      overlay: false,
      modalDetalhes: false,
      detalhes: null,
      situacoes: [
        { value: 'PE', text: 'Pendente' },
        { value: 'AP', text: 'Aprovado' },
        { value: 'DV', text: 'Devolvido' },
        { value: 'RE', text: 'Recusado' }
      ],
      filtros: {
        filter: '',
        id: null,
        cnpj: null,
        numero_cronograma: null,
        situacao: null,
        tipo: null,
        data_cadastro_inicio: null,
        data_cadastro_fim: null,
        order: null,
        orderBy: null
      },
      changeInstitute: {
        modal: false,
        loading: false,
        form: {
          institute_id: null,
          id: null
        }
      },
      modalHistorico: false,
      historico: [],
      ptBR: ptBR
    }
  },
  computed: {
    adesoes () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    },
    tipoAdesao () {
      return tipoAdesao
    },
    instituicoes () {
      return this.$store.state.instituicao.lista
    },
    contextoAtual () {
      let contexto = SessionStorage.get('contextoAtual')

      try {
        contexto = JSON.parse(contexto)
      } catch (e) {
        console.error('Failed to parse contextoAtual:', e)
        contexto = []
      }

      return contexto
    }
  },
  methods: {
    visualizarHistorico (id) {
      this.modalHistorico = true
      this.$http.get('api/adesao/' + id + '/historico').then(response => {
        this.historico = response.body.data
      })
    },
    modalChangeInstitute (obj) {
      this.changeInstitute.form.id = obj.id
      this.changeInstitute.form.institute_id = obj.instituicao_id
      this.changeInstitute.modal = true
    },
    closeChangeModal () {
      this.changeInstitute.form.id = null
      this.changeInstitute.form.institute_id = null
      this.changeInstitute.loading = false
      this.changeInstitute.modal = false
    },
    handleChangeInstituteOk (bvModalEvt) {
      bvModalEvt.preventDefault()

      if (!this.changeInstitute.form.institute_id) {
        this.erro('Seleciona a instituição!')
        return false
      }

      this.$bvModal.msgBoxConfirm(`Confirma a alteração da instituição?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.changeInstitute.loading = true

          this.$http.put(`api/adesao-mudar-instituicao/${this.changeInstitute.form.id}/${this.changeInstitute.form.institute_id}`).then((result) => {
            if (result.body.success) {
              this.sucesso(result.body.msg)
              this.closeChangeModal()
              this.pesquisar()
            } else {
              this.erro(result.body.msg)
              this.changeInstitute.loading = false
            }
          }).catch((error) => {
            console.log('RESULT', error)

            this.erro(error.msg)
            this.changeInstitute.loading = false
          })
        }
      })
    },
    openModalDetalhes (item) {
      this.detalhes = item
      this.modalDetalhes = true
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Adesoes', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    avaliar (id) {
      this.$router.push('/adesao/' + id + '/avaliar')
    },
    avaliarRecursoHabilitacao (id) {
      this.$router.push('/adesao-recurso-habilitacao/' + id)
    },
    avaliarRecursoClassificacao (id) {
      this.$router.push('/adesao-recurso-classificacao/' + id)
    },
    avaliarConvocacao (id) {
      this.$router.push('/adesao-convocacao/' + id)
    },
    navigate (route) {
      this.$router.push(route)
    },
    exportarExcel () {
      this.$store.state.loader.loader = true

      this.$http.post('api/adesao/export/xls', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'adesoes.xlsx'
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

      this.$http.post('api/adesao/export/pdf', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'adesoes.pdf'
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
      this.filtros.id = null
      this.filtros.cnpj = null
      this.filtros.numero_cronograma = null
      this.filtros.situacao = null
      this.filtros.tipo = null
      this.filtros.data_cadastro_inicio = null
      this.filtros.data_cadastro_fim = null
      this.filtros.situacao = null
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
