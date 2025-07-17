<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Convocação</strong></h3>
            </b-col>
          </b-row>
        </b-card-header>

        <br>
        <b-row>
          <b-col sm="12">
            <b-container fluid>
              <span>
                <strong>Nº do Cronograma:</strong>&nbsp;{{ cronograma ? cronograma.numero : '' }} <strong>Nome da
                  Política Pública:</strong>&nbsp;{{ cronograma ? cronograma.programa.nome : '' }}
                <br><br>

                <table class="table table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">Esfera</th>
                      <th tabindex="0" scope="col">UF</th>
                      <th tabindex="0" scope="col">Município</th>
                      <th tabindex="0" scope="col">Data da Solicitação</th>
                      <th tabindex="0" scope="col">Classificação</th>
                      <th tabindex="0" scope="col">Convocação</th>
                      <th tabindex="0" scope="col">Situação Aceite</th>
                      <th tabindex="0" scope="col">Ações</th>
                    </tr>
                  </thead>
                  <tbody v-if="adesoes.length > 0">
                    <tr class="action" v-for="adesao in adesoes" :key="adesao.id">
                      <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                      <td tabindex="0">{{adesao.instituicao.esfera ? esferaOptions.find(e => e.value ===
                        adesao.instituicao.esfera).text : ' - '}}</td>
                      <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                      <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                      <td tabindex="0">{{ adesao.created_at }}</td>
                      <td tabindex="0">{{ adesao.classificacao ? adesao.classificacao + 'º' : '-' }}</td>
                      <td tabindex="0">{{ adesao.convocado ? 'Convocado' : 'Não Convocado' }}</td>
                      <td tabindex="0">
                        {{ adesao.convocado && adesao.situacao == 'AP' ? 'Aceito' : '' }}
                        {{ adesao.convocado && adesao.situacao == 'RE' ? 'Não Aceito' : '' }}
                      </td>
                      <td>
                        <b-button v-if="(can('cronograma.update') || can('cronograma.store')) && !adesao.convocado"
                          @click="convocar(adesao.id)" variant="info" size="sm">
                          <em class="fa fa-envelope"></em>&nbsp;&nbsp;<span>Convocar</span>
                        </b-button>
                        <b-button v-if="can('cronograma.show') && adesao.termo_convocacao"
                          @click="download('adesao_termo_convocacao', adesao.id)" variant="info" size="sm">
                          <em class="fa fa-eye"></em>&nbsp;&nbsp;<span>Visualizar Termo</span>
                        </b-button>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action text-center">
                      <td tabindex="0" colspan="9">Nenhuma adesão classificada</td>
                    </tr>
                  </tbody>
                </table>
              </span>
            </b-container>
          </b-col>
        </b-row>

        <b-row>
          <b-col sm="2" class="text-left">
            <b-container fluid>
              <b-button @click="navigate('/cronograma')" variant="secondary">
                <em class="fa fa-mail-reply-all"></em>&nbsp;&nbsp;<span>Voltar</span>
              </b-button>
            </b-container>
          </b-col>
        </b-row>
        <br>

      </b-card>
    </b-overlay>
  </span>
</template>

<script>
import Vue from 'vue'
import Pagination from '@/components/shared/Pagination'
import { eventType } from '@/components/shared/eventType'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import SessionStorage from '@/services/session-storage'
import Datepicker from 'vuejs-datepicker';
import { ptBR } from 'vuejs-datepicker/dist/locale'
import moment from 'moment'
import PaginationPage from '@/components/shared/PaginationPage'
import { esferaOptions } from '@/constants/solicitacao'

miniToastr.init()

export default {
  components: {
    Pagination,
    PaginationPage,
    Multiselect,
    Datepicker
  },
  created () {
    if (!this.can('cronograma.show')) {
      this.$router.push('/home')
    }

    this.loadClassificados(this.$route.params.id)
    this.laodCronograma(this.$route.params.id)
  },
  data () {
    return {
      overlay: false,
      cronograma: null,
      adesoes: [],
      ptBR: ptBR
    }
  },
  computed: {
    esferaOptions () {
      return esferaOptions
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    setarFiltros () {
      eventType.$emit('app', filtros)
    },
    laodCronograma () {
      this.$http.get('api/cronograma/' + this.$route.params.id).then(response => {
        this.cronograma = response.body.data
      }).catch(erro => {
        this.erro(erro.body.msg)
      })
    },
    loadClassificados (cronograma) {
      this.overlay = true
      this.$http.get('api/adesao/' + cronograma + '/classificado').then(response => {
        this.adesoes = response.body.data.data
        this.overlay = false
      }).catch(erro => {
        this.erro(erro.body.msg)
        this.overlay = false
      })
    },
    convocar (id) {
      this.$bvModal.msgBoxConfirm(`Deseja convocar essa adesão?`, {
        okTitle: 'Confirmar',
        cancelTitle: 'Cancelar'
      }).then(valor => {
        if (valor) {
          this.$http.post('api/adesao/' + id + '/convocado').then(response => {
            if (response.body.success) {
              this.sucesso(response.body.msg)
              this.loadClassificados(this.$route.params.id)
            }
          }).catch(erro => {
            if (!erro.body.success) {
              this.erro(erro.body.msg)
              this.loadClassificados(this.$route.params.id)
            }
          })
        }
      })
    },
    download (origem, id) {
      this.$abrirDocumento(origem, id)
    },
    navigate (route) {
      this.$router.push(route)
    },
    limpar () {
      this.filtros.filter = ''
      this.filtros.numero = null
      this.filtros.situacao = null
      this.filtros.fase = null
      this.filtros.data_inicio = null
      this.filtros.data_termino = null
      this.pesquisar()
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
<style>
.multiselect.invalid .multiselect__tags,
.multiselect.invalid .multiselect__tags span,
.multiselect.invalid .multiselect__tags input {
  background: red;
}
</style>
