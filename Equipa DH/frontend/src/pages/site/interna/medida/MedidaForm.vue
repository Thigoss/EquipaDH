<template>
  <span>
    <b-col md="12" sm="12">
      <b-card no-body>
        <b-card-body>
          <b-form v-on:submit.prevent="onSubmit">
            <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Medidas de Proteção</strong></h1>
            <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Medidas de Proteção</strong></h1>
            <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Medidas de Proteção</strong></h1>
            <br />
            <b-row>
              <b-col md="12" sm="12">
                <b-form-group tabindex="0" label="Nome:">
                  <b-input-group class="mb-3">
                    <b-form-input type="text" class="form-control" placeholder="" autocomplete="nome"
                      :state="chkState('nome')" v-model="$v.form.nome.$model" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12">
                <b-form-group label="Descrição:">
                  <b-input-group class="mb-12">
                    <b-form-textarea v-model.trim="$v.form.descricao.$model" rows="5" :state="chkState('descricao')"
                      max-rows="6" :disabled="visualizar"></b-form-textarea>
                    <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                  </b-input-group>
                </b-form-group>
              </b-col>
            </b-row>
            <b-row>
              <b-col md="4" sm="12">
                <b-form-group tabindex="0" label="Grupo de Medida : *">
                  <b-form-select v-model="$v.form.grupo_medida_id.$model" placeholder="Selecione" class="mb-3"
                    :state="chkState('grupo_medida_id')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in gruposMedidas" :key="item.id" :value="item.id">{{ item.nome }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Áreas Temáticas: *">
                  <multiselect v-model="$v.form.areasTematicas.$model" :options="areasTematicas" label="descricao"
                    track-by="id" placeholder="Selecione" selectLabel="" deselectLabel="" selectedLabel=""
                    :tagglabe="true" :multiple="true" :disabled="visualizar">
                    <span slot="maxElements"></span>
                    <span slot="noResult">Nenhum encontrado</span>
                    <span slot="noOptions">Lista vazia</span>
                  </multiselect>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>
              <b-col cols="4">
                <b-form-group tabindex="0" label="Necessita de um órgão ou sistema de garantia de direitos?">
                  <b-form-select v-model="$v.form.necessita_orgao.$model" placeholder="Selecione" class="mb-3"
                    :state="chkState('necessita_orgao')" :disabled="visualizar">
                    <option selected :value="null">Selecione</option>
                    <option v-for="item in options" :key="item.value" :value="item.value">{{ item.text }}</option>
                  </b-form-select>
                  <b-form-invalid-feedback>Campo obrigatório.</b-form-invalid-feedback>
                </b-form-group>
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
import { cpf } from 'cpf-cnpj-validator';
import moment from 'moment'

miniToastr.init()

const validCpf = (value) => {
  if (value) {
    if (cpf.isValid(value)) {
      return true
    } else {
      return false
    }
  } else {
    return true
  }
}

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
      nome: {
        required
      },
      descricao: {
        required
      },
      grupo_medida_id: {
        required
      },
      necessita_orgao: {
        required
      },
      areasTematicas: {}
    }
  },
  created () {
    this.$store.dispatch('getGruposMedidas')
    this.$store.dispatch('getAreaTematicas')
  },
  data () {
    return {
      btnAvaliar: false,
      editarCepIntituicao: 1,
      trocaUnidade: 1,
      loadingModal: false,
      avaliar: true,
      titleModal: '',
      esconder_footer: false,
      exibeMotivo: false,
      modalPreAprovar: false,
      disable_aprovar: true,
      documento_rg: false,
      documento_cpf: false,
      documento_comprovante: false,
      editar: false,
      editarCep: 1,
      loading: false,
      form: {
        id: '',
        nome: null,
        descricao: null,
        grupo_medida_id: null,
        necessita_orgao: null,
        areasTematicas: [],
        ativo: false
      },
      options: [
        { text: 'Não', value: false },
        { text: 'Sim', value: true }
      ],
      visualizar: false,
      disable_buttons: false,
      salvarForm: false,
      salvarFormResponsavel: false,
    }
  },
  computed: {
    gruposMedidas () {
      return this.$store.state.grupoMedida.lista
    },
    areasTematicas () {
      return this.$store.state.areaTematica.lista
    },
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  watch: {
    '$v.form.direito_fundamental_id.$model': function () {
      this.$store.dispatch('getGruposDireitos', this.form.direito_fundamental_id)
    }
  },
  mounted () {
    if (this.$route.params.id !== undefined && this.$route.params.visualizar !== undefined) {
      this.visualizar =
        this.form.id = this.$route.params.id
      this.load(this.$route.params.id)

    } else if (this.$route.params.id !== undefined) {
      this.form.id = this.$route.params.id
      this.load(this.$route.params.id)
      this.editar = true
    }

    if (!this.form.id && !this.can('medida.store')) {
      this.$router.push('/home')
    }

    if ((this.form.id && !this.visualizar) && !this.can('medida.update')) {
      this.$router.push('/home')
    }

    if ((this.form.id && this.visualizar) && !this.can('medida.show')) {
      this.$router.push('/home')
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },

    // Carregamento da medida
    load (id) {
      Vue.http.get('api/medida/' + id).then(response => {
        let data = response.body.data

        this.form.id = data.id
        this.form.nome = data.nome
        this.form.descricao = data.descricao
        this.form.grupo_medida_id = data.grupo_medida_id
        this.form.necessita_orgao = data.necessita_orgao
        this.form.ativo = data.ativo

        for (let i in data.areas_tematicas) {
          this.form.areasTematicas.push({
            'id': data.areas_tematicas[i].area_tematica_id,
            'descricao': data.areas_tematicas[i].area_tematica.descricao,
          })
        }
      })
    },
    salvar () {
      let data = {
        id: this.form.id,
        nome: this.form.nome,
        descricao: this.form.descricao,
        grupo_medida_id: this.form.grupo_medida_id,
        necessita_orgao: this.form.necessita_orgao,
        ativo: this.form.ativo,
        areasTematicas: JSON.stringify(this.form.areasTematicas)
      }

      this.loading = true
      if (data.id) {
        Vue.http.put('api/medida/' + data.id, data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false

            let t = this
            setTimeout(function () {
              t.navigate('/medidas')
            }, 3000)

          } else {
            this.erro(response.body.msg)
            this.loading = false
          }
        }).catch(e => {
          this.loading = false
          console.log('Servidor está fora! ' + e)
        })
      } else {
        Vue.http.post('api/medida', data).then(response => {
          if (response.body.success) {
            this.sucesso(response.body.msg)
            this.loading = false

            let t = this
            setTimeout(function () {
              t.navigate('/medidas')
            }, 3000)
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
            this.$router.push('/medidas')
          }
        })
      } else {
        this.$router.push('/medidas')
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