<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
    <b-card no-body>
      <b-card-header>
        <b-row>
          <b-col md="9">
            <h3 tabindex="0"><strong>Minhas Adesões</strong></h3>
          </b-col>
        </b-row>
      </b-card-header>
      <b-card-body>
        <b-row>
          <b-col cols="6">
            <b-form-group tabindex="0" label="Política Pública:">
              <b-form-select v-model="filtros.programa_id" class="mb-3">
                <option selected :value="''"> Selecione </option>
                <option v-for="programa in programas" :value="programa.id" :key="programa.id"> {{ programa.nome }} </option>
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Nº da Solicitação:">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-model.trim="filtros.id"/>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Nº do Cronograma:">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" v-model.trim="filtros.numero_cronograma"/>
              </b-input-group>
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
          <b-col md="3" sm="12">
            <b-form-group tabindex="0" label="Situação:">
              <b-form-select v-model="filtros.situacao" placeholder="" class="mb-3">
                <option selected :value="null">Selecione</option>
                <option v-for="item in situacoes" :key="item.id" :value="item.value"> {{item.text}}</option>
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
              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th tabindex="0" scope="col">Nº da Solicitação</th>
                    <th tabindex="0" scope="col">Política Pública</th>
                    <th tabindex="0" scope="col">Cronograma</th>
                    <th tabindex="0" scope="col">Data de início</th>
                    <th tabindex="0" scope="col">Data de encerramento</th>
                    <th tabindex="0" scope="col">Situação da Solicitação</th>
                    <th tabindex="0" scope="col">Tipo de solicitação</th>
                    <th tabindex="0" scope="col">Fase</th>
                    <th tabindex="0" scope="col">Ações</th>
                  </tr>
                </thead>
                <tbody v-if="adesoes.data !== undefined">
                  <tr class="action" v-for="adesao in adesoes.data" :key="adesao.id">
                    <td tabindex="0">{{ adesao.id }}</td>
                    <td tabindex="0">{{ adesao.cronograma.programa.nome }}</td>
                    <td tabindex="0">{{ adesao.cronograma.numero }}</td>
                    <td tabindex="0">{{ adesao.cronograma.data_publicacao_inicio | formatDateToBR }}</td>
                    <td tabindex="0">{{ adesao.cronograma.data_divulgacao_lista | formatDateToBR }}</td>
                    <td tabindex="0">
                      <b-badge v-if="adesao.situacao == 'PE'" variant="secondary">Pendente</b-badge>
                      <b-badge v-if="adesao.situacao == 'DV'" variant="warning">Devolvido</b-badge>
                      <b-badge v-if="adesao.situacao == 'RE'" variant="danger">Recusado</b-badge>
                      <b-badge v-if="adesao.situacao == 'AP'" variant="success">Aprovado</b-badge>
                      <b-badge v-if="adesao.situacao == 'CV'" variant="warning">Convocado</b-badge>
                    </td>
                    <td tabindex="0">
                      <span v-if="adesao.tipo === 'AD'">Adesão</span>
                      <span v-if="adesao.tipo === 'RA'">Recurso de Adesão</span>
                      <span v-if="adesao.tipo === 'RC'">Recurso de Classificação</span>
                      <span v-if="adesao.tipo === 'CV'">Convocação</span>
                    </td>
                    <td tabindex="0">
                      <b-badge v-if="adesao.cronograma.fase === 'NI'" variant="secondary">Não Iniciado</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'PU'" variant="info">Publicado</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'AD'" variant="info">Adesão e Habilitação</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'AF'" variant="info">Validação das Adesões</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'DA'" variant="info">Divulgação da Adesão</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'RA'" variant="info">Recurso Adesão</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'RF'" variant="info">Validação dos Recursos</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'DH'" variant="info">Divulgação de Habilitados</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'RH'" variant="info">Recurso de Habilitados</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'HF'" variant="info">Validação de Recursos de Habilitados</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'DL'" variant="info">Divulgação da Lista</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'EN'" variant="info">Encerrado</b-badge>
                      <b-badge v-if="adesao.cronograma.fase === 'CO'" variant="info">Convocação</b-badge>
                    </td>
                    <td style="width: 1%; white-space: nowrap;">
                      <button v-if="can('adesao.update') && (adesao.tipo === 'AD' && adesao.situacao === 'DV')" class="btn btn-sm btn-secondary action" title="Corrigir" @click="editar(adesao.id)">
                        <em class="fa fa-pencil"></em>
                      </button>
                      <button v-if="can('adesao.historico')" class="btn btn-sm btn-secondary action" title="Visualizar Historico" @click="visualizarHistorico(adesao.id)">
                        <em class="fa fa-list"></em>
                      </button>
                      <button v-if="can('adesao.historico')" class="btn btn-sm btn-secondary action" title="Acompanhar Cronograma" @click="acompanharCronograma(adesao.cronograma_id, adesao.id)">
                        <em class="fa fa-calendar"></em>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr class="action text-center">
                    <td tabindex="0" colspan="9">Nenhuma solicitação cadastrada</td>
                  </tr>
                </tbody>
              </table>
              <b-row>
                <b-col cols="4">
                  <pagination-page tabindex="0" v-show="adesoes.data !== undefined" :totalPorPagina="totalPorPagina" :resource="resource" :filtros="filtros" :origin="'MinhasAdesoes'"></pagination-page>
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
              <label style="font-size: 13px;" class="form-text text-muted">O identificador N/A em Unidades/Instituições ou Perfis aparece em casos feitos antes da alteração para exibir os mesmos.</label>
              <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
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
                      <b-button v-if="item.arquivo" size="sm" @click="$abrirDocumento('documento_historico_adesoes', item.id)">
                        <em class="fa fa-download"></em>
                      </b-button>
                      <span v-else>
                        N/A
                      </span>
                    </td>
                    <td tabindex="0" class="text-center">
                      <b-button v-if="item.formulario_habilitacao" size="sm" @click="$abrirDocumento('formulario_historico_adesoes', item.id)">
                        <em class="fa fa-download"></em>
                      </b-button>
                      <span v-else>
                        N/A
                      </span>
                    </td>
                    <td tabindex="0" class="text-center">
                      <b-button v-if="item.situacao === 'RE' || item.situacao === 'DV'" size="sm" @click="openModalDetalhes(item)">
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
    </b-overlay>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import {eventType} from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'
import Datepicker from 'vuejs-datepicker';
import {ptBR} from 'vuejs-datepicker/dist/locale'
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
    if (!this.can('adesao.minhasSolicitacoes')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getProgramas')

    let filtros = this.$store.getters.getPageFilter('MinhasAdesoes')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'minhas-adesoes',
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
        programa_id: null,
        numero_cronograma: null,
        situacao: null,
        data_cadastro_inicio: null,
        data_cadastro_fim: null
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
    programas () {
      return this.$store.state.programa.lista
    },
  },
  methods: {
    visualizarHistorico (id) {
      this.modalHistorico = true
      this.$http.get('api/adesao/' + id + '/historico').then(response => {
        this.historico = response.body.data
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
      this.$store.dispatch('updateFilter', {page: 'MinhasAdesoes', filter: this.filtros})
    },
    pesquisar () {
      this.setarFiltros(this.filtros)
      this.$store.dispatch('getRegistrosSearch', {resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros})
    },
    editar (id) {
      this.$router.push('/adesao/' + id + '/editar')
    },
    acompanharCronograma (cronograma, id) {
      this.$router.push('/adesao-acompanhar/' + id + '/' + cronograma)
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.id = null
      this.filtros.programa_id = null
      this.filtros.numero_cronograma = null
      this.filtros.situacao = null
      this.filtros.data_cadastro_inicio = null
      this.filtros.data_cadastro_fim = null
      this.filtros.situacao = null
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
        background:red;
    }
</style>
