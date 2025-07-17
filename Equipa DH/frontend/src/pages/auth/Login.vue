<template>
  <auth-template>
    <b-col md="8">
      <b-card-group>
        <b-card no-body class="p-4">
          <b-card-body>
            <b-form>
              <div style="margin-top: 3rem; margin-left: 2rem; margin-bottom: 3rem;">
                <a @click="loginGov">
                  <img tabindex="0" src="/static/img/btGovbr.png" alt="Entrar com gov.br" style="width: 80%;" />
                </a>
                <br><br>
                <b-button block variant="primary" @click="navigate('/solicitacao-credenciamento')" style="width: 80%;">
                  <span><strong>Credenciamento</strong></span>
                </b-button>
                <a @click="navigate('/apresentacao')" style="display: block; margin-top: 5px;">
                  <p><strong>Consulte aqui as Políticas Públicas</strong></p>
                </a>
              </div>
            </b-form>
          </b-card-body>
        </b-card>
        <b-card no-body class="text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <b-card-body class="text-center">
            <div>
              <h2><strong>EquipaDH+</strong></h2>
              <strong>Programa de Equipagem</strong>
              <br>
              <img tabindex="0" src="/static/img/logo_governo.png" alt="Logo MDHC" style="width: 100%;" />
            </div>
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
    }
  },
  methods: {
    navigate (route) {
      this.$router.push(route)
    },
    loginGov () {
      Vue.http.get('api/auth/login/uri').then(response => {
        location.href = response.body.data.url
      })
    }
  }
}
</script>

<style scoped>
a p {
  cursor: pointer;
  color: #294B92;
}
</style>
