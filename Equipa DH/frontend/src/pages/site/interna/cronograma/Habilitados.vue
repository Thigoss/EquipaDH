<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Habilitados</strong></h3>
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

                <table aria-describedby="my-table" aria-rowcount="4" id="my-table" aria-busy="false" aria-colcount="7"
                  class="table table-responsive-sm table-bordered table-striped table-sm">
                  <thead>
                    <tr>
                      <th tabindex="0" scope="col">Instituição</th>
                      <th tabindex="0" scope="col">UF</th>
                      <th tabindex="0" scope="col">Município</th>
                      <th tabindex="0" scope="col">Data da Solicitação</th>
                    </tr>
                  </thead>
                  <tbody v-if="habilitados.length > 0">
                    <tr class="action" v-for="adesao in habilitados" :key="adesao.id">
                      <td tabindex="0">{{ adesao.instituicao.razao_social }}</td>
                      <td tabindex="0">{{ adesao.instituicao.estado.sigla }}</td>
                      <td tabindex="0">{{ adesao.instituicao.cidade.cidade }}</td>
                      <td tabindex="0">{{ adesao.created_at }}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr class="action">
                      <td tabindex="0" colspan="4">Nenhuma adesão habilitada</td>
                    </tr>
                  </tbody>
                </table>
                <pagination-page v-show="habilitados.length > 0"
                  :resource="'adesao/' + $route.params.id + '/habilitado'" :filtros="{}" :totalPorPagina="50"
                  :list="'habilitados'" :origin="'Habilitados'">
                </pagination-page>
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
import Pagination from '@/components/shared/Pagination'
import miniToastr from 'mini-toastr'
import Multiselect from 'vue-multiselect'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import PaginationPage from '@/components/shared/PaginationPage'

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

    this.laodCronograma(this.$route.params.id)
  },
  data () {
    return {
      overlay: false,
      cronograma: null,
      ptBR: ptBR
    }
  },
  computed: {
    habilitados () {
      let result = this.$store.state.pagination.lists.habilitados
      return result !== undefined ? result.data : []
    }
  },
  methods: {
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    laodCronograma () {
      this.$http.get('api/cronograma/' + this.$route.params.id).then(response => {
        this.cronograma = response.body.data
      }).catch(erro => {
        this.erro(erro.body.msg)
      })
    },
    navigate (route) {
      this.$router.push(route)
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
