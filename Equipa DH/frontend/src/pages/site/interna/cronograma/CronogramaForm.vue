<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Cronograma</strong></h1>
            <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Cronograma</strong></h1>
            <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Cronograma</strong></h1>
            <b-row>
              <b-col cols="12">
                <strong>Dados pessoais</strong>
                <br />
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col cols="6">
                <b-form-group tabindex="0" label="Política Pública: *">
                  <b-form-select v-model="form.programa_id" :state="chkState('programa_id')" :disabled="visualizar">
                    <option selected :value="''"> Selecione </option>
                    <option v-for="programa in programas" :value="programa.id" :key="programa.id"> {{ programa.nome }}
                    </option>
                  </b-form-select>
                  <b-form-invalid-feedback :state="chkState('programa_id')">Campo obrigatório!</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>

            <b-row>
              <b-col cols="12">
                <strong>Publicação do cronograma</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_publicacao_inicio')"
                      type="date" v-model="form.data_publicacao_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <!-- <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_publicacao_fim')" type="date" v-model="form.data_publicacao_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col> -->
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Período de Adesão</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_adesao_inicio')"
                      type="date" v-model="form.data_adesao_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_adesao_fim')" type="date"
                      v-model="form.data_adesao_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Divulgação preliminar das adesões:</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar"
                      :state="chkState('data_divulgacao_adesao_inicio')" type="date"
                      v-model="form.data_divulgacao_adesao_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <!-- <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_divulgacao_adesao_fim')" type="date" v-model="form.data_divulgacao_adesao_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col> -->
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Prazo para recurso de Adesões:</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_recurso_adesao_inicio')"
                      type="date" v-model="form.data_recurso_adesao_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_recurso_adesao_fim')"
                      type="date" v-model="form.data_recurso_adesao_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Divulgação preliminar da lista de habilitados e classificados:</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar"
                      :state="chkState('data_divulgacao_habilitados_inicio')" type="date"
                      v-model="form.data_divulgacao_habilitados_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <!-- <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_divulgacao_habilitados_fim')" type="date" v-model="form.data_divulgacao_habilitados_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col> -->
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Prazo para recurso de habilitados e classificados:</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar"
                      :state="chkState('data_recurso_habilitados_inicio')" type="date"
                      v-model="form.data_recurso_habilitados_inicio">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar"
                      :state="chkState('data_recurso_habilitados_fim')" type="date"
                      v-model="form.data_recurso_habilitados_fim">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <br />

            <b-row>
              <b-col cols="12">
                <strong>Divulgação da lista de habilitados e classificados:</strong>
                <br />
              </b-col>
              <b-col md="4" sm="12">
                <b-form-group>
                  <b-input-group>
                    <b-form-input id="type-date" :disabled="visualizar" :state="chkState('data_divulgacao_lista')"
                      type="date" v-model="form.data_divulgacao_lista">
                    </b-form-input>
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <br />

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
import { cpf } from 'cpf-cnpj-validator';
import moment from 'moment'
import SessionStorage from '@/services/session-storage'

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
      programa_id: {
        required
      },
      data_publicacao_inicio: {
        required
      },
      data_publicacao_fim: {},
      data_adesao_inicio: {
        required
      },
      data_adesao_fim: {
        required
      },
      data_divulgacao_adesao_inicio: {},
      data_divulgacao_adesao_fim: {},
      data_recurso_adesao_inicio: {},
      data_recurso_adesao_fim: {
        required: requiredIf(function () {
          return this.form.data_recurso_adesao_inicio
        })
      },
      data_divulgacao_habilitados_inicio: {
        required
      },
      data_divulgacao_habilitados_fim: {

      },
      data_recurso_habilitados_inicio: {
        required
      },
      data_recurso_habilitados_fim: {
        required
      },
      data_divulgacao_lista: {
        required
      }
    }
  },
  created () {
    this.$store.dispatch('getProgramas')
  },
  data () {
    return {
      editar: false,
      loading: false,
      form: {
        id: null,
        programa_id: null,
        data_publicacao_inicio: null,
        data_publicacao_fim: null,
        data_adesao_inicio: null,
        data_adesao_fim: null,
        data_divulgacao_adesao_inicio: null,
        data_divulgacao_adesao_fim: null,
        data_recurso_adesao_inicio: null,
        data_recurso_adesao_fim: null,
        data_divulgacao_habilitados_inicio: null,
        data_divulgacao_habilitados_fim: null,
        data_recurso_habilitados_inicio: null,
        data_recurso_habilitados_fim: null,
        data_divulgacao_lista: null
      },
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      ptBR: ptBR
    }
  },
  computed: {
    programas () {
      return this.$store.state.programa.lista.filter(programa => programa.ativo === true)
    },
    isValid () {
      return !this.$v.form.$anyError
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

    if (!this.form.id && !this.can('cronograma.store')) {
      this.$router.push('/home')
    }

    if ((this.form.id && !this.visualizar) && !this.can('cronograma.update')) {
      this.$router.push('/home')
    }

    if ((this.form.id && this.visualizar) && !this.can('cronograma.show')) {
      this.$router.push('/home')
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    load (id) {
      Vue.http.get('api/cronograma/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.programa_id = data.programa_id
        this.form.data_publicacao_inicio = data.data_publicacao_inicio
        this.form.data_publicacao_fim = data.data_publicacao_fim
        this.form.data_adesao_inicio = data.data_adesao_inicio
        this.form.data_adesao_fim = data.data_adesao_fim
        this.form.data_divulgacao_adesao_inicio = data.data_divulgacao_adesao_inicio
        this.form.data_divulgacao_adesao_fim = data.data_divulgacao_adesao_fim
        this.form.data_recurso_adesao_inicio = data.data_recurso_adesao_inicio
        this.form.data_recurso_adesao_fim = data.data_recurso_adesao_fim
        this.form.data_divulgacao_habilitados_inicio = data.data_divulgacao_habilitados_inicio
        this.form.data_divulgacao_habilitados_fim = data.data_divulgacao_habilitados_fim
        this.form.data_recurso_habilitados_inicio = data.data_recurso_habilitados_inicio
        this.form.data_recurso_habilitados_fim = data.data_recurso_habilitados_fim
        this.form.data_divulgacao_lista = data.data_divulgacao_lista

      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        programa_id: this.form.programa_id,
        data_publicacao_inicio: this.form.data_publicacao_inicio,
        data_publicacao_fim: this.form.data_publicacao_fim,
        data_adesao_inicio: this.form.data_adesao_inicio,
        data_adesao_fim: this.form.data_adesao_fim,
        data_divulgacao_adesao_inicio: this.form.data_divulgacao_adesao_inicio,
        data_divulgacao_adesao_fim: this.form.data_divulgacao_adesao_fim,
        data_recurso_adesao_inicio: this.form.data_recurso_adesao_inicio,
        data_recurso_adesao_fim: this.form.data_recurso_adesao_fim,
        data_divulgacao_habilitados_inicio: this.form.data_divulgacao_habilitados_inicio,
        data_divulgacao_habilitados_fim: this.form.data_divulgacao_habilitados_fim,
        data_recurso_habilitados_inicio: this.form.data_recurso_habilitados_inicio,
        data_recurso_habilitados_fim: this.form.data_recurso_habilitados_fim,
        data_divulgacao_lista: this.form.data_divulgacao_lista
      }

      // valida se todas as datas estão em sequencia em caso de preenchimento delas
      // if (data.data_publicacao_inicio && data.data_publicacao_fim) {
      //   if (moment(data.data_publicacao_inicio).isAfter(data.data_publicacao_fim)) {
      //     this.erro('Data de publicação final deve ser maior que a data de publicação inicial')
      //     return
      //   }
      // }

      if (data.data_adesao_inicio && data.data_adesao_fim) {
        if (moment(data.data_adesao_inicio).isAfter(data.data_adesao_fim)) {
          this.erro('Data de adesão final deve ser maior que a data de adesão inicial')
          return
        }
      }

      // if (data.data_divulgacao_adesao_inicio && data.data_divulgacao_adesao_fim) {
      //   if (moment(data.data_divulgacao_adesao_inicio).isAfter(data.data_divulgacao_adesao_fim)) {
      //     this.erro('Data de divulgação de adesão final deve ser maior que a data de divulgação de adesão inicial')
      //     return
      //   }
      // }

      if (data.data_recurso_adesao_inicio && data.data_recurso_adesao_fim) {
        if (moment(data.data_recurso_adesao_inicio).isAfter(data.data_recurso_adesao_fim)) {
          this.erro('Data de recurso de adesão final deve ser maior que a data de recurso de adesão inicial')
          return
        }
      }

      // if (data.data_divulgacao_habilitados_inicio && data.data_divulgacao_habilitados_fim) {
      //   if (moment(data.data_divulgacao_habilitados_inicio).isAfter(data.data_divulgacao_habilitados_fim)) {
      //     this.erro('Data de divulgação de habilitados final deve ser maior que a data de divulgação de habilitados inicial')
      //     return
      //   }
      // }

      if (data.data_recurso_habilitados_inicio && data.data_recurso_habilitados_fim) {
        if (moment(data.data_recurso_habilitados_inicio).isAfter(data.data_recurso_habilitados_fim)) {
          this.erro('Data de recurso de habilitados final deve ser maior que a data de recurso de habilitados inicial')
          return
        }
      }

      if (data.data_divulgacao_lista) {
        if (moment(data.data_divulgacao_lista).isBefore(data.data_recurso_habilitados_fim)) {
          this.erro('Data de divulgação da lista deve ser maior que a data de recurso de habilitados final')
          return
        }
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/cronograma/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false
            let t = this
            setTimeout(function () {
              t.navigate('/cronograma')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/cronograma', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)

            let t = this
            setTimeout(function () {
              t.navigate('/cronograma')
            }, 3000)
          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
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
    }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>