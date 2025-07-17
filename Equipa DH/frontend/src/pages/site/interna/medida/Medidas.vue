<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Medidas de Proteção</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" v-if="can('medida.store')" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="12" sm="12">
              <b-form-group tabindex="0" label="Nome:">
                <b-input-group class="mb-3">
                  <b-form-input type="text" class="form-control" placeholder="" autocomplete="nome"
                    v-model="filtros.nome" />
                </b-input-group>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Grupo de Medida : *">
                <b-form-select v-model="filtros.grupo_medida_id" placeholder="Selecione" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in gruposMedidas" :key="item.id" :value="item.id">{{ item.nome }}</option>
                </b-form-select>
                <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
              </b-form-group>
            </b-col>
            <b-col cols="4">
              <b-form-group tabindex="0" label="Áreas Temáticas: *">
                <multiselect v-model="filtros.areasTematicas" :options="areasTematicas" label="descricao" track-by="id"
                  placeholder="Selecione" selectLabel="" deselectLabel="" selectedLabel="" :tagglabe="true"
                  :multiple="true">
                  <span slot="maxElements"></span>
                  <span slot="noResult">Nenhum encontrado</span>
                  <span slot="noOptions">Lista vazia</span>
                </multiselect>
              </b-form-group>
            </b-col>
            <b-col cols="4">
              <b-form-group tabindex="0" label="Necessita de um órgão ou sistema de garantia de direitos?">
                <b-form-select v-model="filtros.necessita_orgao" placeholder="Selecione" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in options" :key="item.value" :value="item.value">{{ item.text }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="4" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.ativo" placeholder="Selecione" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="item in situacoes" :key="item.value" :value="item.value">{{ item.text }}</option>
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
                      <th tabindex="0" scope="col">Nome</th>
                      <th tabindex="0" scope="col">Descrição</th>
                      <th tabindex="0" scope="col">Grupo de Medida</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="medidas.data !== undefined">
                    <tr class="action" v-for="medida in medidas.data" :key="medida.id">
                      <td tabindex="0">{{ medida.nome }}</td>
                      <td tabindex="0">{{ medida.descricao }}</td>
                      <td tabindex="0">{{ medida.grupo_medida.nome }}</td>
                      <td tabindex="0">{{ medida.ativo ? 'Ativo' : 'Inativo' }}</td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('medida.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(medida.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="can('medida.show')" class="btn btn-sm btn-secondary action" title="Visualizar"
                          @click="visualizar(medida.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="medida.ativo && can('medida.inativar')" class="btn btn-sm btn-secondary action"
                          title="Desativar" @click="alterarSituacao(medida.id, false)">
                          <em class="fa fa-toggle-off"></em>
                        </button>
                        <button v-if="!medida.ativo && can('medida.ativar')" class="btn btn-sm btn-secondary action"
                          title="Ativar" @click="alterarSituacao(medida.id, true)">
                          <em class="fa fa-toggle-on"></em>
                        </button>
                        <button v-if="can('medida.historico')" class="btn btn-sm btn-secondary action"
                          title="Visualizar Historico" @click="visualizarHistorico(medida.id)">
                          <em class="fa fa-list"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="5">Nenhuma medida</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="medidas.data !== undefined" :totalPorPagina="totalPorPagina"
                      :resource="resource" :filtros="filtros" :origin="'Medidas'"></pagination-page>
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
    /* formatDateToBR: formatDateToBR */
  },
  created () {
    if (!this.can('medida.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getGruposMedidas')
    this.$store.dispatch('getAreaTematicas')

    let filtros = this.$store.getters.getPageFilter('Medidas')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'medida',
      overlay: false,
      situacoes: [
        { value: '1', text: 'Ativo' },
        { value: '0', text: 'Inativo' }
      ],
      options: [
        { text: 'Não', value: false },
        { text: 'Sim', value: true }
      ],
      filtros: {
        filter: '',
        nome: null,
        descricao: null,
        grupo_medida_id: null,
        necessita_orgao: null,
        ativo: null
      },
      ptBR: ptBR
    }
  },
  computed: {
    medidas () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    },
    gruposMedidas () {
      return this.$store.state.grupoMedida.lista
    },
    areasTematicas () {
      return this.$store.state.areaTematica.lista
    },
  },
  methods: {
    visualizarHistorico (id) {
      this.$router.push('/medida/' + id + '/historico')
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Medidas', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
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
          let url = situacao ? 'api/medida/' + id + '/ativo' : 'api/medida/' + id + '/inativo'
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
    cadastrar () {
      this.$router.push('/medida/cadastrar')
    },
    editar (id) {
      this.$router.push('/medida/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/medida/' + id + '/visualizar')
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.nome = ''
      this.filtros.descricao = ''
      this.filtros.grupo_medida_id = ''
      this.filtros.necessita_orgao = ''
      this.filtros.ativo = null
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
