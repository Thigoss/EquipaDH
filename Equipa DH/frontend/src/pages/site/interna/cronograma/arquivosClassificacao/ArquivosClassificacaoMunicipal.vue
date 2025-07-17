<template>
  <b-card no-body>
    <b-card-header>
      <b-row>
        <b-col md="9">
          <h3 tabindex="0"><strong>{{ $route.name }}</strong></h3>
        </b-col>
        <b-col md="3">
          <b-button block variant="primary" v-if="can('arquivoClassificacao.index')" @click="cadastrar()">
            <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
          </b-button>
        </b-col>
      </b-row>
    </b-card-header>
    <b-card-header v-if="cronograma">
      <b-overlay :show="cronograma === null" rounded="sm">
        <b-row>
          <b-col md="12">
            <h4 class="inline mr-3" tabindex="0"><b>Política Pública: </b> {{ cronograma.programa.nome }}</h4>
            <h4 class="inline" tabindex="0"><b>Cronograma: </b> {{ cronograma.numero }}</h4>
          </b-col>
        </b-row>
      </b-overlay>
    </b-card-header>
    <b-card-body>
      <b-row>
        <b-col md="8" sm="12">
          <b-form-group tabindex="0" label="Nome:">
            <b-form-input type="text" v-model="filtros.filter"></b-form-input>
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
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th tabindex="0" scope="col">Data</th>
                  <th tabindex="0" scope="col">Nome</th>
                  <th tabindex="0" scope="col">Status</th>
                  <th tabindex="0" scope="col">Ações</th>
                </tr>
              </thead>
              <tbody v-if="arquivos.data !== undefined">
                <tr class="action" v-for="arquivo in arquivos.data" :key="arquivo.id">
                  <td tabindex="0">{{ arquivo.created_at | formatDateTimeToBR }}</td>
                  <td tabindex="0">{{ arquivo.nome }}</td>
                  <td tabindex="0">
                    <b-badge v-if="arquivo.ativo" variant="success">Ativo</b-badge>
                    <b-badge v-else variant="warning">Inativo</b-badge>
                  </td>
                  <td style="width: 1%; white-space: nowrap;">
                    <button class="btn btn-sm btn-primary action" title="Download"
                      @click="$abrirDocumento('arquivo_classificacao_municipal', arquivo.id)">
                      <em class="fa fa-download"></em>
                    </button>
                    <button v-if="arquivo.ativo && can('arquivoClassificacao.inativar')"
                      class="btn btn-sm btn-secondary action" title="Desativar" @click="alterarSituacao(arquivo)">
                      <em class="fa fa-toggle-off"></em>
                    </button>
                    <button v-if="!arquivo.ativo && can('arquivoClassificacao.ativar')"
                      class="btn btn-sm btn-secondary action" title="Ativar" @click="alterarSituacao(arquivo)">
                      <em class="fa fa-toggle-on"></em>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr class="action text-center">
                  <td tabindex="0" colspan="8">Nenhum arquivo de classificação cadastrado.</td>
                </tr>
              </tbody>
            </table>
            <b-row>
              <b-col cols="4">
                <pagination-page tabindex="0" v-show="arquivos.data !== undefined" :totalPorPagina="totalPorPagina"
                  :resource="resource" :filtros="filtros"
                  :origin="'ArquivosClassificacoesMunicipais'"></pagination-page>
              </b-col>
            </b-row>
          </span>
        </b-container>
      </b-col>
    </b-row>
    <b-card-footer>
      <b-row>
        <b-col md="3" sm="12">
          <b-button block variant="secondary" @click="$router.push('/cronograma')">
            <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
          </b-button>
        </b-col>
      </b-row>
    </b-card-footer>
  </b-card>
</template>

<script>

import Pagination from '@/components/shared/Pagination'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage'
import { ativoBoolean } from '@/constants/defaults'

export default {
  name: 'ArquivosClassificacaomunicipal',
  components: {
    Pagination,
    PaginationPage,
    Datepicker
  },
  created () {
    if (!this.can('arquivoClassificacao.index')) {
      this.$router.push('/home')
    }

    this.loadCronograma(this.$route.params.cronograma)

    let filtros = this.$store.getters.getPageFilter('ArquivosClassificacaoMunicipal')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: `arquivos-classificacao/municipal/${this.$route.params.cronograma}`,
      cronograma: null,
      filtros: {
        filter: ''
      },
      ptBR: ptBR
    }
  },
  computed: {
    arquivos () {
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
    sitacoes () {
      return [...ativoBoolean, { text: 'Todas', value: null }]
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    loadCronograma (id) {
      this.$http.get('api/cronograma/' + id).then(response => {
        this.cronograma = response.data.data
      })
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'ArquivosClassificacaoMunicipal', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push(`/arquivo-classificacao-municipal/${this.$route.params.cronograma}/cadastrar`)
    },
    editar (id) {
      this.$router.push(`/arquivo-classificacao-municipal/${this.$route.params.cronograma}/editar/${id}`)
    },
    visualizar (id) {
      this.$router.push(`/arquivo-classificacao-municipal/${this.$route.params.cronograma}/visualizar/${id}`)
    },
    alterarSituacao (arquivo) {
      this.$bvModal.msgBoxConfirm(`Tem certeza que deseja ${(arquivo.ativo ? 'inativar' : 'ativar')} o arquivo ${arquivo.nome}? ${(arquivo.ativo ? '' : 'Apenas um arquivo pode estar ativo!')}`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.put(`api/arquivo-classificacao-status/municipal/${arquivo.id}`).then(response => {
            this.pesquisar()
          }).catch(error => {
            console.log(error)
          })
        }
      }).catch(error => {
        console.log(error)
      })
    },
    limpar () {
      this.filtros.filter = ''
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

.inline {
  display: inline !important;
}
</style>
