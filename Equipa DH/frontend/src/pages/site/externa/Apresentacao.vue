<template>
  <span>
    <br>
    <b-col md="8" sm="12" offset-md="2">
      <b-card no-body>
        <b-card-body>
          <h1 tabindex="0"><strong>Equipa DH+</strong></h1>
          <br>

          <b-row>
            <b-col cols="12">
              <h2 tabindex="0"><strong>Orientações</strong></h2>
            </b-col>
          </b-row>
          <p><span v-html="orientacoes_credenciamento"></span></p>
          <p><span v-html="orientacoes"></span></p>
          <br>

          <b-row>
            <b-col cols="12">
              <h2 tabindex="0"><strong>Políticas Públicas</strong></h2>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Políticas Públicas</th>
                    <th style="width: 10%">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="programa in programas" :key="programa.id">
                    <td>{{ programa.nome }}</td>
                    <td>
                      <button class="btn btn-sm btn-secondary action" title="Visualizar"
                        @click="visualizarPrograma(programa.id)">
                        <em class="fa fa-search"></em>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-col>
          </b-row>
          <br>

          <b-row>
            <b-col cols="12">
              <h2 tabindex="0"><strong>Solicite seu Credenciamento</strong></h2>
            </b-col>
          </b-row>
          <p><span v-html="credenciamento"></span></p>
          <b-row>
            <b-col md="4" sm="12">
              <b-button block variant="primary" @click="solicitarCredenciamento()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Solicitar Credenciamento</span>
              </b-button>
            </b-col>
            <b-col md="4" sm="12">
              <b-button block variant="primary" @click="acompanharCredenciamento()" :disabled="disabled">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Acompanhar Credenciamento</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-body>
        <b-card-footer class="p-12">
          <b-row>
            <b-col md="3" sm="12">
              <b-button block variant="secondary" @click="voltar">
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
import miniToastr from 'mini-toastr'
import SessionStorage from '@/services/session-storage'

miniToastr.init()

export default {
  name: 'Apresentacao',
  data () {
    return {
      orientacoes: null,
      orientacoes_credenciamento: null,
      credenciamento: null,
      disabled: false,
      programas: []
    }
  },
  created () {
    this.getConfiguracaoGeral()
    this.getPoliticasPublicas()
  },
  methods: {
    solicitarCredenciamento () {
      this.$router.push('/solicitacao-credenciamento')
    },
    acompanharCredenciamento () {
      SessionStorage.set('acompanhar-credenciamento', true)
      this.disabled = true

      setTimeout(() => {
        Vue.http.get('api/auth/login/uri').then(response => {
          location.href = response.body.data.url
        })
      }, 3000)
    },
    getConfiguracaoGeral () {
      Vue.http.get('api/configuracao-geral').then(response => {
        let data = response.data.data
        this.orientacoes_credenciamento = data.orientacoes_credenciamento
        this.orientacoes = data.orientacoes_adesao
        this.credenciamento = data.orientacoes_solicitacao
      })
    },
    getPoliticasPublicas () {
      Vue.http.get('api/programa/ativo').then(response => {
        let data = response.data.data
        this.programas = data
      })
    },
    visualizarPrograma (id) {
      this.$router.push('/programa-externo/' + id)
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      this.$router.push('/login')
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
<style scoped>
h1,
h2 {
  color: #294B92;
}
</style>