<template>
  <span>
    <b-card no-body>
      <b-card-header>
        <b-row>
          <b-col md="9">
            <h3 tabindex="0"><strong>Gerenciar Perfis</strong></h3>
          </b-col>
          <b-col md="3">
            <b-button v-if="can('perfil.storeFrame')" @click="navigate('/perfil/cadastrar')" block variant="primary">
              <em class="fa fa-plus"></em>
              <span>Novo Perfil</span>
            </b-button>
          </b-col>
        </b-row>
      </b-card-header>
      <b-card-body>
        <b-row>
          <b-col sm="8">
            <b-form-group tabindex="0" label="Nome do Perfil: ">
              <b-form-input type="text" id="name" v-model="filtros.nome_perfil" placeholder=""
                v-on:keyup.enter="pesquisar"></b-form-input>
            </b-form-group>
          </b-col>
          <b-col sm="4">
            <b-form-group tabindex="0" label="Situação:">
              <b-form-select v-model="filtros.status" class="mb-3">
                <option selected :value="null"> Selecione </option>
                <option v-for="situacao in situacoes" :value="situacao.value" :key="situacao.value"> {{ situacao.text }}
                </option>
              </b-form-select>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col sm="4">
            <b-form-group tabindex="0" label="Tipo de acesso:">
              <b-form-select v-model="filtros.tipo_perfil_id" class="mb-3">
                <option selected :value="null"> Selecione </option>
                <option v-for="tipo in options_tipos_perfis" :value="tipo.value" :key="tipo.value"> {{ tipo.text }}
                </option>
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
                    <th tabindex="0" scope="col">Nome do Perfil</th>
                    <th tabindex="0" scope="col">Tipo de Acesso</th>
                    <th tabindex="0" scope="col">Situação</th>
                    <th tabindex="0" scope="col">Ações</th>
                  </tr>
                </thead>

                <tbody v-if="perfis.data !== undefined">
                  <tr class="action" v-for="item in perfis.data" :key="item.id">
                    <td tabindex="0">{{ item.nome }}</td>
                    <td tabindex="0">
                      <span v-if="item.tipo == 1">Administrador Geral</span>
                      <span v-else-if="item.tipo == 2">Padrão</span>
                      <span v-else-if="item.tipo == 3">Solicitante</span>
                    </td>
                    <td tabindex="0" style="text-align: center">{{ item.ativo == false ? 'Inativo' : 'Ativo' }}</td>
                    <td style="width: 1%; white-space: nowrap;">
                      <button v-if="!item.fixo && can('perfil.updateFrame')" class="btn btn-sm btn-secondary action"
                        title="Editar" @click="editar(item.codigo)">
                        <em class="fa fa-edit"></em>
                      </button>
                      <button v-if="can('perfil.showFrame')" class="btn btn-sm btn-secondary action" title="Visualizar"
                        @click="visualizar(item.codigo)">
                        <em class="fa fa-search"></em>
                      </button>
                      <button v-if="can('perfil.storeFrame')" class="btn btn-sm btn-secondary action"
                        title="Vincular um Tipo de Acesso" @click="abrirModalTipo(item.id, item)">
                        <em class="fa fa-user-lock"></em>
                      </button>
                      <button v-if="item.ativo && can('perfil.updateFrame')" class="btn btn-sm btn-secondary action"
                        title="Desativar" @click="alterarSituacao(item.id, false)">
                        <em class="fa fa-toggle-off"></em>
                      </button>
                      <button v-if="!item.ativo && can('perfil.updateFrame')" class="btn btn-sm btn-secondary action"
                        title="Ativar" @click="alterarSituacao(item.id, true)">
                        <em class="fa fa-toggle-on"></em>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr class="action">
                    <td tabindex="0" colspan="5">Nenhum Perfil Cadastrado</td>
                  </tr>
                </tbody>
              </table>
              <b-row>
                <b-col cols="4">
                  <pagination-page tabindex="0" v-show="perfis.data !== undefined" :totalPorPagina="totalPorPagina"
                    :resource="resource" :filtros="filtros" :origin="'Perfis'"></pagination-page>
                </b-col>
              </b-row>
            </span>
          </b-container>
        </b-col>
      </b-row>
    </b-card>

    <b-modal :hide-footer="esconder_footer_tipo" :title="titulo_descricao_tipo" size="lg" ok-title="Salvar"
      cancel-title="Cancelar" v-model="modalTipoPerfil" @ok="onSubmitTipo">
      <b-overlay :show="show" rounded="sm">
        <b-card-body>
          <b-form @submit.stop.prevent="onSubmitTipo">
            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Perfil: ">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" autocomplete="complemento"
                      v-model.trim="form.nome_perfil" :disabled="true" />
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="6" sm="6">
                <b-form-group tabindex="0" label="Tipo de Acesso: *">
                  <b-form-select v-model="form_tipo.tipo_perfil" placeholder="Selecione" class="mb-3">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in options_tipos_perfis" :value="item.value" :key="item.value"> {{ item.text }}
                    </option>
                  </b-form-select>
                  <b-form-invalid-feedback v-if="!$v.form_tipo.tipo_perfil.required" id="input1LiveFeedback3">Tipo de
                    Acesso é obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-card-body>
      </b-overlay>
    </b-modal>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import PaginationPage from '@/components/shared/PaginationPage'
import { eventType } from '@/components/shared/eventType'
import 'v-calendar/lib/v-calendar.min.css'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import { validationMixin } from 'vuelidate'
import { required, requiredIf } from 'vuelidate/lib/validators'

miniToastr.init()

export default {
  components: {
    Pagination,
    PaginationPage,
    Multiselect
  },
  mixins: [validationMixin],
  validations: {
    form: {
      limite_usuarios: {
        required
      },
      qtd_limite_usuarios: {
        required: requiredIf(function () {
          return this.exibe_qtd_limite_usuarios
        }),
      },
    },
    form_tipo: {
      tipo_perfil: {}
    }
  },
  created () {
    if (!this.can('perfil.index')) {
      this.$router.push('/home')
    }

    this.$store.dispatch('getTiposPerfil')

    let filtros = this.$store.getters.getPageFilter('Perfis')
    if (filtros && filtros !== undefined) {
      this.filtros = filtros
    }
  },
  data () {
    return {
      show: false,
      esconder_footer: false,
      esconder_footer_tipo: false,
      perfil_id: null,
      exibe_qtd_limite_usuarios: false,
      titulo_descricao: 'Limitar usuários com este Perfil',
      titulo_descricao_tipo: 'Vincular Tipo de Acesso',
      salvarForm: false,
      titulo_confirmacao: 'Confirma a configuração?',
      titulo_confirmacao_tipo: 'Confirma o tipo de acesso?',
      modalConfiguracao: false,
      modalTipoPerfil: false,
      totalPorPagina: 20,
      resource: 'perfil',
      form: {
        nome_perfil: '',
        limite_usuarios: null,
        qtd_limite_usuarios: null
      },
      form_tipo: {
        tipo_perfil: null
      },
      filtros: {
        nome_perfil: '',
        status: null,
        tipo_perfil_id: null
      },
      situacoes: [
        { value: true, text: 'Ativo' },
        { value: false, text: 'Inativo' }
      ],
      options: [
        { value: true, text: 'Sim' },
        { value: false, text: 'Não' }
      ],
      options_numbers: [
        { value: 1, text: '1' },
        { value: 2, text: '2' },
        { value: 3, text: '3' },
        { value: 4, text: '4' },
        { value: 5, text: '5' },
        { value: 6, text: '6' },
        { value: 7, text: '7' },
        { value: 8, text: '8' },
        { value: 9, text: '9' },
        { value: 10, text: '10' },
        { value: 11, text: '11' },
        { value: 12, text: '12' },
        { value: 13, text: '13' },
        { value: 14, text: '14' },
        { value: 15, text: '15' },
        { value: 16, text: '16' },
        { value: 17, text: '17' },
        { value: 18, text: '18' },
        { value: 19, text: '19' },
        { value: 20, text: '20' }
      ],
      options_tipos_perfis: [
        { value: 1, text: 'Administrador Geral' },
        { value: 2, text: 'Padrão' },
        { value: 3, text: 'Solicitante' }
      ],
    }
  },
  computed: {
    perfis () {
      if (this.$store.state.pagination.getList.data !== undefined) {
        if (this.$store.state.pagination.getList.data.length !== 0) {
          return this.$store.state.pagination.getList
        } else {
          return false
        }
      }
      return this.$store.state.pagination.getList
    },
    isValid () {
      return !this.$v.$anyError
    },
    isValidTipo () {
      return !this.$v.form_tipo.$anyError
    },
  },
  watch: {
    'form.limite_usuarios': function () {
      if (this.form.limite_usuarios == true) {
        this.exibe_qtd_limite_usuarios = true
      } else {
        this.exibe_qtd_limite_usuarios = false
        this.form.qtd_limite_usuarios = null
      }
    }
  },
  methods: {
    visualizarHistorico (id) {
      this.$router.push('/perfil/' + id + '/historico')
    },
    onSubmit (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.salvarForm = true
      if (this.validate()) {
        this.modalConfirma()
      }
    },
    onSubmitTipo (bvModalEvt) {
      bvModalEvt.preventDefault()
      this.salvarForm = true
      if (this.validateTipo()) {
        this.modalConfirmaTipo()
      }
    },
    modalConfirma () {
      this.$bvModal.msgBoxConfirm(this.titulo_confirmacao, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.esconder_footer = true
          let data = {
            perfil_id: this.perfil_id,
            limite_usuarios: this.form.limite_usuarios,
            qtd_limite_usuarios: this.form.qtd_limite_usuarios
          }
          Vue.http.post('api/perfil/configurar-limite', data).then(response => {
            if (response.body.success) {
              this.modalConfiguracao = false
              miniToastr.setIcon('success', 'i', { 'class': 'fa fa-check' })
              miniToastr.success(response.body.msg, 'Sucesso!', 7000)
              this.pesquisar()
            }
          }).catch(erro => {
            if (!erro.body.success) {
              miniToastr.setIcon('error', 'i', { 'class': 'fa fa-check' })
              miniToastr.error(erro.body.msg, 'Alerta!', 7000)
              this.pesquisar()
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    modalConfirmaTipo () {
      this.$bvModal.msgBoxConfirm(`${this.form_tipo.tipo_perfil == 3 ? 'Somente um perfil pode ser do tipo solicitante, ao continuar se houver outro perfil com esse tipo ele será alterado para Padrão, deseja continuar?' : this.titulo_confirmacao_tipo}`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.show = true
          this.esconder_footer_tipo = true

          let data = {
            perfil_id: this.perfil_id,
            tipo_perfil: this.form_tipo.tipo_perfil
          }

          Vue.http.post('api/perfil/tipo-perfil', data).then(response => {
            if (response.body.success) {
              this.show = false
              this.modalTipoPerfil = false
              miniToastr.setIcon('success', 'i', { 'class': 'fa fa-check' })
              miniToastr.success(response.body.msg, 'Sucesso!', 7000)
              this.pesquisar()
            }
          }).catch(erro => {
            if (!erro.body.success) {
              miniToastr.setIcon('error', 'i', { 'class': 'fa fa-check' })
              miniToastr.error(erro.body.msg, 'Alerta!', 7000)
              this.show = false
              this.esconder_footer_tipo = false
              this.pesquisar()
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      this.$store.dispatch('updateFilter', { page: 'Perfis', filter: this.filtros })
    },
    pesquisar () {
      this.setarFiltros()
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.nome_perfil = null
      this.filtros.status = null
      this.filtros.tipo_perfil_id = null

      this.pesquisar()
    },
    editar (id) {
      this.$router.push('/perfil/' + id + '/editar')
    },
    visualizar (id) {
      this.$router.push('/perfil/' + id + '/visualizar')
    },
    configurar (id) {
      this.esconder_footer = false
      this.form.limite_usuarios = null
      this.form.qtd_limite_usuarios = null
      this.form.nome_perfil = ''
      this.$v.$reset();

      let perfis = this.perfis.data

      for (let i in perfis) {
        let element = perfis[i]
        if (element.id == id) {
          this.form.nome_perfil = element.nome
        }
      }
      this.modalConfiguracao = true
      this.perfil_id = id
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
          let url = 'api/perfil/' + id + (situacao ? '/ativo' : '/inativo')
          let data = {
            id: id
          }
          Vue.http.put(url, data).then(response => {
            console.log(response)
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.pesquisar()
            } else {
              this.erro(response.body.msg)
              this.pesquisar()
            }
          }).catch(erro => {
            if (!erro.body.success) {
              this.erro(erro.msg)
              this.pesquisar()
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    abrirModalTipo (id, perfil) {
      this.esconder_footer_tipo = false

      this.form_tipo.tipo_perfil = null
      this.$v.$reset()

      this.form.nome_perfil = perfil.nome

      if (perfil.tipo) {
        this.form_tipo.tipo_perfil = perfil.tipo
      }

      this.modalTipoPerfil = true
      this.perfil_id = id
    },
    chkState (val) {
      const field = this.$v.form[val]
      if (!field.$model && !this.salvarForm) {
        return null
      }
      return !field.$dirty || !field.$invalid
    },
    chkStateTipo (val) {
      const field = this.$v.form_tipo[val]
      if (!field.$model && !this.salvarForm) {
        return null
      }
      return !field.$dirty || !field.$invalid
    },
    findFirstError (component = this) {
      if (component.state === false) {
        if (component.$refs.input) {
          component.$refs.input.focus()
          return true
        }
        if (component.$refs.check) {
          component.$refs.check.focus()
          return true
        }
      }
      let focused = false
      component.$children.some((child) => {
        focused = this.findFirstError(child)
        return focused
      })
      return focused
    },
    validate () {
      this.$v.$touch()
      this.$nextTick(() => this.findFirstError())
      return this.isValid
    },
    validateTipo () {
      this.$v.$touch()
      this.$nextTick(() => this.findFirstError())
      return this.isValidTipo
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
