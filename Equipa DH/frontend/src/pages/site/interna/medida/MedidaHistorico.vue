<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="12">
              <h3 tabindex="0"><strong>Histórico de Atualizações</strong></h3>
            </b-col>
          </b-row>
        </b-card-header>
        <br />
        <b-row>
          <b-col sm="8">
            <b-container fluid>
              <span>
                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Quem Fez</th>
                      <th tabindex="0" scope="col">O que Fez</th>
                      <th tabindex="0" scope="col">Quando Fez</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="historicos.data !== undefined">
                    <tr class="action" v-for="historico in historicos.data" :key="historico.id">
                      <td tabindex="0">{{ historico.usuario_nome }}</td>
                      <td tabindex="0">{{ historico.acao_realizada }}</td>
                      <td tabindex="0">{{ formataData(historico.created_at) }}</td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('medida.buscarHistorico')" class="btn btn-sm btn-secondary action"
                          title="Visualizar" @click="visualizarHistorico(historico.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('medida.buscarHistorico')" class="btn btn-sm btn-secondary action"
                          title="Exportar" @click="exportarPdf(historico)">
                          <em class="fa fa-file-pdf"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="9">Nenhum Histórico de Conselho De Direito Encontrado.</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col md="9" sm="12">
                    <pagination tabindex="0" v-show="historicos.data !== undefined" :totalPorPagina="totalPorPagina"
                      :resource="resource" :filtros="filtros"></pagination>
                  </b-col>
                </b-row>
              </span>
              <br />
            </b-container>
          </b-col>
        </b-row>
      </b-card>
      <b-card-footer class="p-12">
        <b-row>
          <b-col md="3">
            <b-button block variant="secondary" @click="voltar">
              <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
            </b-button>
          </b-col>
        </b-row>
      </b-card-footer>
    </b-overlay>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import moment from 'moment'
import Datepicker from 'vuejs-datepicker';

miniToastr.init()

export default {
  components: {
    Pagination,
    Multiselect,
    Datepicker
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'medida/historico',
      overlay: false,
      filtros: {
        medida_id: null
      }
    }
  },
  watch: {},
  computed: {
    historicos () {
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
  created () {
    if (!this.can('medida.historico')) {
      this.$router.push('/home')
    }

    if (this.$route.params.id !== undefined) {
      this.filtros.medida_id = this.$route.params.id
      this.pesquisar()
    }

    let filtros = this.$store.getters.getPageFilter('MedidaHistorico')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  methods: {
    voltar () {
      return this.$router.push('/medidas')
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'MedidaHistorico', filter: this.filtros })
    },
    visualizarHistorico (id) {
      this.$router.push('/medida/' + id + '/historico/detalhamento')
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    navigate (route) {
      this.$router.push(route)
    },
    formataData (data) {
      return data ? moment(String(data)).format('DD/MM/YYYY HH:mm:ss') : null
    },
    exportarPdf (medida) {
      this.$store.state.loader.loader = true

      let params = {
        medida_id: medida.medida_id,
        medida_historico_id: medida.id
      }
      Vue.http.post('api/medida/historico/export/pdf', params, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          var file = 'medida_historico.pdf'
          this.downloadFile(response.data, file)
        }

        this.$store.state.loader.loader = false
      }).catch(err => {
        console.log(err)
        this.$store.state.loader.loader = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },
    downloadFile (response, filename) {
      var newBlob = new Blob([response], { type: 'application/pdf' })

      if (window.navigator && window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(newBlob)
        return
      }

      const data = window.URL.createObjectURL(newBlob)
      var link = document.createElement('a')
      link.href = data
      link.download = filename
      link.click()
      setTimeout(function () {
        window.URL.revokeObjectURL(data)
      }, 100)
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
