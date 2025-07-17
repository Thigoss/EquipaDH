<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Gerenciar Instituições</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" v-if="can('instituicao.store')" @click="cadastrar()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Cadastrar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Pesquisar por Instituição:">
                <b-form-input type="text" v-model="filtros.filter"></b-form-input>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="CNPJ:">
                <b-form-input type="text" v-mask="'##.###.###/####-##'" v-model="filtros.cnpj"
                  placeholder="##.###.###/####-##"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="UF:">
                <b-form-select v-model="filtros.estado_id" :options="estados" value-field="id" text-field="sigla">
                  <option selected :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
              <b-form-group tabindex="0" label="Município:">
                <b-form-select v-model="filtros.cidade_id" :options="cidades" value-field="id" text-field="cidade">
                  <option selected :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="3" sm="12">
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
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">Estado</th>
                      <th tabindex="0" scope="col">Município</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="instituicoes.data !== undefined">
                    <tr class="action" v-for="instituicao in instituicoes.data" :key="instituicao.id">
                      <td tabindex="0">{{ instituicao.razao_social }}</td>
                      <td tabindex="0">{{ instituicao.estado.sigla }}</td>
                      <td tabindex="0">{{ instituicao.cidade.cidade }}</td>
                      <td tabindex="0">
                        <b-badge v-if="instituicao.ativo" variant="success">Ativo</b-badge>
                        <b-badge v-if="!instituicao.ativo" variant="danger">Inativo</b-badge>
                      </td>
                      <td style="width: 1%; white-space: nowrap;">
                        <button v-if="can('instituicao.show')" class="btn btn-sm btn-secondary action"
                          title="Visualizar" @click="visualizar(instituicao.id)">
                          <em class="fa fa-search"></em>
                        </button>
                        <button v-if="can('instituicao.show')" class="btn btn-sm btn-secondary action"
                          title="Usuários da Instituição" @click="usuariosModal(instituicao)">
                          <em class="fa fa-users"></em>
                        </button>
                        <button v-if="can('instituicao.update')" class="btn btn-sm btn-secondary action" title="Editar"
                          @click="editar(instituicao.id)">
                          <em class="fa fa-edit"></em>
                        </button>
                        <button v-if="instituicao.ativo && can('instituicao.inativar')"
                          class="btn btn-sm btn-secondary action" title="Desativar"
                          @click="alterarSituacao(instituicao.id, false)">
                          <em class="fa fa-toggle-off"></em>
                        </button>
                        <button v-if="!instituicao.ativo && can('instituicao.ativar')"
                          class="btn btn-sm btn-secondary action" title="Ativar"
                          @click="alterarSituacao(instituicao.id, true)">
                          <em class="fa fa-toggle-on"></em>
                        </button>
                        <button v-if="can('instituicao.destroy')" class="btn btn-sm btn-secondary action"
                          title="Excluir" @click="excluir(instituicao.id)">
                          <em class="fa fa-trash"></em>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="5">Nenhuma instituição cadastrada</td>
                    </tr>
                  </tbody>
                </table>
                <b-row>
                  <b-col cols="4">
                    <pagination-page tabindex="0" v-show="instituicoes.data !== undefined"
                      :totalPorPagina="totalPorPagina" :resource="resource" :filtros="filtros"
                      :origin="'Instituicoes'"></pagination-page>
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

      <!-- Modal de usuários vinculados -->
      <b-modal v-if="modalUsers.instituicao" v-model="modalUsers.show" title="Usuários da Instituição" size="lg"
        ok-title="Fechar" ok-only @hidden="clearModalUser">
        <b-row>
          <b-col sm="12">
            <p><b>Instituição:</b>&nbsp;{{ modalUsers.instituicao.razao_social }}</p>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="12">
            <b-table :items="modalUsers.usuarios" :fields="fields" table-class="text-center" bordered striped hover
              responsive sticky-header small show-empty>
              <template v-slot:cell(tipo_representacao)="data">
                {{ data.item.tipo_representacao == 'A' ? 'Autoridade máxima' : 'Representante' }}
              </template>
              <template #empty>
                Nenhum usuário vinculado a esta instituição.
              </template>
            </b-table>
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
    /* formatDateToBR: formatDateToBR */
  },
  created () {
    if (!this.can('instituicao.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('buscarEstados')

    let filtros = this.$store.getters.getPageFilter('Instituicoes')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      totalPorPagina: 20,
      resource: 'instituicao',
      overlay: false,
      situacoes: [
        { value: true, text: 'Ativo' },
        { value: false, text: 'Inativo' }
      ],
      filtros: {
        filter: '',
        cnpj: '',
        ativo: null,
        estado_id: null,
        cidade_id: null
      },
      fields: [
        { key: 'tipo_representacao', label: 'Tipo de responsável' },
        { key: 'nome', label: 'Nome' },
        { key: 'telefone_funcional', label: 'Telefone Funcional' },
        { key: 'celular', label: 'Celular' },
        { key: 'email', label: 'E-mail' }
      ],
      modalUsers: {
        show: false,
        instituicao: null,
        usuarios: []
      },
      ptBR: ptBR
    }
  },
  computed: {
    instituicoes () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    },
    estados () {
      return this.$store.state.estado.listaEstados
    },
    cidades () {
      return this.$store.state.cidade.listaCidadesPorEstado
    }
  },
  watch: {
    'filtros.estado_id': function () {
      if (this.filtros.estado_id !== null) {
        this.$store.dispatch('buscarCidadesPorEstado', this.filtros.estado_id)
      } else {
        this.filtros.cidade_id = null
        this.$store.state.cidade.listaCidadesPorEstado = []
      }
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Instituicoes', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    cadastrar () {
      this.$router.push('/instituicao/cadastrar')
    },
    editar (id) {
      this.$router.push('/instituicao/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/instituicao/' + id + '/visualizar')
    },
    excluir (id) {
      this.$bvModal.msgBoxConfirm(`Deseja excluir o registro?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.delete('api/instituicao/' + id).then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.pesquisar()
            } else {
              this.erro(response.body.msg)
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
    usuariosModal (instituicao) {
      this.modalUsers.instituicao = instituicao
      this.$store.state.loader.loader = true
      this.$http.get('api/users-instituicao/' + instituicao.id).then(response => {
        this.modalUsers.usuarios = response.body.data
        this.modalUsers.show = true
        this.$store.state.loader.loader = false
      }).catch(erro => {
        this.modalUsers.show = false
        this.erro('Erro ao buscar usuários da instituição!')
        this.$store.state.loader.loader = false
        console.log(erro)
      })
    },
    clearModalUser () {
      this.modalUsers.show = false
      this.modalUsers.instituicao = null
      this.modalUsers.usuarios = []
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
          let url = 'api/instituicao/' + id + (situacao ? '/ativo' : '/inativo')
          let data = {
            id: id
          }
          Vue.http.put(url, data).then(response => {
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
      }).catch(erro => {
        console.log(erro)
      })
    },
    navigate (route) {
      this.$router.push(route)
    },
    exportarExcel () {
      this.$store.state.loader.loader = true

      this.$http.post('api/instituicao/export/xls', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'instituicoes.xlsx'
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

      this.$http.post('api/instituicao/export/pdf', this.filtros, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/pdf' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'instituicoes.pdf'
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
      this.filtros.cnpj = ''
      this.filtros.id = ''
      this.filtros.ativo = null
      this.filtros.estado_id = null
      this.filtros.cidade_id = null
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
