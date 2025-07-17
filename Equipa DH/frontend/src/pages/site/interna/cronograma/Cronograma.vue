<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Cronograma</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" v-if="can('cronograma.store')" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col cols="8">
              <b-form-group tabindex="0" label="Política Pública: *">
                <b-form-select v-model="filtros.programa_id" class="mb-3">
                  <option selected :value="''"> Selecione </option>
                  <option v-for="programa in programas" :value="programa.id" :key="programa.id"> {{ programa.nome }}
                  </option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Nº do Cronograma:">
                <b-form-input type="text" v-model="filtros.numero"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Fase:">
                <b-form-select v-model="filtros.fase" placeholder="" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in fases" :key="item.id" :value="item.value"> {{ item.text }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Status:">
                <b-form-select v-model="filtros.situacao" placeholder="" class="mb-3">
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
                      <th tabindex="0" scope="col">Nº do Cronograma</th>
                      <th tabindex="0" scope="col">Política Pública</th>
                      <th tabindex="0" scope="col">Data de Início</th>
                      <th tabindex="0" scope="col">Data de Encerramento</th>
                      <th tabindex="0" scope="col">Fase</th>
                      <th tabindex="0" scope="col">Publicação</th>
                      <th tabindex="0" scope="col">Status</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="cronogramas.data !== undefined">
                    <tr class="action" v-for="cronograma in cronogramas.data" :key="cronograma.id">
                      <td tabindex="0">{{ cronograma.numero }}</td>
                      <td tabindex="0">{{ cronograma.programa.nome }}</td>
                      <td tabindex="0">{{ cronograma.data_publicacao_inicio | formatDateToBR }}</td>
                      <td tabindex="0">{{ cronograma.data_divulgacao_lista | formatDateToBR }}</td>
                      <td tabindex="0">
                        <b-badge v-if="cronograma.fase === 'NI'" variant="secondary">Não Iniciado</b-badge>
                        <b-badge v-if="cronograma.fase === 'PU'" variant="info">Publicado</b-badge>
                        <b-badge v-if="cronograma.fase === 'AD'" variant="info">Adesão e Habilitação</b-badge>
                        <b-badge v-if="cronograma.fase === 'AF'" variant="info">Validação das Adesões</b-badge>
                        <b-badge v-if="cronograma.fase === 'DA'" variant="info">Divulgação da Adesão</b-badge>
                        <b-badge v-if="cronograma.fase === 'RA'" variant="info">Recurso Adesão</b-badge>
                        <b-badge v-if="cronograma.fase === 'RF'" variant="info">Validação dos Recursos</b-badge>
                        <b-badge v-if="cronograma.fase === 'DH'" variant="info">Divulgação de Habilitados</b-badge>
                        <b-badge v-if="cronograma.fase === 'RH'" variant="info">Recurso de Habilitados</b-badge>
                        <b-badge v-if="cronograma.fase === 'HF'" variant="info">Validação de Recursos de
                          Habilitados</b-badge>
                        <b-badge v-if="cronograma.fase === 'DL'" variant="info">Divulgação da Lista</b-badge>
                        <b-badge v-if="cronograma.fase === 'EN'" variant="info">Encerrado</b-badge>
                        <b-badge v-if="cronograma.fase === 'CO'" variant="info">Convocação</b-badge>
                      </td>
                      <td tabindex="0">
                        <b-badge v-if="cronograma.publicacao == 'PU'" variant="success">Publicado</b-badge>
                        <b-badge v-if="cronograma.publicacao == 'NP'" variant="danger">Não Publicado</b-badge>
                      </td>
                      <td tabindex="0">
                        <b-badge v-if="cronograma.situacao == 'NI'" variant="secondary">Não Iniciado</b-badge>
                        <b-badge v-if="cronograma.situacao == 'AN'" variant="info">Em Andamento</b-badge>
                        <b-badge v-if="cronograma.situacao == 'FI'" variant="success">Finalizado</b-badge>
                        <b-badge v-if="cronograma.situacao == 'CA'" variant="danger">Cancelado</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('cronograma.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(cronograma.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="can('cronograma.show')" class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizar(cronograma.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('cronograma.destroy')" class="btn btn-sm btn-secondary action" title="Excluir"
                          @click="excluir(cronograma.id)">
                          <em class="fa fa-trash"></em>
                        </button>
                        <button v-if="can('cronograma.show')" class="btn btn-sm btn-secondary action"
                          title="Listar habilitados" @click="listarHabilitados(cronograma.id)">
                          <em class="fa fa-list"></em>
                        </button>
                        <button v-if="can('cronograma.show')" class="btn btn-sm btn-secondary action"
                          title="Classificar habilitados" @click="listarClassificados(cronograma.id)">
                          <em class="fa fa-list"></em>
                        </button>
                        <button v-if="can('cronograma.show')" class="btn btn-sm btn-secondary action"
                          title="Convocar habilitados" @click="listarConvocados(cronograma.id)">
                          <em class="fa fa-list"></em>
                        </button>
                        <button v-if="can('arquivoClassificacao.index')" class="btn btn-sm btn-secondary action"
                          title="Arquivos de Classificação Estadual/Distrital"
                          @click="arquivosEstaduais(cronograma.id)">
                          <em class="fa fa-file-alt"></em>
                        </button>
                        <button v-if="can('arquivoClassificacao.index')" class="btn btn-sm btn-secondary action"
                          title="Arquivos de Classificação Municipal" @click="arquivosMunicipais(cronograma.id)">
                          <em class="fa fa-file"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="8">Nenhum cronograma cadastrado</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="cronogramas.data !== undefined"
                      :totalPorPagina="totalPorPagina" :resource="resource" :filtros="filtros"
                      :origin="'Cronograma'"></pagination-page>
                  </b-col>
                </b-row>
              </span>
            </b-container>
          </b-col>
        </b-row>
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
  },
  created () {
    if (!this.can('cronograma.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getProgramas')

    let filtros = this.$store.getters.getPageFilter('Cronogramas')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'cronograma',
      overlay: false,
      situacoes: [
        { value: 'NI', text: 'Não Iniciado' },
        { value: 'AN', text: 'Em Andamento' },
        { value: 'FI', text: 'Finalizado' }
      ],
      fases: [
        { value: 'NI', text: 'Não Iniciado' },
        { value: 'PU', text: 'Publicado' },
        { value: 'AD', text: 'Adesão e Habilitação' },
        { value: 'AF', text: 'Validação das Adesões' },
        { value: 'DA', text: 'Divulgação da Adesão' },
        { value: 'RA', text: 'Recurso Adesão' },
        { value: 'RF', text: 'Validação dos Recursos' },
        { value: 'DH', text: 'Divulgação de Habilitados' },
        { value: 'RH', text: 'Recurso de Habilitados' },
        { value: 'HF', text: 'Validação de Recursos de Habilitados' },
        { value: 'DL', text: 'Divulgação da Lista' },
        { value: 'EN', text: 'Encerrado' },
        { value: 'CO', text: 'Convocação' }
      ],
      filtros: {
        filter: '',
        programa_id: null,
        numero: null,
        situacao: null,
        fase: null,
        data_inicio: null,
        data_termino: null
      },
      ptBR: ptBR
    }
  },
  computed: {
    cronogramas () {
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
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Cronogramas', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push('/cronograma/cadastrar')
    },
    editar (id) {
      this.$router.push('/cronograma/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/cronograma/' + id + '/visualizar')
    },
    listarHabilitados (id) {
      this.$router.push('/cronograma/' + id + '/habilitados/lista')
    },
    listarClassificados (id) {
      this.$router.push('/cronograma/' + id + '/classificados/lista')
    },
    listarConvocados (id) {
      this.$router.push('/cronograma/' + id + '/convocados/lista')
    },
    arquivosEstaduais (id) {
      this.$router.push(`/arquivos-classificacao-estadual/${id}`)
    },
    arquivosMunicipais (id) {
      this.$router.push(`/arquivos-classificacao-municipal/${id}`)
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir o cronograma?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/cronograma/' + id).then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.pesquisar()
            } else {
              this.erro(response.body.msg)
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
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.programa_id = null
      this.filtros.numero = null
      this.filtros.situacao = null
      this.filtros.fase = null
      this.filtros.data_inicio = null
      this.filtros.data_termino = null
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
