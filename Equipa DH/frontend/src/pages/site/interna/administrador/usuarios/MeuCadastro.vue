<template>
  <b-overlay :show="overlay" rounded="sm">
    <b-col md="12" sm="12">
      <b-card no-body class="mx-4">
        <b-card-body class="p-12">
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0">Meu Cadastro</h1>
            <b-row>
              <b-col cols="12">
                <b-form-group tabindex="0" label="Nome Completo: *">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="Nome" autocomplete="nome"
                      v-model.trim="$v.form.nome.$model" :state="chkState('nome')" disabled />
                    <b-form-invalid-feedback id="input1LiveFeedback3">Nome é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="4">
                <b-form-group tabindex="0" label="CPF: *">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input v-mask="'###.###.###-##'" type="text" class="form-control" placeholder="CPF"
                      autocomplete="cpf" v-model="$v.form.cpf.$model" :state="chkState('cpf')" disabled />
                    <b-form-invalid-feedback id="input1LiveFeedback1">CPF é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="RG: ">
                  <b-input-group class="mb-6">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" v-mask="'###############'" class="form-control" placeholder="RG"
                      autocomplete="rg" v-model="$v.form.rg.$model" />
                    <b-form-invalid-feedback id="input1LiveFeedback1">RG é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Data de Nascimento: ">
                  <b-input-group>
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class='fa fa-calendar'></i>
                      </span>
                    </div>
                    <b-form-input id="type-date" type="date" v-model="dataNascimento"
                      v-model.lazy.trim="$v.form.data_nascimento.$model" />
                    <b-form-invalid-feedback id="input1LiveFeedback2">Data de Nascimento é obrigatória.
                    </b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="6">
                <b-form-group tabindex="0" label="E-mail: *">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text>@</b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="E-mail" autocomplete="email"
                      v-model.trim="$v.form.email.$model" :state="chkState('email')" disabled />
                    <b-form-invalid-feedback id="input1LiveFeedback7">Este é um campo obrigatório, e deve ser um
                      endereço de e-mail válido.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="6">
                <b-form-group tabindex="0" label="DDD + Telefone">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="fa fa-phone"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" v-mask="['+55 (##) ####-####', '+55 (##) #####-####']"
                      class="form-control" placeholder="Telefone" autocomplete="telefone"
                      v-model.lazy.trim="$v.form.telefone.$model" />
                    <b-form-invalid-feedback id="input1LiveFeedback4">Telefone é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <h4 tabindex="0">Dados de Atuação</h4>
            <b-row>
              <b-col cols="5">
                <b-form-group tabindex="0" label="Órgão Público: *">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="Órgão Público"
                      autocomplete="orgao_publico" v-model.trim="$v.form.orgao_publico.$model"
                      :state="chkState('orgao_publico')" />
                    <b-form-invalid-feedback id="input1LiveFeedback3">Órgão Público é
                      obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="3">
                <b-form-group tabindex="0" label="UF: *">
                  <b-form-select v-model.trim="$v.form.estado_atuacao_id.$model" placeholder="UF" class="mb-3"
                    :state="chkState('estado_atuacao_id')">
                    <option v-for="(item, index) in estados_atuacao" :key="index" :value="index"> {{ item }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback id="input1LiveFeedback7">UF é obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Município: *">
                  <b-form-select v-model.trim="$v.form.cidade_atuacao_id.$model" placeholder="Município" class="mb-3"
                    :state="chkState('cidade_atuacao_id')" :disabled="disabled_cidades_atuacao">
                    <option v-for="item in cidades_atuacao" :value="item.id" :key="item.id"> {{ item.cidade }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback id="input1LiveFeedback7">Município é obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <h4 tabindex="0">Endereço</h4>
            <b-row>
              <b-col cols="3">
                <b-form-group tabindex="0" label="CEP">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" v-mask="'#####-###'" class="form-control" placeholder="CEP"
                      autocomplete="cep" v-model.lazy.trim="$v.form.cep.$model" :state="chkState('cep')" />
                    <b-form-invalid-feedback id="input1LiveFeedback3">CEP é obrigatório, e deve ser
                      válido.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="9">
                <b-form-group tabindex="0" label="Endereço:">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="icon-user"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="Endereço" autocomplete="endereco"
                      v-model.trim="$v.form.endereco.$model" :state="chkState('endereco')" />
                    <b-form-invalid-feedback id="input1LiveFeedback3">Endereço é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="3">
                <b-form-group tabindex="0" label="Número:">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text><i class="fa fa-map-marker"></i>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" v-mask="'######'" placeholder="Número"
                      autocomplete="numero" v-model.trim="$v.form.numero.$model" :state="chkState('numero')" />
                    <b-form-invalid-feedback id="input1LiveFeedback3">Número é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="2">
                <b-form-group tabindex="0" label="UF: *">
                  <b-form-select v-model.trim="$v.form.estado_id.$model" placeholder="UF" class="mb-3"
                    :state="chkState('estado_id')">
                    <option v-for="(item, index) in estados_atuacao" :key="index" :value="index"> {{ item }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback id="input1LiveFeedback7">UF é obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="3">
                <b-form-group tabindex="0" label="Município: *">
                  <b-form-select v-model.trim="$v.form.cidade_id.$model" placeholder="Município" class="mb-3"
                    :state="chkState('cidade_id')" :disabled="disabled_cidades">
                    <option v-for="item in cidades" :value="item.id" :key="item.id"> {{ item.cidade }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback id="input1LiveFeedback7">Município é obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Bairro:">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <i class="fa fa-map-marker"></i>
                      </b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="Bairro" autocomplete="bairro"
                      v-model.trim="$v.form.bairro.$model" :state="chkState('bairro')" />
                    <b-form-invalid-feedback id="input1LiveFeedback3">Bairro é obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col cols="5">
                <b-form-group tabindex="0" label="Complemento:">
                  <b-input-group class="mb-3">
                    <b-input-group-prepend>
                      <b-input-group-text>
                        <i class="fa fa-map-marker"></i></b-input-group-text>
                    </b-input-group-prepend>
                    <b-form-input type="text" class="form-control" placeholder="Complemento" autocomplete="complemento"
                      v-model.trim="$v.form.complemento.$model" />
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <h4 tabindex="0">Assinatura</h4>
            <b-row>
              <b-col md="5" sm="12" cols="12">
                <label tabindex="0">Assinatura Responsável:</label>
                <span class="icon-info" v-b-popover.hover.top="'JPG, JPEG, PNG'" title="Extensões aceitas"></span>
                <b-form-group label-for="nome-input">
                  <b-form-file class="fileInput" accept="image/png, image/jpeg, image/jpg"
                    v-on:change="uploadAssinatura" v-model="form.assinatura" browse-text="Selecione"
                    placeholder="Selecione uma assinatura" :state="chkState('assinatura')"></b-form-file>
                  <b-form-invalid-feedback :state="chkState('assinatura')" id="input1LiveFeedback3">Assinatura é
                    obrigatória.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="2">
                <b-button style="margin-top: 2em;" block variant="primary" :disabled="!assinaturaBool"
                  @click="abrirDocumento(form.assinatura)">
                  <i class="fa fa-download"></i>
                </b-button>
              </b-col>
            </b-row>
          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col v-if="!loading" key="k" md="3">
              <b-button block variant="primary" :disabled="disable_buttons" v-on:click="onSubmit">
                <i class="fa fa-plus"></i>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col v-else md="3">
              <b-button block variant="primary" :disabled="true" v-on:click="onSubmit">
                <b-spinner small type="grow"></b-spinner>Salvando...
              </b-button>
            </b-col>
            <b-col md="3">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="navigate('/home')">
                <i class="fa fa-mail-reply-all"></i>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-footer>
      </b-card>
    </b-col>
  </b-overlay>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { validationMixin } from 'vuelidate'
import { required, minLength, email, maxLength } from 'vuelidate/lib/validators'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'

miniToastr.init()

export default {
  name: 'Cadastro',
  components: {
    VueSelect,
    Multiselect
  },
  mixins: [validationMixin],
  validations: {
    form: {
      cpf: {
        required,
        minLength: minLength(14)
      },
      nome: {
        required
      },
      email: {
        required,
        email
      },
      rg: {
        maxLength: maxLength(15)
      },
      data_nascimento: {},
      telefone: {},
      orgao_publico: {
        required
      },
      estado_atuacao_id: {
        required
      },
      cidade_atuacao_id: {
        required
      },
      cep: {
        required,
        minLength: minLength(9),
        async validarCep (cep) {
          return Vue.http.post('api/gerenciar-pessoa-tea/buscaCep', { cep: cep }).then(response => {
            if (response.body.success) {
              if (response.body.data[0] != null) {
                return true
              }
              return false
            }
          })
        }
      },
      endereco: {
        required
      },
      numero: {
        required
      },
      bairro: {
        required
      },
      estado_id: {
        required
      },
      cidade_id: {
        required
      },
      complemento: {},
      assinatura: {
        required
      }
    }
  },
  created () {
    this.$store.dispatch('buscarEstados')
    this.$store.dispatch('buscarCidades')
    this.$store.dispatch('getPerfisListaCombo')
  },
  data () {
    return {
      editarCep: 1,
      loading: false,
      overlay: false,
      usuarioCorrente: parseInt(SessionStorage.get('usuario')),
      form: {
        id: '',
        cpf: '',
        rg: '',
        data_nascimento: '',
        nome: '',
        email: '',
        telefone: '',
        orgao_publico: '',
        estado_atuacao_id: null,
        cidade_atuacao_id: null,
        cep: '',
        endereco: '',
        numero: '',
        complemento: '',
        bairro: '',
        estado_id: null,
        cidade_id: null,
        perfil_id: null,
        assinatura: null
      },
      disabled_cidades: true,
      disabled_cidades_atuacao: true,
      disable_buttons: false,
      titulo_toast: 'Alerta!',
      salvarForm: false,
      todas_cidades: false,
      modalExcluir: false,
      assinaturaBool: true
    }
  },
  computed: {
    perfis () {
      return this.$store.state.perfil.lista
    },
    estados () {
      return this.$store.state.estado.listaEstados
    },
    cidades () {
      return this.$store.state.cidade.listaCidadesPorEstado
    },
    estados_atuacao () {
      return this.$store.state.estado.listaEstados
    },
    cidades_atuacao () {
      return this.$store.state.cidade.listaCidadesPorEstadoAtuacao
    },
    isValid () {
      return !this.$v.form.$anyError
    },
    isValidSelection () {
      return this.selected !== '' && this.selected !== 'forbidden value'
    }
  },
  watch: {
    '$v.form.estado_id.$model': function () {
      this.$store.dispatch('buscarCidadesPorEstado', this.form.estado_id)
      this.disabled_cidades = false
    },
    '$v.form.estado_atuacao_id.$model': function () {
      this.$store.dispatch('buscarCidadesPorEstadoAtuacao', this.form.estado_atuacao_id)
      this.disabled_cidades_atuacao = false
    },
    '$v.form.cep.$model': function () {
      let cep = this.$v.form.cep.$model.replace(/[^\d]+/g, '')

      if (cep.length > 7 && this.editarCep > 1) {
        this.buscaCep(cep)
      }
      this.editarCep++
    }
  },
  mounted () {
    if (this.$route.params.id) {
      this.loadUser(this.$route.params.id)
    } else {
      this.loadUser(parseInt(SessionStorage.get('usuario')))
    }
  },
  methods: {
    uploadAssinatura (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files

      if (!arquivo.length) {
        return false
      }

      let reader = new FileReader()

      reader.onload = (evento) => {
        this.form.assinatura = evento.target.result
      }

      reader.readAsDataURL(arquivo[0])
    },
    abrirDocumento (path) {
      window.open(path, '_blank')
    },
    buscaCep (cep) {
      let filtro = { cep: cep }
      Vue.http.post('api/gerenciar-pessoa-tea/buscaCep', filtro).then(response => {
        if (response.body.success) {
          let cep = response.body.data[0]

          if (cep != null) {
            this.$v.form.bairro.$model = cep.bairro
            this.$v.form.endereco.$model = cep.endereco
            this.$v.form.estado_id.$model = cep.estado
            this.$v.form.cidade_id.$model = cep.cidade
          }
        }
      })
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    loadUser (id) {
      Vue.http.get('api/usuario/' + id).then(response => {
        let data = response.body.data[0]

        this.form.id = data.id
        this.form.cpf = data.cpf
        this.form.rg = data.rg
        this.form.nome = data.nome
        this.form.data_nascimento = data.data_nascimento
        this.form.email = data.email
        this.form.telefone = data.telefone
        this.form.orgao_publico = data.orgao_publico
        this.form.estado_atuacao_id = data.estado_atuacao_id ? data.estado_atuacao_id : null
        this.form.cidade_atuacao_id = data.cidade_atuacao_id
        this.form.cep = data.cep
        this.form.endereco = data.endereco
        this.form.numero = data.numero
        this.form.complemento = data.complemento
        this.form.bairro = data.bairro
        this.form.estado_id = data.estado_id ? data.estado_id : null
        this.form.cidade_id = data.cidade_id
        this.form.perfil_id = data.perfil_id
        this.form.assinatura = data.assinatura

        if (data.assinatura === null) {
          this.assinaturaBool = false
        }
      })
    },
    salvar () {
      let form = this.$v.form
      let data = {
        cpf: form.cpf.$model.replace(/[^\d]+/g, ''),
        rg: form.rg.$model,
        nome: form.nome.$model,
        data_nascimento: form.data_nascimento.$model,
        email: form.email.$model,
        email_confirmation: form.email.$model,
        telefone: form.telefone.$model,
        orgao_publico: form.orgao_publico.$model,
        estado_atuacao_id: form.estado_atuacao_id.$model,
        cidade_atuacao_id: form.cidade_atuacao_id.$model,
        cep: form.cep.$model.replace(/[^\d]+/g, ''),
        endereco: form.endereco.$model,
        numero: form.numero.$model,
        complemento: form.complemento.$model,
        bairro: form.bairro.$model,
        estado_id: form.estado_id.$model,
        cidade_id: form.cidade_id.$model,
        ativo: true,
        perfis: JSON.stringify([{ id: this.form.perfil_id }]),
        gerarToken: false,
        assinatura: form.assinatura.$model
      }

      this.loading = true
      Vue.http.put('api/usuario/' + this.form.id, data).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)
          setTimeout(() => {
            location.reload()
          }, 2000)
        } else {
          this.erro(response.body.msg)
        }
        this.loading = false
      }).catch(e => {
        console.log('Servidor está fora! ' + e)
      })
    },
    editarUsuario () {
      this.$router.push('/usuario/' + this.$route.params.id + '/editar')
    },
    abrirModalExclusao () {
      this.modalExcluir = true
    },
    exclusao () {
      this.$bvModal.msgBoxConfirm('Confirma a exclusão do registro selecionado?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        Vue.http.get('api/usuario/excluir/' + this.$route.params.id).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
            this.$router.push({ path: '/home' })
          }
        }).catch(erro => {
          if (!erro.body.success) {
            this.erro(erro.body.msg)
            this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros })
          }
        })
      }).catch(erro => {
        console.log(erro)
      })
    },
    confirmar () {
      this.$bvModal.msgBoxConfirm('Tem certeza que deseja continuar?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.salvar()
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    onSubmit () {
      this.salvarForm = true
      if (this.validate()) {
        this.confirmar()
      }
    },
    chkState (val) {
      const field = this.$v.form[val]
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
    navigate (route) {
      this.$router.push(route)
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
.fileInput {
  word-break: break-all;
  overflow: hidden;
}
</style>
