<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form>
            <h1 tabindex="0">Analisar Solicitação de Recurso de Adesão</h1>
            <b-row>
              <b-col md="4" sm="12">
                <p><strong>Solicitação: </strong>{{ form.id }}</p>
              </b-col>
              <b-col md="4" sm="12" v-if="cronograma !== null">
                <p><strong>Nº do cronograma: </strong>{{ cronograma.numero }}</p>
              </b-col>
              <b-col md="4" sm="12">
                <strong>Situação: </strong>
                <b-badge v-if="form.situacao == 'PE'" variant="secondary">Pendente</b-badge>
                <b-badge v-if="form.situacao == 'DV'" variant="warning">Devolvido</b-badge>
                <b-badge v-if="form.situacao == 'RE'" variant="danger">Recusado</b-badge>
                <b-badge v-if="form.situacao == 'AP'" variant="success">Aprovado</b-badge>
                <b-badge v-if="form.situacao == 'CV'" variant="warning">Convocado</b-badge>
              </b-col>
            </b-row>
            <b-row>
              <b-col lg="4" md="5" sm="12">
                <label tabindex="0"><b>Declaração Unificada:</b></label>
                <b-button v-if="form.arquivo_download" block variant="primary"
                  @click="$abrirDocumento('adesao_declaracao', form.id)">
                  <em class="fa fa-download"></em>&nbsp;
                  Declaração Unificada
                </b-button>
                <span v-else>
                  <br>
                  Não há declaração unificada.
                </span>
              </b-col>
              <b-col lg="4" md="5" sm="12">
                <label tabindex="0"><b>Formulário de Habilitação:</b></label>
                <b-button v-if="form.formulario_habilitacao" block variant="primary"
                  @click="$abrirDocumento('adesao_formulario_habilitacao', form.id)">
                  <em class="fa fa-download"></em>&nbsp;
                  Formulário de Habilitação
                </b-button>
                <span v-else>
                  <br>
                  Não há formulário de habilitação.
                </span>
              </b-col>
            </b-row>
            <br>
            <b-row v-if="cronograma !== null">
              <b-col md="12" sm="12">
                <b-form-group label-for="nome-input">
                  <b-form-checkbox v-model="form.declaracao" name="check-button" style="text-align: justify;" disabled>
                    {{ cronograma && cronograma.programa ? cronograma.programa.texto_confirmacao : 'Aceito as condições do cronograma.'}}
                  </b-form-checkbox>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>

            <span style="display: block; background-color: #f8f9fa;" v-if="recursoAdesao">
              <b-row>
                <b-col md="12" sm="12">
                  <p><strong>Analisar Recurso</strong></p>
                </b-col>
              </b-row>
              <b-row>
                <b-col md="12" sm="12">
                  <b-form-group>
                    {{ recursoAdesao ? recursoAdesao.justificativa : '' }}
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row>
                <b-col md="12" sm="12">
                  <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                    class="table table-responsive-sm table-bordered table-striped table-sm">
                    <thead>
                      <tr>
                        <th tabindex="0" scope="col">Arquivo</th>
                        <th tabindex="0" scope="col" style="width: 10%">Ação</th>
                      </tr>
                    </thead>
                    <tbody v-if="recursoAdesao && recursoAdesao.arquivos.length > 0">
                      <tr class="action" v-for="arquivo in recursoAdesao.arquivos" :key="arquivo.id">
                        <td tabindex="0">{{ arquivo.nome }}</td>
                        <td tabindex="0">
                          <b-button @click="$abrirDocumento('adesao_recurso_arquivo', arquivo.id)">
                            <em class="fa fa-download"></em>&nbsp;
                            Download
                          </b-button>
                        </td>
                      </tr>
                    </tbody>
                    <tbody v-else>
                      <tr class="action">
                        <td tabindex="0" colspan="2">Nenhum arquivo inserido</td>
                      </tr>
                    </tbody>
                  </table>
                </b-col>
              </b-row>
            </span>

          </b-form>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col v-if="can('adesao.aprovar')" md="3" sm="12">
              <b-button block variant="success" v-on:click="modalAprovar">
                <em class="fa fa-check"></em>&nbsp;
                <span>Aprovar</span>
              </b-button>
            </b-col>
            <b-col v-if="can('adesao.devolver')" md="3" sm="12">
              <b-button block variant="warning" v-on:click="modalDevolver">
                <em class="fa fa-ban"></em>&nbsp;
                <span>Devolver</span>
              </b-button>
            </b-col>
            <b-col v-if="can('adesao.recusar')" md="3" sm="12">
              <b-button block variant="danger" v-on:click="modalReprovar">
                <em class="fa fa-x"></em>&nbsp;
                <span>Recusar</span>
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

      <b-modal @cancel="limparModalAvaliacao" :hide-header-close="true" no-close-on-backdrop no-close-on-esc
        :hide-footer="true" cancel-title="Voltar" centered v-model="modalAvaliacao" size="lg"
        :title="titleModalAvaliacao">
        <b-container fluid>
          <b-row>
            <b-col cols="6">
              <strong>Solicitação: </strong>{{ form.id }}
            </b-col>
          </b-row>

          <hr v-if="form_avaliacao.situacao !== 'AP'">
          <b-row v-if="form_avaliacao.situacao !== 'AP'">
            <b-col cols="12">
              <b-form-group tabindex="0" label="Justificativa: *">
                <b-form-textarea v-model="form_avaliacao.observacao" :rows="5" :max-rows="5"></b-form-textarea>
              </b-form-group>
            </b-col>
          </b-row>

          <hr>
          <b-row>
            <b-col cols="4">
              <b-button block variant="primary" :disabled="disable_buttons" @click="avaliar">
                <em class="fa fa-plus"></em>&nbsp;
                <span>Salvar</span>
              </b-button>
            </b-col>
            <b-col cols="4">
              <b-button block variant="secondary" :disabled="disable_buttons" @click="limparModalAvaliacao">
                <em class="fa fa-close"></em>&nbsp;
                <span>Fechar</span>
              </b-button>
            </b-col>
          </b-row>
        </b-container>
      </b-modal>

    </b-col>
  </span>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'

miniToastr.init()

export default {
  name: 'Editar',
  components: {
    VueSelect,
    Multiselect,
    Datepicker
  },
  data () {
    return {
      loading: false,
      form: {
        id: null,
        cronograma_id: null,
        arquivo: null,
        formulario_habilitacao: null,
        arquivo_download: null,
        declaracao: null,
        situacao: null
      },
      recursoAdesao: null,
      titleModalAvaliacao: '',
      modalAvaliacao: false,
      form_avaliacao: {
        id: null,
        situacao: null,
        observacao: null
      },
      configuracao: {},
      cronograma: null,
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
    this.loadRecursoAdesao(this.$route.params.id)
    this.getConfiguracaoGeral()
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
        this.form.arquivo_download = data.arquivo
        this.form.formulario_habilitacao = data.formulario_habilitacao
        this.form.declaracao = true

        this.historicos = data.historico
        this.loadCronograma(data.cronograma_id)
      })
    },
    loadRecursoAdesao (id) {
      Vue.http.get('api/adesao/' + id + '/recurso-adesao').then(response => {
        let data = response.body.data
        this.recursoAdesao = data.length !== 0 ? data : null
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
    modalAprovar () {
      this.modalAvaliacao = true
      this.titleModalAvaliacao = 'Aprovação da Solicitação'
      this.form_avaliacao.id = this.form.id
      this.form_avaliacao.situacao = 'AP'
      this.form_avaliacao.observacao = null
    },
    modalDevolver () {
      this.modalAvaliacao = true
      this.titleModalAvaliacao = 'Devolver Solicitação'
      this.form_avaliacao.id = this.form.id
      this.form_avaliacao.situacao = 'DV'
      this.form_avaliacao.observacao = null
    },
    modalReprovar () {
      this.modalAvaliacao = true
      this.titleModalAvaliacao = 'Reprovar Solicitação'
      this.form_avaliacao.id = this.form.id
      this.form_avaliacao.situacao = 'RE'
      this.form_avaliacao.observacao = null
    },
    avaliar () {
      this.$bvModal.msgBoxConfirm('Tem certeza que deseja continuar?', {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          let url = ''
          if (this.form_avaliacao.situacao == 'AP') {
            url = 'api/adesao/' + this.form_avaliacao.id + '/aprovada'
          }

          if (this.form_avaliacao.situacao == 'DV') {
            url = 'api/adesao/' + this.form_avaliacao.id + '/devolvida'
          }

          if (this.form_avaliacao.situacao == 'RE') {
            url = 'api/adesao/' + this.form_avaliacao.id + '/recusada'
          }

          Vue.http.put(url, { observacao: this.form_avaliacao.observacao }).then(response => {
            if (response.body.success) {
              this.modalAvaliacao = false
              this.sucesso(response.body.msg)
              this.load(this.form.id)
              setTimeout(() => {
                this.navigate('/adesao')
              }, 3000)
            } else {
              this.erro(response.body.msg)
            }
          })
        }
      }).catch(erro => {
        console.log(erro)
      })
    },
    limparModalAvaliacao () {
      this.modalAvaliacao = false
      this.form_avaliacao.id = null
      this.form_avaliacao.situacao = null
      this.form_avaliacao.observacao = null
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
