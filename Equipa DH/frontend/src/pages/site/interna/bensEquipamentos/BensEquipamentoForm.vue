<template>
    <span>
      <b-col md="12" sm="12">
        <b-card no-body>
          <b-card-body>
            <b-form @submit.prevent="onSubmit">
              <h1 tabindex="0" v-if="!form.id"><strong>Cadastrar Bens e Equipamentos</strong></h1>
              <h1 tabindex="0" v-if="form.id && !visualizar"><strong>Editar Bem</strong></h1>
              <h1 tabindex="0" v-if="visualizar"><strong>Visualizar Bem</strong></h1>
  
              <b-row>
                <b-col md="6" sm="12">
                  <b-form-group label="Nome do Objeto: *">
                    <b-form-input v-model.trim="form.nome" :state="chkState('nome')" :disabled="visualizar" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-form-group>
                </b-col>
                <b-col md="6" sm="12">
                  <b-form-group label="Categoria: *">
                    <b-form-select
                      v-model.trim="form.categoria"
                      :state="chkState('categoria')"
                      :disabled="visualizar"
                      :options="categorias"
                    />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-form-group>
                </b-col>
              </b-row>
  
              <b-row>
                <b-col md="12" sm="12">
                  <b-form-group label="Descrição: *">
                    <b-form-textarea v-model.trim="form.descricao" :state="chkState('descricao')" :disabled="visualizar" rows="3" />
                    <b-form-invalid-feedback>Campo obrigatório!</b-form-invalid-feedback>
                  </b-form-group>
                </b-col>
              </b-row>
  
              <b-row>
                <b-col md="3" sm="12" v-if="!loading && !visualizar">
                  <b-button block variant="primary" @click="onSubmit">
                    <em class="fa fa-save"></em> Salvar
                  </b-button>
                </b-col>
                <b-col md="3" sm="12">
                  <b-button block variant="secondary" @click="voltar">
                    <em class="fa fa-mail-reply-all"></em> Voltar
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
          </b-card-body>
        </b-card>
      </b-col>
    </span>
  </template>
  
  <script>
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'
  
  
  export default {
    name: 'BensEquipamentoForm',
    mixins: [validationMixin],
    data () {
      return {
        form: {
          id: null,
          nome: '',
          categoria: '',
          descricao: '',
          situacao: true
        },
        categorias: [
          'Acessibilidade',
          'Equipamentos de TIC',
          'Eletrodomésticos',
          'Eletrônicos',
          'Embarcações Náuticas', 
          'Mobiliários',
          'Veículos terrestres'
        ],
        visualizar: false,
        loading: false
      }
    },
    validations: {
      form: {
        nome: { required },
        categoria: { required },
        descricao: { required }
      }
    },
    mounted () {
      const { id, visualizar } = this.$route.params
  
      if (visualizar !== undefined) {
        this.visualizar = true
      }
  
      if (id !== undefined) {
        this.form.id = id
        this.load(id)
      }
    },
    methods: {
      load (id) {
        this.loading = true
        this.$http.get(`api/bens-equipamentos/${id}`).then(response => {
          Object.assign(this.form, response.body.data)
          this.loading = false
        }).catch(() => {
          this.loading = false
          this.$bvToast.toast('Erro ao carregar dados do bem.', {
            title: 'Erro',
            variant: 'danger',
            autoHideDelay: 5000
          })
        })
      },
      onSubmit () {
        this.$v.$touch()
        if (this.$v.$invalid) return
  
        const request = this.form.id
          ? this.$http.put(`api/bens-equipamentos/${this.form.id}`, this.form)
          : this.$http.post('api/bens-equipamentos', this.form)
  
        request.then(response => {
          if (response.body.success) {
            this.$bvToast.toast(response.body.msg, {
              title: 'Sucesso',
              variant: 'success',
              autoHideDelay: 5000
            })
            this.$router.push('/bens-equipamentos')
          }
        }).catch(err => {
          this.$bvToast.toast(err.body.msg || 'Erro ao salvar', {
            title: 'Erro',
            variant: 'danger',
            autoHideDelay: 5000
          })
        })
      },
      voltar () {
        this.$router.go(-1)
      },
      chkState (campo) {
        const field = this.$v.form[campo]
        return field.$dirty ? !field.$invalid : null
      }
    }
  }
  </script>
  
  <style scoped>
  </style>
  