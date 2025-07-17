<template>
  <span>
    <br>
    <b-col md="8" sm="12" offset-md="2">
      <b-card no-body>
        <b-card-body>
          <h1 tabindex="0"><strong>{{ programa.nome }}</strong></h1>
          <br>

          <b-row>
            <b-col cols="12">
              <div class="banner" v-if="programa.logomarca">
                <img :src="programa.logomarca" class="img-fluid" alt="Imagem do Programa" />
              </div>
            </b-col>
          </b-row>
          <br>

          <b-row>
            <b-col cols="12">
              <h2 tabindex="0"><strong>Descrição da Política</strong></h2>
            </b-col>
          </b-row>
          <p><span v-html="programa.descricao"></span></p>
          <br>

          <b-row>
            <b-col cols="12">
              <h2 tabindex="0"><strong>Cronogramas</strong></h2>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="12">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nº Cronograma</th>
                    <th>Data</th>
                    <th>Fase</th>
                    <th>Status da Fase</th>
                    <th style="width: 10%">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="cronograma in programa.cronogramas" :key="cronograma.id">
                    <td>{{ cronograma.numero }}</td>
                    <td>
                      {{ cronograma.data_publicacao_inicio | formatDateToBR }}
                      à
                      {{ cronograma.data_divulgacao_lista | formatDateToBR }}
                    </td>
                    <td tabindex="0">
                      <b-badge v-if="cronograma.fase === 'NI'" variant="secondary">Não Iniciado</b-badge>
                      <b-badge v-if="cronograma.fase === 'PU'" variant="info">Publicado</b-badge>
                      <b-badge v-if="cronograma.fase === 'AD'" variant="info">Adesão e Habilitação</b-badge>
                      <b-badge v-if="cronograma.fase === 'AF'" variant="info">Validação das Adesões</b-badge>
                      <b-badge v-if="cronograma.fase === 'DA'" variant="info">Divulgação da Adesão</b-badge>
                      <b-badge v-if="cronograma.fase === 'RA'" variant="info">Recurso Adesão</b-badge>
                      <b-badge v-if="cronograma.fase === 'RF'" variant="info">Validação dos Recursos</b-badge>
                      <b-badge v-if="cronograma.fase === 'DH'" variant="info">Divulgação de Habilitados</b-badge>
                      <b-badge v-if="cronograma.fase === 'RH'" variant="info">Recurso de Habilitados</b-badge>
                      <b-badge v-if="cronograma.fase === 'HF'" variant="info">Validação de Recursos de
                        Habilitados</b-badge>
                      <b-badge v-if="cronograma.fase === 'DL'" variant="info">Divulgação da Lista</b-badge>
                      <b-badge v-if="cronograma.fase === 'EN'" variant="info">Encerrado</b-badge>
                      <b-badge v-if="cronograma.fase === 'CO'" variant="info">Convocação</b-badge>
                    </td>
                    <td tabindex="0">
                      <b-badge v-if="cronograma.situacao == 'NI'" variant="secondary">Não Iniciado</b-badge>
                      <b-badge v-if="cronograma.situacao == 'AN'" variant="info">Em Andamento</b-badge>
                      <b-badge v-if="cronograma.situacao == 'FI'" variant="success">Finalizado</b-badge>
                      <b-badge v-if="cronograma.situacao == 'CA'" variant="danger">Cancelado</b-badge>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-secondary action" title="Visualizar"
                        @click="visualizarCronograma(cronograma.id)">
                        <em class="fa fa-search"></em>
                      </button>
                      <button v-if="cronograma.fase === 'AD'" class="btn btn-sm btn-primary action" title="Adesão"
                        @click="solicitarAdesao(cronograma.id)" :disabled="disabled">
                        <em class="fa fa-plus"></em>
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
  name: 'ProgramaExterno',
  data () {
    return {
      credenciamento: null,
      programa: {},
      disabled: false
    }
  },
  created () {
    this.load(this.$route.params.id)
    this.getPoliticasPublicas()
  },
  methods: {
    load (id) {
      Vue.http.get('api/programa/' + id + '/ativo').then(response => {
        this.programa = response.data.data
        this.programa.logomarca = this.programa.logomarca ? (process.env.ROOT_API1 + this.programa.logomarca) : null
      })
    },
    getPoliticasPublicas () {
      Vue.http.get('api/configuracao-geral').then(response => {
        let data = response.data.data
        this.credenciamento = data.orientacoes_solicitacao
      })
    },
    solicitarCredenciamento () {
      this.$router.push('/solicitacao-credenciamento')
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
    visualizarCronograma (id) {
      this.$router.push('/cronograma-externo/' + id)
    },
    navigate (route) {
      this.$router.push(route)
    },
    voltar () {
      this.$router.push('/apresentacao')
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

.banner {
  width: 100%;
  max-height: 400px;
  overflow: hidden;
  border: 1px solid #ccc;
}

img {
  width: 100%;
  height: auto;
}
</style>