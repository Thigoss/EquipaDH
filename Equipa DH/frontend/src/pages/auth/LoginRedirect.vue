<template>
  <auth-template>
    <b-col md="8">
      <b-card-group>
        <b-card no-body class="p-4">
          <b-card-body>
            <b-form>
              <h1>Autenticando...</h1>
            </b-form>
          </b-card-body>
        </b-card>
      </b-card-group>
    </b-col>
  </auth-template>
</template>

<script>
import Vue from 'vue'
import AuthTemplate from '@/templates/auth/AuthTemplate'

export default {
  name: 'Login',
  components: {
    AuthTemplate
  },
  data () {
    return {
      usuario: {
        cpf: '',
        senha: ''
      },
      logando: false,
      titulo_toast: 'Alerta!',
      chavaCaptcha: process.env.KEY_RECAPTCHA
    }
  },
  created () {
    var urlString = window.location.href
    var url = new URL(urlString)
    var code = url.searchParams.get('code')

    Vue.http.post('api/auth/' + code).then(response => {
      this.$store.dispatch('login', response.data.data).then((response) => {
        this.$startSessionTimer()
        this.$addSessionTimerEvents()
        this.$router.push('/home')
      }).catch(e => {
        console.log('Servidor está fora! ' + e)
      })
    }).catch(e => {
      console.log(e)
      this.$bvToast.toast(e.body.msg, {
        title: 'Erro',
        autoHideDelay: 10000,
        appendToast: true,
        variant: 'danger'
      })

      var thing = this
      setTimeout(function () {
        thing.$router.push('/')
      }, 3000)
    })
  },
  methods: {
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
