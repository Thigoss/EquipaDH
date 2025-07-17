<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body v-if="verificacaoCredenciamento === true">
        <b-card-body>
          <h1 tabindex="0">
            Seu credenciamento encontra-se pendente.
          </h1>
        </b-card-body>
      </b-card>
      <b-card no-body v-if="verificacaoInexistente === true">
        <b-card-body>
          <h1 tabindex="0">
            Você não possuí credenciamento.
          </h1>
        </b-card-body>
      </b-card>
      <b-card no-body v-if="verificacaoInativo === true">
        <b-card-body>
          <h1 tabindex="0">Credenciamento da instituição encontra-se Inativo.</h1>
        </b-card-body>
      </b-card>
      <b-card no-body v-if="verificacaoExistente === false
        && verificacaoInativo === false
        && verificacaoInexistente === false
        && verificacaoCredenciamento === false">
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0"><strong>Nº do cronograma: </strong>{{ cronograma.numero }}</h1>
            <b-row>
              <b-col sm="12">
                <label tabindex="0">Declaração Unificada: *</label>
                <span class="icon-info" v-b-popover.hover.top="'DOC, DOCX e PDF'" title="Extensões aceitas"></span>
                <b-form-group label-for="nome-input">
                  <b-input-group>
                    <b-form-file ref="docAdesao" class="fileInput" accept=".doc, .docx, .pdf" v-on:change="uploadAdesao"
                      v-model="form.arquivo" browse-text="Selecione" placeholder="Upload"
                      :state="chkState('arquivo')"></b-form-file>
                    <b-input-group-append class="d-none d-md-block"
                      v-if="configuracao && configuracao.arquivo_ato_declaracao">
                      <b-button block variant="primary"
                        @click="$abrirDocumento('config_arquivo_ato_declara', configuracao.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Download de documento Declaração Unificada
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-button block variant="primary" class="d-block d-md-none"
                    v-if="configuracao && configuracao.arquivo_ato_declaracao"
                    @click="$abrirDocumento('config_arquivo_ato_declara', configuracao.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Download de documento Declaração Unificada
                  </b-button>
                  <b-form-invalid-feedback :state="chkState('arquivo')">Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col sm="12">
                <label tabindex="0">Formulário de Habilitação: *</label>
                <span class="icon-info" v-b-popover.hover.top="'DOC, DOCX e PDF'" title="Extensões aceitas"></span>
                <b-form-group label-for="nome-input">
                  <b-input-group>
                    <b-form-file ref="docHabilitacao" class="fileInput" accept=".doc, .docx, .pdf"
                      v-on:change="uploadFormularioHabilitacao" v-model="form.formulario_habilitacao"
                      browse-text="Selecione" placeholder="Upload"
                      :state="chkState('formulario_habilitacao')"></b-form-file>
                    <b-input-group-append class="d-none d-md-block"
                      v-if="configuracao && configuracao.arquivo_habilitacao">
                      <b-button block variant="primary"
                        @click="$abrirDocumento('config_arquivo_habilitacao', configuracao.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Download de documento Formulário de Habilitação
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-button block variant="primary" class="d-block d-md-none"
                    v-if="configuracao && configuracao.arquivo_habilitacao"
                    @click="$abrirDocumento('config_arquivo_habilitacao', configuracao.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Download de documento Formulário de Habilitação
                  </b-button>
                  <b-form-invalid-feedback :state="chkState('formulario_habilitacao')">Campo
                    obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <br>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group label-for="nome-input">
                  <b-form-checkbox v-model="form.declaracao" name="check-button" :state="chkState('declaracao')"
                    style="text-align: justify;">
                    {{ cronograma && cronograma.programa ? cronograma.programa.texto_confirmacao : 'Aceito as condições do cronograma.'}}
                  </b-form-checkbox>
                  <b-form-invalid-feedback :state="chkState('declaracao')">Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-card-body>
        <b-card-footer v-if="verificacaoExistente === false && verificacaoInativo === false" class="p-12">
          <b-row>
            <b-col v-if="!loading && !visualizar" md="3" sm="12" key="new_p1">
              <b-button block variant="primary" :disabled="disable_buttons" v-on:click="onSubmit">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Aderir</span>
              </b-button>
            </b-col>
            <b-col v-else-if="!visualizar" md="3" sm="12">
              <b-button block variant="primary" :disabled="true" v-on:click="onSubmit">
                <b-spinner small type="grow"></b-spinner>Salvando...
              </b-button>
            </b-col>
            <b-col md="3" sm="12">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="voltar">
                <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-footer>
      </b-card>
    </b-col>
  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import SessionStorage from '@/services/session-storage'

export default {
  name: 'AdesaoForm',
  components: {
    VueSelect,
    Multiselect,
    Datepicker
  },
  mixins: [validationMixin],
  validations: {
    form: {
      arquivo: {
        required
      },
      declaracao: {
        required,
        confirmed () {
          return this.form.declaracao === true
        }
      },
      formulario_habilitacao: {
        required
      }
    }
  },
  data () {
    return {
      loading: false,
      form: {
        cronograma_id: null,
        arquivo: null,
        formulario_habilitacao: null,
        declaracao: null
      },
      configuracao: {},
      cronograma: {},
      usuario: null,
      verificacaoExistente: false,
      verificacaoInativo: false,
      verificacaoInexistente: false,
      verificacaoCredenciamento: false,
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      ptBR: ptBR
    }
  },
  computed: {
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  created () {
    let userID = SessionStorage.get('usuario')
    if (userID) {
      this.loadUser(userID)
    }
  },
  mounted () {
    this.loadCronograma(this.$route.params.id)
    this.verificarCronograma(this.$route.params.id)
    this.getConfiguracaoGeral()
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    verificarCronograma (id) {
      Vue.http.get('api/cronograma-verificar/' + id).then(response => {
        let data = response.body.data
        this.verificacaoExistente = data.verificar
      })
    },
    verificarCredenciamento (usuario) {
      this.verificacaoInativo = usuario.instituicao ? !usuario.instituicao.ativo : false

      if (usuario.solicitacoes.length) {
        let solicitacao = usuario.solicitacoes[0]
        this.verificacaoCredenciamento = solicitacao.situacao !== 'AP'
      } else {
        this.verificacaoInexistente = true
      }
    },
    loadUser (id) {
      Vue.http.get('api/user/' + id).then(response => {
        let data = response.body.data
        this.usuario = data

        this.verificarCredenciamento(this.usuario)
      })
    },
    loadCronograma (id) {
      Vue.http.get('api/cronograma/' + id).then(response => {
        let data = response.body.data
        this.cronograma = data
        this.form.cronograma_id = id
      })
    },
    getConfiguracaoGeral () {
      Vue.http.get('api/configuracao-geral').then(response => {
        let data = response.body.data
        this.configuracao = data
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        cronograma_id: this.form.cronograma_id,
        arquivo: this.form.arquivo,
        formulario_habilitacao: this.form.formulario_habilitacao
      }

      this.loading = true
      Vue.http.post('api/adesao', data).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)
          setTimeout(() => {
            this.navigate('/minhas-adesoes')
          }, 3000)
        } else {
          this.erro(response.body.msg)
          this.loading = false
        }
      }).catch(e => {
        this.loading = false
        console.log('Servidor está fora! ' + e)
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
    uploadAdesao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAdesao.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.arquivo = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadFormularioHabilitacao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docHabilitacao.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.formulario_habilitacao = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
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
    voltar () {
      if (!this.visualizar) {
        this.$bvModal.msgBoxConfirm('Deseja retornar para tela de pesquisa? É possível que dados informados não sejam salvos.', {
          okTitle: 'Confirmar',
          cancelTitle: 'Cancelar'
        }).then(valor => {
          if (valor) {
            this.$router.go(-1)
          }
        })
      } else {
        this.$router.go(-1)
      }
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
    },
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
