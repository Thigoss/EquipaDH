<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0">Adesão</h1>
            <b-row>
              <b-col md="6" sm="12">
                <p><strong>Nº do cronograma: </strong>{{  cronograma.numero }}</p>
              </b-col>
              <b-col md="6" sm="12">
                <strong>Situação: </strong>
                <b-badge v-if="form.situacao == 'PE'" variant="secondary">Pendente</b-badge>
                <b-badge v-if="form.situacao == 'DV'" variant="warning">Devolvido</b-badge>
                <b-badge v-if="form.situacao == 'RE'" variant="danger">Recusado</b-badge>
                <b-badge v-if="form.situacao == 'AP'" variant="success">Aprovado</b-badge>
                <b-badge v-if="form.situacao == 'RC'" variant="warning">Recurso</b-badge>
              </b-col>
            </b-row>
            <br>
            
            <b-row>
              <b-col sm="12">
                <label tabindex="0">Declaração Unificada: *</label>
                <span class="icon-info" v-b-popover.hover.top="'DOC, DOCX e PDF'" title="Extensões aceitas"></span>
                <b-form-group label-for="nome-input">
                  <b-button block variant="primary" class="d-block d-md-none mb-2" v-if="form && form.arquivo" @click="$abrirDocumento('adesao_declaracao', form.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Documento Atual
                  </b-button>
                  <b-input-group>
                    <b-input-group-prepend class="d-none d-md-block" v-if="form.arquivo">
                      <b-button block variant="primary" @click="$abrirDocumento('adesao_declaracao', form.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Documento Atual
                      </b-button>
                    </b-input-group-prepend>
                    <b-form-file ref="docAdesao" class="fileInput" accept=".doc, .docx, .pdf" v-on:change="uploadAdesao" v-model="form.arquivo" browse-text="Selecione" placeholder="Upload" :state="chkState('arquivo')"></b-form-file>
                    <b-input-group-append class="d-none d-md-block" v-if="configuracao && configuracao.arquivo_ato_declaracao">
                      <b-button block variant="primary" @click="$abrirDocumento('config_arquivo_ato_declara', configuracao.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Download de documento Declaração Unificada
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-button block variant="primary" class="d-block d-md-none mt-2" v-if="configuracao && configuracao.arquivo_ato_declaracao" @click="$abrirDocumento('config_arquivo_ato_declara', configuracao.id)">
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
                  <b-button block variant="primary" class="d-block d-md-none mb-2" v-if="form && form.formulario_habilitacao" @click="$abrirDocumento('adesao_formulario_habilitacao', form.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Documento Atual
                  </b-button>
                  <b-input-group>
                    <b-input-group-prepend class="d-none d-md-block" v-if="form && form.formulario_habilitacao">
                      <b-button block variant="primary" @click="$abrirDocumento('adesao_formulario_habilitacao', form.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Documento Atual
                      </b-button>
                    </b-input-group-prepend>
                    <b-form-file ref="docHabilitacao" class="fileInput" accept=".doc, .docx, .pdf" v-on:change="uploadFormularioHabilitacao" v-model="form.formulario_habilitacao" browse-text="Selecione" placeholder="Upload" :state="chkState('formulario_habilitacao')"></b-form-file>
                    <b-input-group-append class="d-none d-md-block" v-if="configuracao && configuracao.arquivo_habilitacao">
                      <b-button block variant="primary" @click="$abrirDocumento('config_arquivo_habilitacao', configuracao.id)">
                        <em class="fa fa-download"></em>&nbsp;
                        Download de documento Formulário de Habilitação
                      </b-button>
                    </b-input-group-append>
                  </b-input-group>
                  <b-button block variant="primary" class="d-block d-md-none mt-2" v-if="configuracao && configuracao.arquivo_habilitacao" @click="$abrirDocumento('config_arquivo_habilitacao', configuracao.id)">
                    <em class="fa fa-download"></em>&nbsp;
                    Download de documento Formulário de Habilitação
                  </b-button>
                  <b-form-invalid-feedback :state="chkState('formulario_habilitacao')">Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <br>
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group label-for="nome-input">
                  <b-form-checkbox v-model="form.declaracao" name="check-button" :state="chkState('declaracao')" style="text-align: justify;">
                    {{ cronograma.programa.texto_confirmacao }}
                  </b-form-checkbox>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>

            <hr>
            <b-row>
              <b-col md="12" sm="12">
                <h3><strong>Histórico da Solicitação de Adesão</strong></h3>
              </b-col>
            </b-row>
            <!-- Lista de histórico em tabela -->
            <b-row>
              <b-col md="12">
                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7" class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Data</th>
                      <th tabindex="0" scope="col">Usuário</th>
                      <th tabindex="0" scope="col">Situação</th>
                      <th tabindex="0" scope="col">Justificativa</th>
                    </tr>
                  </thead>
                  <tbody v-if="historicos.length > 0">
                    <tr class="action" v-for="historico in historicos" :key="historico.id">
                      <td tabindex="0">{{ historico.created_at }}</td>
                      <td tabindex="0">{{ historico.user.nome }}</td>
                      <td tabindex="0">
                        <b-badge v-if="historico.situacao === 'PE'" variant="info">Pendente</b-badge>
                        <b-badge v-if="historico.situacao === 'AP'" variant="success">Aprovado</b-badge>
                        <b-badge v-if="historico.situacao === 'RE'" variant="danger">Recusado</b-badge>
                        <b-badge v-if="historico.situacao === 'DV'" variant="warning">Devolvido</b-badge>
                      </td>
                      <td tabindex="0">{{ historico.justificativa }}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="8">Nenhuma solicitação cadastrada</td>
                    </tr>
                  </tbody>
                </table>
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
import {validationMixin} from 'vuelidate'
import {required} from 'vuelidate/lib/validators'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import {ptBR} from 'vuejs-datepicker/dist/locale'

miniToastr.init()

export default {
  name: 'Editar',
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
      formulario_habilitacao: {
        required
      },
      declaracao: {
        required,
        confirmed () {
          return this.form.declaracao === true
        }
      }
    }
  },
  data () {
    return {
      loading: false,
      form: {
        id: null,
        cronograma_id: null,
        arquivo: null,
        formulario_habilitacao: null,
        declaracao: null,
        situacao: null
      },
      configuracao: {},
      cronograma: {},
      historicos: [],
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
    this.load(this.$route.params.id)
    this.getConfiguracaoGeral()
    /*if (!this.form.id && !this.can('solicitacao.store')) {
      this.$router.push('/home')
    }
       
    if ((this.form.id && !this.visualizar) && !this.can('solicitacao.update')) {
      this.$router.push('/home')
    }
   
    if ((this.form.id && this.visualizar) && !this.can('solicitacao.show')) {
      this.$router.push('/home')
    }*/
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      Vue.http.get('api/adesao/' + id).then(response => {
        let data = response.body.data
        this.form.id = data.id
        this.form.cronograma_id = data.cronograma_id
        this.form.situacao = data.situacao
        this.form.arquivo = data.arquivo
        this.form.formulario_habilitacao = data.formulario_habilitacao
        this.form.declaracao = true

        this.historicos = data.historico
        this.loadCronograma(data.cronograma_id)
      })
    },
    loadCronograma (id) {
      Vue.http.get('api/cronograma/' + id).then(response => {
        let data = response.body.data
        this.cronograma = data
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
      Vue.http.put('api/adesao/' + this.form.id, data).then(response => {
        if (response.body.success) {
          this.sucesso(response.body.msg)

          let t = this
          setTimeout(function () {
            t.navigate('/minhas-adesoes')
          }, 3000)
        } else {
          this.erro(response.body.msg)
          this.loading = false
        }
      }).catch(e => {
        this.loading = false
        this.erro(e.body.msg)
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