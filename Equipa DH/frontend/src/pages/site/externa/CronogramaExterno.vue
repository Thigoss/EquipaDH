<template>
  <span>
    <br>
    <b-col md="8" sm="12" offset-md="2">
      <b-card no-body>
        <b-card-body>
          <h1 tabindex="0"><strong>Nº do Cronograma {{ cronograma.numero }}</strong></h1>
          <br>

          <b-row>
            <b-col cols="12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Fase</th>
                    <th>Data</th>
                    <th>Status da Fase</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Publicação do Cronograma de Participação</td>
                    <td>{{ cronograma.data_publicacao_inicio | formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'AD' || cronograma.fase == 'DA' || cronograma.fase == 'RA' || cronograma.fase == 'DH' || cronograma.fase == 'RH' || cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'AF' || cronograma.fase == 'RF' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'PU'" variant="info">Em Andamento</b-badge>
                    </td>
                  </tr>
                  <tr>
                    <td>Prazo para adesão à Política Pública</td>
                    <td>{{ cronograma.data_adesao_inicio | formatDateToBR }} à {{ cronograma.data_adesao_fim |
                      formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'DA' || cronograma.fase == 'RA' || cronograma.fase == 'DH' || cronograma.fase == 'RH' || cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'AF' || cronograma.fase == 'RF' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'AD'" variant="info">Em Andamento</b-badge>
                      <b-badge v-if="cronograma.fase == 'PU'" variant="secondary">À iniciar</b-badge>
                    </td>
                  </tr>
                  <!-- <tr v-if="cronograma.data_divulgacao_adesao_inicio !== null && cronograma.data_divulgacao_adesao_fim !== null"> -->
                  <tr v-if="cronograma.data_divulgacao_adesao_inicio !== null">
                    <td>Divulgação preliminar das adesões</td>
                    <td>{{ cronograma.data_divulgacao_adesao_inicio | formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'RA' || cronograma.fase == 'DH' || cronograma.fase == 'RH' || cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'AF' || cronograma.fase == 'RF' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'DA'" variant="info">Em Andamento</b-badge>
                      <b-badge v-if="cronograma.fase == 'PU' || cronograma.fase == 'AD'" variant="secondary">À
                        iniciar</b-badge>
                    </td>
                  </tr>
                  <tr
                    v-if="cronograma.data_recurso_adesao_inicio !== null && cronograma.data_recurso_adesao_fim !== null">
                    <td>Prazo para recurso de Adesões</td>
                    <td>{{ cronograma.data_recurso_adesao_inicio | formatDateToBR }} à {{
                      cronograma.data_recurso_adesao_fim | formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'DH' || cronograma.fase == 'RH' || cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'RF' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'RA'" variant="info">Em Andamento</b-badge>
                      <b-badge
                        v-if="cronograma.fase == 'PU' || cronograma.fase == 'AD' || cronograma.fase == 'DA' || cronograma.fase == 'AF'"
                        variant="secondary">À iniciar</b-badge>
                    </td>
                  </tr>
                  <tr>
                    <td>Divulgação preliminar da lista de participantes habilitados e classificados na política pública
                    </td>
                    <td>{{ cronograma.data_divulgacao_habilitados_inicio | formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'RH' || cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'DH'" variant="info">Em Andamento</b-badge>
                      <b-badge
                        v-if="cronograma.fase == 'PU' || cronograma.fase == 'AD' || cronograma.fase == 'DA' || cronograma.fase == 'AF' || cronograma.fase == 'RA' || cronograma.fase == 'RF'"
                        variant="secondary">À iniciar</b-badge>
                    </td>
                  </tr>
                  <tr>
                    <td>Prazo para recurso dos participantes quanto à lista de habilitados e classificados</td>
                    <td>{{ cronograma.data_recurso_habilitados_inicio | formatDateToBR }} à {{
                      cronograma.data_recurso_habilitados_fim | formatDateToBR }}</td>
                    <td>
                      <b-badge
                        v-if="cronograma.fase == 'DL' || cronograma.fase == 'EN' || cronograma.fase == 'CO' || cronograma.fase == 'HF'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'RH'" variant="info">Em Andamento</b-badge>
                      <b-badge
                        v-if="cronograma.fase == 'PU' || cronograma.fase == 'AD' || cronograma.fase == 'DA' || cronograma.fase == 'AF' || cronograma.fase == 'RA' || cronograma.fase == 'RF' || cronograma.fase == 'DH'"
                        variant="secondary">À iniciar</b-badge>
                    </td>
                  </tr>
                  <tr>
                    <td>Divulgação da lista de participantes habilitados e classificados na política pública</td>
                    <td>{{ cronograma.data_divulgacao_lista | formatDateToBR }}</td>
                    <td>
                      <b-badge v-if="cronograma.fase == 'EN' || cronograma.fase == 'CO'"
                        variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.fase == 'DL'" variant="info">Em Andamento</b-badge>
                      <b-badge
                        v-if="cronograma.fase == 'PU' || cronograma.fase == 'AD' || cronograma.fase == 'DA' || cronograma.fase == 'AF' || cronograma.fase == 'RA' || cronograma.fase == 'RF' || cronograma.fase == 'DH' || cronograma.fase == 'RH' || cronograma.fase == 'HF'"
                        variant="secondary">À iniciar</b-badge>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-col>
          </b-row>
          <br>
          <b-row>
            <b-col v-if="cronograma.fase === 'AD'" md="4" sm="12">
              <b-button block variant="primary" @click="solicitarAdesao(cronograma.id)" :disabled="disabled">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Solicitar Adesão</span>
              </b-button>
            </b-col>
            <b-col md="4" sm="12">
              <b-button block variant="primary" @click="solicitarCredenciamento()">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;<span>Solicitar Credenciamento</span>
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
      cronograma: null,
      disabled: false
    }
  },
  created () {
    this.load(this.$route.params.id)
  },
  methods: {
    load (id) {
      Vue.http.get('api/cronograma/' + id).then(response => {
        this.cronograma = response.data.data
      })
    },
    solicitarAdesao (id) {
      SessionStorage.set('solicitar-adesao', id)
      this.disabled = true

      setTimeout(() => {
        Vue.http.get('api/auth/login/uri').then(response => {
          location.href = response.body.data.url
        })
      }, 2000)
    },
    solicitarCredenciamento () {
      this.$router.push('/solicitacao-credenciamento')
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      this.$router.push('/programa-externo/' + this.cronograma.programa_id)
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
