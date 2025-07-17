<template>
  <b-col md="12" sm="12">
    <b-card no-body class="mx-4">
      <b-card-body class="p-12">
        <b-form v-on:submit.prevent="onSubmit">
          <iframe title="iframe" :src="frameCadastrar" v-if="!id" style="width: 100%; border: 0; height: 800px;" />
          <iframe title="iframe" :src="frameEditar" v-if="id && !visualizar"
            style="width: 100%; border: 0; height: 800px;" />
          <iframe title="iframe" :src="frameVisualizar" v-if="visualizar"
            style="width: 100%; border: 0; height: 800px;" />
        </b-form>
      </b-card-body>
      <b-card-footer class="p-12">
        <b-row>
          <b-col md="3">
            <b-button block variant="secondary" :disabled="disable_buttons" @click="voltar">
              <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
            </b-button>
          </b-col>
        </b-row>
      </b-card-footer>
    </b-card>
  </b-col>
</template>

<script>
import Vue from 'vue'
import VueSelect from 'vue-select'
import { validationMixin } from 'vuelidate'
import { required, minLength, email } from 'vuelidate/lib/validators'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'

miniToastr.init()

export default {
  name: 'Editar',
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
      foto: {}
    }
  },
  created () {
    this.$store.dispatch('getPerfis')
  },
  data () {
    return {
      loading: false,
      id: null,
      frameCadastrar: false,
      frameEditar: false,
      frameVisualizar: false,
      visualizar: false,
      disable_buttons: false,
      salvarForm: false
    }
  },
  computed: {
    perfis () {
      return this.$store.state.perfil.lista
    },
    isValid () {
      return !this.$v.form.$anyError
    }
  },
  mounted () {
    if (this.$route.params.id === undefined) {
      this.loadFrameCadastro()
    }

    if (this.$route.params.id !== undefined && this.$route.params.visualizar === undefined) {
      this.id = this.$route.params.id
      this.loadFrameEditar()
    }

    if (this.$route.params.id !== undefined && this.$route.params.visualizar !== undefined) {
      this.visualizar = true
      this.id = this.$route.params.id
      this.loadFrameVisualizar()
    }

    if (!this.id && !this.can('perfil.storeFrame')) {
      this.$router.push('/home')
    }

    if ((this.id && !this.visualizar) && !this.can('perfil.updateFrame')) {
      this.$router.push('/home')
    }

    if ((this.id && this.visualizar) && !this.can('perfil.showFrame')) {
      this.$router.push('/home')
    }
  },
  methods: {
    abrirDocumento (path) {
      window.open(path, '_blank')
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    loadFrameCadastro () {
      Vue.http.get('api/perfil/store-external').then(response => {
        this.frameCadastrar = response.body.data.url
      })
    },
    loadFrameEditar () {
      Vue.http.get('api/perfil/update-external/' + this.$route.params.id).then(response => {
        this.frameEditar = response.body.data.url
      })
    },
    loadFrameVisualizar () {
      Vue.http.get('api/perfil/show-external/' + this.$route.params.id).then(response => {
        this.frameVisualizar = response.body.data.url
      })
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      this.$router.push('/perfis')
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
