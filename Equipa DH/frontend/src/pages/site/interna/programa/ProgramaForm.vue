<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Políticas Públicas</strong></h1>
            <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Políticas Públicas</strong></h1>
            <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Políticas Públicas</strong></h1>
            <b-row>
              <b-col md="8" sm="12">
                <b-form-group tabindex="0" label="Nome da Política Pública: *">
                  <b-input-group>
                    <b-form-input type="text" class="form-control" placeholder="" v-model.trim="form.nome"
                      :state="chkState('nome')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0" label="Descrição: *">
                  <vue-editor :editor-toolbar="customEditorToolbar" v-model="form.descricao"
                    :disabled="visualizar"></vue-editor>
                  <!-- <b-form-textarea v-model="form.descricao" :rows="5" :max-rows="5" :state="chkState('descricao')" :disabled="visualizar"></b-form-textarea> -->
                  <b-form-invalid-feedback :state="chkState('descricao')">Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="6" sm="12">
                <b-form-group tabindex="0" label="Unidade: *">
                  <b-form-select v-model="form.unidade_id" :state="chkState('unidade_id')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in unidades" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('unidade_id')">Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0" label="Texto de Confirmação de Adesão: *">
                  <b-form-textarea v-model="form.texto_confirmacao" :rows="5" :max-rows="5"
                    :state="chkState('texto_confirmacao')" :disabled="visualizar"></b-form-textarea>
                  <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="6" sm="12">
                <label tabindex="0">Logomarca:</label>
                <span class="icon-info" v-b-popover.hover.top="'JPG, JPEG e PNG'" title="Extensões aceitas"
                  v-if="!visualizar"></span>
                <b-form-group label-for="nome-input" v-if="!visualizar">
                  <b-form-file ref="docLogomarca" class="fileInput" accept=".png, .jpeg, .jpg"
                    v-on:change="uploadDocumentoLogo" v-model="form.logomarca" browse-text="Selecione"
                    placeholder="Upload" :state="chkState('logomarca')"></b-form-file>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
                <img v-if="form.logomarca" :src="logoIMG" class="img-fluid" alt="Logomarca" />
              </b-col>
            </b-row>
          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col v-if="!loading && !visualizar" md="3" sm="12" key="new_p1">
              <b-button block variant="primary" :disabled="disable_buttons" v-on:click="onSubmit">
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
import { required, minLength, email, requiredIf, maxLength, sameAs } from 'vuelidate/lib/validators'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker';
import { ptBR } from 'vuejs-datepicker/dist/locale'
import { VueEditor } from "vue2-editor"
import { cpf } from 'cpf-cnpj-validator';
import moment from 'moment'
import SessionStorage from '@/services/session-storage'

miniToastr.init()

export default {
  name: 'Editar',
  components: {
    VueSelect,
    Multiselect,
    VueEditor,
    Datepicker
  },
  mixins: [validationMixin],
  validations: {
    form: {
      nome: {
        required
      },
      descricao: {
        required
      },
      texto_confirmacao: {
        required
      },
      logomarca: {},
      unidade_id: {
        required
      }
    }
  },
  data () {
    return {
      loading: false,
      form: {
        id: null,
        nome: null,
        descricao: null,
        texto_confirmacao: null,
        logomarca: null,
        unidade_id: null,
        logomarca_view: null
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
    },
    unidades () {
      return this.$store.state.unidade.lista
    },
    logoIMG () {
      if (this.form.id && this.form.logomarca && this.form.logomarca.includes('documentos/programas')) {
        return process.env.ROOT_API1 + this.form.logomarca
      } else if (!this.form.logomarca.includes('documentos/programas') && this.form.logomarca) {
        return this.form.logomarca
      }
    }
  },
  mounted () {
    if (this.$route.params.id !== undefined && this.$route.params.visualizar !== undefined) {
      this.visualizar = true
      this.form.id = this.$route.params.id
      this.load(this.$route.params.id)
    }

    if (this.$route.params.id !== undefined && this.$route.params.visualizar === undefined) {
      this.editar = true
      this.form.id = this.$route.params.id
      this.load(this.$route.params.id)
    }

    this.$store.dispatch('getUnidades')
  },
  watch: {
    'form.unidade_id': function (val) {
      if (!this.loading && val) {
        let nome = 'Nome da Política Pública'
        if (this.form.nome) {
          nome = this.form.nome
        }

        let unidade = { nome: 'Nome da Unidade' }
        if (val) {
          unidade = this.unidades.find(item => item.id === val)
        }

        this.templateTextoConfirmacao(unidade, nome)
      }
    },
    'form.nome': function (val) {
      if (!this.loading && val) {
        let unidade = { nome: 'Nome da Unidade' }
        if (this.form.unidade_id) {
          unidade = this.unidades.find(item => item.id === this.form.unidade_id)
        }

        let nome = 'Nome da Política Pública'
        if (val) {
          nome = val
        }

        this.templateTextoConfirmacao(unidade, nome)
      }
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      this.loading = true
      Vue.http.get('api/programa/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.nome = data.nome
        this.form.descricao = data.descricao
        this.form.texto_confirmacao = data.texto_confirmacao
        this.form.logomarca = data.logomarca
        this.form.unidade_id = data.unidade_id

        this.$nextTick(() => {
          this.loading = false
        })
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        nome: this.form.nome,
        descricao: this.form.descricao,
        texto_confirmacao: this.form.texto_confirmacao,
        logomarca: this.form.logomarca,
        unidade_id: this.form.unidade_id
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/programa/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false

            let t = this
            setTimeout(function () {
              t.navigate('/programas')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.erro(e.body.msg)
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/programa', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)

            let t = this
            setTimeout(function () {
              t.navigate('/programas')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.erro(e.body.msg)
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
    uploadDocumentoLogo (evento) {
      let arquivo = evento.target.files || evento.dataTransfer.files
      if (!arquivo.length) {
        return false
      }
      if (arquivo[0].size > 15728640) {
        this.erro('Tamanho do arquivo maior que 15MB.')
        this.$refs.docLogomarca.reset()
        return false
      }
      let reader = new FileReader()
      reader.onload = (evento) => {
        this.form.logomarca = evento.target.result
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
    templateTextoConfirmacao (unidade, nome) {
      this.form.texto_confirmacao = `A ${unidade.nome}, no uso das atribuições que lhe foram conferidas pela Portaria nº XX, de XX de XXXXXXXX de 20XX e conforme art. 13, inciso I da Portaria nº 222, de 3 de abril de 2024, resolve tornar público o CRONOGRAMA DE PARTICIPAÇÃO da política pública ${nome} no Programa de Equipagem, de Modernização da Infraestrutura e de Apoio ao Funcionamento dos Órgãos, das Entidades e das Instâncias Colegiadas Atuantes na Promoção e na Defesa dos Direitos Humanos - Programa EquipaDH+, instituído no Decreto nº 11.919, de 14 de fevereiro de 2024.`
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