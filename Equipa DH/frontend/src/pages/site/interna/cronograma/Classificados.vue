<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="12">
              <h3 tabindex="0"><strong>Classificação</strong></h3>
            </b-col>
          </b-row>
        </b-card-header>

        <br>
        <b-row>
          <b-col sm="12">
            <b-container fluid>
              <span>
                <strong>Nº do Cronograma:</strong>&nbsp;{{ cronograma ? cronograma.numero : '' }} <strong>Nome da
                  Política Pública:</strong>&nbsp;{{ cronograma ? cronograma.programa.nome : '' }}
                <br><br>

                <b-row>
                  <b-col md="12" sm="12">
                    <b-card no-body>
                      <b-card-header>
                        <h3 class="info-name"><strong>Esfera Estadual/Distrital</strong></h3>
                        <b-button class="collapse-button" ref="collapse" v-b-toggle="'collapse-estadual'">
                          <span class="when-opened">
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                          </span>
                          <span class="when-closed">
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                          </span>
                        </b-button>
                        <b-button v-if="can('cronograma.update') || can('cronograma.store')" variant="primary"
                          @click="classificar('estadual')" class="collapse-button mr-2">
                          <em class="fa fa-check"></em>&nbsp;&nbsp;<span>Classificar</span>
                        </b-button>
                        <b-button v-if="can('cronograma.show')" variant="primary" @click="exportarEstadual()"
                          class="collapse-button mr-2">
                          <em class="fa fa-file-pdf"></em>&nbsp;&nbsp;<span>Exportar PDF</span>
                        </b-button>
                      </b-card-header>
                      <b-collapse id="collapse-estadual" class="p-2">
                        <table class="table table-bordered table-striped table-sm">
                          <thead>
                            <tr>
                              <th tabindex="0" scope="col">Instituição</th>
                              <th tabindex="0" scope="col">UF</th>
                              <th tabindex="0" scope="col">Município</th>
                              <th tabindex="0" scope="col">Data da Solicitação</th>
                              <th tabindex="0" scope="col">Classificação</th>
                            </tr>
                          </thead>
                          <tbody v-if="classificadoEstadual.length > 0">
                            <tr class="action" v-for="adesao in classificadoEstadual" :key="'estadual-' + adesao.id">
                              <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                              <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                              <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                              <td tabindex="0">{{ adesao.created_at }}</td>
                              <td tabindex="0">{{ adesao.classificacao ? adesao.classificacao + 'º' : '-' }}</td>
                            </tr>
                          </tbody>
                          <tbody v-else>
                            <tr class="action">
                              <td tabindex="0" colspan="5">Nenhuma adesão classificada</td>
                            </tr>
                          </tbody>
                        </table>
                        <pagination-page v-show="classificadoEstadual && classificadoEstadual.length > 0"
                          :resource="resource" :filtros="filtersEstadual" :totalPorPagina="20"
                          :list="'classificadoEstadual'" :origin="'ClassificadosEstaduais'">
                        </pagination-page>
                      </b-collapse>
                    </b-card>
                  </b-col>
                </b-row>

                <b-row class="mt-3">
                  <b-col md="12" sm="12">
                    <b-card no-body>
                      <b-card-header>
                        <h3 class="info-name"><strong>Esfera Municipal</strong></h3>
                        <b-button class="collapse-button" ref="collapse" v-b-toggle="'collapse-municipal'">
                          <span class="when-opened">
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                          </span>
                          <span class="when-closed">
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                          </span>
                        </b-button>
                        <b-button v-if="can('cronograma.update') || can('cronograma.store')" variant="primary"
                          @click="classificar('municipal')" class="collapse-button mr-2">
                          <em class="fa fa-check"></em>&nbsp;&nbsp;<span>Classificar</span>
                        </b-button>
                        <b-button v-if="can('cronograma.show')" variant="primary" @click="exportarMunicipal()"
                          class="collapse-button mr-2">
                          <em class="fa fa-file-pdf"></em>&nbsp;&nbsp;<span>Exportar PDF</span>
                        </b-button>
                      </b-card-header>
                      <b-collapse id="collapse-municipal" class="p-2">
                        <table class="table table-bordered table-striped table-sm">
                          <thead>
                            <tr>
                              <th tabindex="0" scope="col">Instituição</th>
                              <th tabindex="0" scope="col">UF</th>
                              <th tabindex="0" scope="col">Município</th>
                              <th tabindex="0" scope="col">Data da Solicitação</th>
                              <th tabindex="0" scope="col">Classificação</th>
                            </tr>
                          </thead>
                          <tbody v-if="classificadoMunicipal.length > 0">
                            <tr class="action" v-for="adesao in classificadoMunicipal" :key="'municipal-' + adesao.id">
                              <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                              <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                              <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                              <td tabindex="0">{{ adesao.created_at }}</td>
                              <td tabindex="0">{{ adesao.classificacao ? adesao.classificacao + 'º' : '-' }}</td>
                            </tr>
                          </tbody>
                          <tbody v-else>
                            <tr class="action">
                              <td tabindex="0" colspan="5">Nenhuma adesão classificada</td>
                            </tr>
                          </tbody>
                        </table>
                        <pagination-page v-show="classificadoMunicipal && classificadoMunicipal.length > 0"
                          :resource="resource" :filtros="filtersMunicipal" :totalPorPagina="20"
                          :list="'classificadoMunicipal'" :origin="'ClassificadosMunicipais'">
                        </pagination-page>
                      </b-collapse>
                    </b-card>
                  </b-col>
                </b-row>

              </span>
            </b-container>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="2" class="text-left">
            <b-container fluid>
              <b-button @click="navigate('/cronograma')" variant="secondary">
                <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-container>
          </b-col>
        </b-row>
        <br>

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
    if (!this.can('cronograma.show')) {
      this.$router.push('/home')
    }

    // this.loadClassificados(this.$route.params.id)filtersEstadual
    this.laodCronograma(this.$route.params.id)
  },
  data () {
    return {
      overlay: false,
      resource: 'adesao/' + this.$route.params.id + '/classificado',
      filtersEstadual: { estadual: true },
      filtersMunicipal: { municipal: true },
      cronograma: null,
      totalPorPagina: 20,
      adesoes: [],
      ptBR: ptBR
    }
  },
  computed: {
    classificadoEstadual () {
      let result = this.$store.state.pagination.lists.classificadoEstadual
      return result !== undefined ? result.data : []
    },
    classificadoMunicipal () {
      let result = this.$store.state.pagination.lists.classificadoMunicipal
      return result !== undefined ? result.data : []
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      eventType.$emit('app', filtros)
    },
    laodCronograma () {
      this.$http.get('api/cronograma/' + this.$route.params.id).then(response => {
        this.cronograma = response.body.data
      }).catch(erro => {
        this.erro(erro.body.msg)
      })
    },
    exportarEstadual () {
      this.$http.post('api/adesao/' + this.$route.params.id + '/classificado/estadual/pdf', this.filtersEstadual, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'classificados_estadual.pdf'
          link.click()
        }
      })
    },
    exportarMunicipal () {
      this.$http.post('api/adesao/' + this.$route.params.id + '/classificado/municipal/pdf', this.filtersMunicipal, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'classificados_municipal.pdf'

          link.click()
        }
      })
    },
    // loadClassificados (cronograma) {
    //   this.overlay = true
    //   this.$http.get('api/adesao/' + cronograma + '/classificado').then(response => {
    //     this.adesoes = response.body.data
    //     this.overlay = false
    //   }).catch(erro => {
    //     this.erro(erro.body.msg)
    //     this.overlay = false
    //   })
    // },
    pesquisar (filters, list) {
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: filters, list: list })
      this.setarFiltros(this.filtros)
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
    classificar (tipo) {
      this.$bvModal.msgBoxConfirm(`Deseja acionar a classificação?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.post('api/adesao/' + this.cronograma.id + '/classificado', { tipo: tipo }).then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)

              if (tipo === 'estadual') {
                this.pesquisar(this.filtersEstadual, 'classificadoEstadual')
              } else {
                this.pesquisar(this.filtersMunicipal, 'classificadoMunicipal')
              }
            }
          }).catch(erro => {
            if (!erro.body.success) {
              this.erro(erro.body.msg)
            }
          })
        }
      })
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir o registro?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/cronograma/' + id).then(response => {
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
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
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

<style>
.info-name {
  display: inline-block;
  width: fit-content;
  margin-left: 0;
  margin-right: auto;
}

.collapsed>.when-closed,
:not(.collapsed)>.when-opened {
  display: none;
}

.collapse-button {
  display: block;
  float: right;
}
</style>
