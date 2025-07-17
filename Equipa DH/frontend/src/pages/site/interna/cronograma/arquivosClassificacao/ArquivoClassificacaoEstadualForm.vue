<template>
  <b-card no-body>
    <b-card-body>
      <b-form v-on:submit.prevent="onSubmit">
        <h1 tabindex="0" v-if="!form.id"><strong>{{ $route.name }}</strong></h1>
        <br />
        <b-row>
          <b-col md="12" sm="12">
            <b-form-group tabindex="0" label="Arquivo:">
              <b-input-group class="mb-3">
                <b-form-file ref="arquivo-uploader" class="fileInput" accept=".csv" v-on:change="uploadDocumento"
                  v-model="tempFile" :disabled="visualizar || disabled" browse-text="Selecione" placeholder="Upload"
                  :state="chkState('arquivo')">
                </b-form-file>
                <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="12" sm="12">
            <b-form-group tabindex="0" label="Nome:">
              <b-input-group class="mb-3">
                <b-form-input type="text" class="form-control" placeholder="" autocomplete="nome"
                  :state="chkState('nome')" v-model="$v.form.nome.$model"
                  :disabled="visualizar || disabled || !form.arquivo" />
                <b-form-invalid-feedback>Campo obrigatório, deve ser um nome válido de arquivo, contendo a extensão
                  <b>.csv</b> ao final.</b-form-invalid-feedback>
              </b-input-group>
            </b-form-group>
          </b-col>
        </b-row>
      </b-form>
    </b-card-body>
    <b-card-footer class="p-12">
      <b-row>
        <b-col v-if="!loading && !visualizar" md="3" sm="12" key="new_p1">
          <b-button block variant="primary" :disabled="disabled" v-on:click="onSubmit">
            <em class="fa fa-plus"></em>&nbsp;
            <span>Salvar</span>
          </b-button>
        </b-col>
        <b-col v-else-if="!visualizar" md="3" sm="12">
          <b-button block variant="primary" :disabled="true" v-on:click="onSubmit">
            <b-spinner small type="grow"></b-spinner>Salvando...
          </b-button>
        </b-col>
        <b-col md="3" sm="12">
          <b-button block variant="secondary" :disabled="disabled" @click="voltar">
            <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
          </b-button>
        </b-col>
      </b-row>
    </b-card-footer>
  </b-card>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength } from 'vuelidate/lib/validators'

export default {
  name: 'ArquivoClassificacaoEstadualForm',
  components: {},
  mixins: [validationMixin],
  validations: {
    form: {
      nome: {
        required,
        csv: value => /\.csv$/.test(value),
        validCharacters: value => /^[^\\/:*?"<>|]+$/.test(value),
        maxLength: maxLength(255)
      },
      arquivo: {
        required
      },
      cronograma_id: {
        required
      }
    }
  },
  created () {
    if (this.$route.path.includes('cadastrar') && !this.can('arquivoClassificacao.store')) {
      this.$router.push('/home')
    }

    if (this.$route.path.includes('editar') && !this.can('arquivoClassificacao.update')) {
      this.$router.push('/home')
    }

    if (this.$route.path.includes('visualizar')) {
      if (!this.can('arquivoClassificacao.show')) {
        this.$router.push('/home')
      }

      this.visualizar = true
    }

    if (this.$route.params.id !== undefined) {
      this.load(this.$route.params.id)
    } else {
      this.form.cronograma_id = this.$route.params.cronograma
    }
  },
  data () {
    return {
      loading: false,
      tempFile: null,
      form: {
        nome: null,
        arquivo: null
      },
      visualizar: false,
      disabled: false,
      saving: false
    }
  },
  computed: {
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      this.$http.get(`api/arquivo-classificacao/estadual/${id}`).then(response => {
        this.form = response.body.data
      })
    },
    uploadDocumento (event) {
      let arquivo = event.target.files || event.dataTransfer.files
      if (!arquivo.length) {
        return false
      }

      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.resetFields()
        return false
      }

      if (!this.$validMimeType(arquivo[0].type, ['text/csv'])) {
        this.erro('Extensão do arquivo inválida.')
        this.resetFields()
        return false
      }

      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.arquivo = evento.target.result
        this.form.nome = arquivo[0].name
        this.form.extensao = arquivo[0].type
      }

      reader.readAsDataURL(arquivo[0])
    },
    salvar () {
      this.loading = true
      if (this.form.id) {
        this.$http.put('api/arquivo-classificacao/estadual/' + this.form.id, this.form).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            setTimeout(() => {
              this.navigate(`/arquivos-classificacao-estadual/${this.$route.params.cronograma}`)
            }, 3000)
          } else {
            this.resetFields()
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      } else {
        this.$http.post('api/arquivo-classificacao/estadual', this.form).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            setTimeout(() => {
              this.navigate(`/arquivos-classificacao-estadual/${this.$route.params.cronograma}`)
            }, 3000)
          } else {
            this.resetFields()
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
    onSubmit () {
      this.saving = true
      if (this.validate()) {
        this.confirmar()
      }
    },
    chkState (val) {
      const field = this.$v.form[val]
      if (!field.$model && !this.saving) {
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
    resetFields () {
      this.tempFile = null
      this.form.nome = null
      this.form.arquivo = null
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
            this.$router.push(`/arquivos-classificacao-estadual/${this.$route.params.cronograma}`)
          }
        })
      } else {
        this.$router.push(`/arquivos-classificacao-estadual/${this.$route.params.cronograma}`)
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
    }
  }
}
</script>
