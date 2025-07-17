<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0"><strong>Configurações Gerais</strong></h1>

            <br>
            <b-row>
              <b-col md="8" sm="12">
                <h3><strong>Credenciamento</strong></h3>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0">
                  <vue-editor :editor-toolbar="customEditorToolbar"
                    v-model="form.orientacoes_credenciamento"></vue-editor>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="8" sm="12">
                <b-form-group tabindex="0" label="Ato de delegação de competência: ">
                  <b-form-group label-for="nome-input">
                    <b-form-file ref="docAtoDelegacaoCompetencia" class="fileInput" accept=".docx., .pdf"
                      v-on:change="uploadAtoDelegacao" v-model="form.arquivo_ato_delegacao" browse-text="Selecione"
                      placeholder="Upload" :state="chkState('arquivo_ato_delegacao')"></b-form-file>
                    <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                  </b-form-group>
                  <b-button v-if="form.id" block variant="primary"
                    @click="$abrirDocumento('config_arquivo_ato_delegacao', form.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Ato de delegação de competência
                  </b-button>
                </b-form-group>
              </b-col>
            </b-row>

            <hr>
            <b-row>
              <b-col md="8" sm="12">
                <h3><strong>Adesão</strong></h3>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0" label="Orientações: ">
                  <vue-editor :editor-toolbar="customEditorToolbar" v-model="form.orientacoes_adesao"></vue-editor>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0" label="Solicite seu credenciamento: ">
                  <vue-editor :editor-toolbar="customEditorToolbar" v-model="form.orientacoes_solicitacao"></vue-editor>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="8" sm="12">
                <b-form-group tabindex="0" label="Declaração unificada: ">
                  <b-form-group label-for="nome-input">
                    <b-form-file ref="docDeclaracaoUnificada" class="fileInput" accept=".docx., .pdf"
                      v-on:change="uploadDeclaracaoUnificada" v-model="form.arquivo_declaracao_unificada"
                      browse-text="Selecione" placeholder="Upload"
                      :state="chkState('arquivo_declaracao_unificada')"></b-form-file>
                    <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                  </b-form-group>
                  <b-button v-if="form.id" block variant="primary"
                    @click="$abrirDocumento('config_arquivo_ato_declara', form.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Declaração unificada
                  </b-button>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="8" sm="12">
                <b-form-group tabindex="0" label="Formulário de Habilitação: ">
                  <b-form-group label-for="nome-input">
                    <b-form-file ref="docHabilitacao" class="fileInput" accept=".docx., .pdf"
                      v-on:change="uploadHabilitacao" v-model="form.arquivo_habilitacao" browse-text="Selecione"
                      placeholder="Upload" :state="chkState('arquivo_habilitacao')"></b-form-file>
                    <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                  </b-form-group>
                  <b-button
                    v-if="form.id && form.arquivo_habilitacao && form.arquivo_habilitacao.includes('configuracao')"
                    @click="$abrirDocumento('config_arquivo_habilitacao', form.id)" variant="primary" block>
                    <em class="fa fa-download"></em>&nbsp;
                    Formulário de Habilitação
                  </b-button>
                </b-form-group>
              </b-col>
            </b-row>
          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col v-if="!loading" md="3" sm="12" key="new_p1">
              <b-button block variant="primary" :disabled="disable_buttons" v-on:click="onSubmit">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col v-else md="3" sm="12">
              <b-button block variant="primary" :disabled="true" v-on:click="onSubmit">
                <b-spinner small type="grow"></b-spinner>Salvando...
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
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import { VueEditor } from 'vue2-editor'
miniToastr.init()

export default {
  name: 'ConfiguracaoGeralForm',
  components: {
    VueSelect,
    Multiselect,
    Datepicker,
    VueEditor
  },
  mixins: [validationMixin],
  validations: {
    form: {
      orientacoes_credenciamento: {
        required
      },
      arquivo_ato_declaracao: {

      },
      arquivo_habilitacao: {

      },
      arquivo_ato_delegacao: {
        required
      },
      orientacoes_adesao: {
        required
      },
      orientacoes_solicitacao: {
        required
      },
      arquivo_declaracao_unificada: {
        required
      }
    }
  },
  data () {
    return {
      loading: false,
      form: {
        id: null,
        orientacoes_credenciamento: null,
        arquivo_ato_declaracao: null,
        arquivo_ato_delegacao: null,
        orientacoes_adesao: null,
        orientacoes_solicitacao: null,
        arquivo_declaracao_unificada: null,
        arquivo_habilitacao: null
      },
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
  mounted () {
    this.load()
    if (!this.can('configuracaoGeral.update')) {
      this.$router.push('/home')
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load () {
      Vue.http.get('api/configuracao-geral').then(response => {
        let data = response.body.data

        if (data) {
          this.form.id = data.id
          this.form.orientacoes_credenciamento = data.orientacoes_credenciamento
          this.form.arquivo_ato_declaracao = data.arquivo_ato_declaracao
          this.form.arquivo_ato_delegacao = data.arquivo_ato_delegacao
          this.form.orientacoes_adesao = data.orientacoes_adesao
          this.form.orientacoes_solicitacao = data.orientacoes_solicitacao
          this.form.arquivo_declaracao_unificada = data.arquivo_declaracao_unificada
          this.form.arquivo_habilitacao = data.arquivo_habilitacao
        }
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        orientacoes_credenciamento: this.form.orientacoes_credenciamento,
        arquivo_ato_declaracao: this.form.arquivo_ato_declaracao,
        arquivo_ato_delegacao: this.form.arquivo_ato_delegacao,
        orientacoes_adesao: this.form.orientacoes_adesao,
        orientacoes_solicitacao: this.form.orientacoes_solicitacao,
        arquivo_declaracao_unificada: this.form.arquivo_declaracao_unificada,
        arquivo_habilitacao: this.form.arquivo_habilitacao
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/configuracao-geral/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false
            this.load()
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/configuracao-geral', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.load()
            this.loading = false
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      }
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
    uploadAtoDeclaracao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoDeclaracao.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.arquivo_ato_declaracao = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadAtoDelegacao (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docAtoDelegacaoCompetencia.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.arquivo_ato_delegacao = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadDeclaracaoUnificada (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docDeclaracaoUnificada.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.arquivo_declaracao_unificada = evento.target.result
      }
      reader.readAsDataURL(arquivo[0])
    },
    uploadHabilitacao (evento) {
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
        this.form.arquivo_habilitacao = evento.target.result
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
