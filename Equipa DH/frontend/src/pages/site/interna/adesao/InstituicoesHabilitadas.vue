<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3 tabindex="0"><strong>Instituições Habilitadas</strong></h3>
            </b-col>
          </b-row>
        </b-card-header>
        <b-card-body>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Política Pública:">
                <b-form-select v-model="filtros.programa_id" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="programa in programas" :value="programa.id" :key="programa.id"> {{ programa.nome }}
                  </option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Cronograma:">
                <b-form-select v-model="filtros.numero_cronograma" class="mb-3" :disabled="!cronogramas.length">
                  <option selected :value="null">Selecione</option>
                  <option v-for="cronograma in cronogramas" :value="cronograma.numero" :key="cronograma.numero"> {{
                    cronograma.numero }}
                  </option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="UF:">
                <b-form-select v-model="filtros.estado_id" :options="estados" value-field="id" text-field="sigla">
                  <option selected :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Município:">
                <b-form-select v-model="filtros.cidade_id" :options="cidades" value-field="id" text-field="cidade"
                  :disabled="!cidades.length">
                  <option selected :value="null">Selecione</option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Instituição:">
                <b-form-select v-model="filtros.cnpj" class="mb-3">
                  <option selected :value="null">Selecione</option>
                  <option v-for="instituicao in instituicoes" :value="instituicao.cnpj" :key="instituicao.id"> {{
                    instituicao.cnpj }} - {{ instituicao.razao_social }}
                  </option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Esfera:">
                <b-form-select v-model="filtros.esfera">
                  <option selected :value="null">Selecione</option>
                  <option v-for="esfera in esferaOptions" :key="esfera.value" :value="esfera.value">{{ esfera.text }}
                  </option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Situação:">
                <b-form-select v-model="filtros.statusConvocacao">
                  <option selected :value="null">Selecione</option>
                  <option value="1">Convocado</option>
                  <option value="0">Pendente de Convocação</option>
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col md="6" sm="12">
              <b-form-group tabindex="0" label="Nº Adesão:">
                <b-form-input v-model="filtros.id" type="text"
                  @keypress.enter="carregarInstituicoesHabilitadas"></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <hr>
          <b-row>
            <b-col sm="2">
              <b-button block @click="limpar">
                <em class="fa fa-eraser"></em>&nbsp;&nbsp;<span>Limpar filtros</span>
              </b-button>
            </b-col>
            <b-col sm="2">
              <b-button block variant="primary" @click="carregarInstituicoesHabilitadas" :disabled="!filtros.programa_id || !filtros.numero_cronograma">
                <em class="fa fa-search"></em>&nbsp;&nbsp;<span>Pesquisar</span>
              </b-button>
            </b-col>
            <b-col sm="2">
              <b-button block variant="primary" @click="exportarDados" :disabled="!instituicoesHabilitadas.data.length">
                <em class="fa fa-file-export"></em>&nbsp;&nbsp;<span>Exportar Dados</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-body>
        <b-row>
          <b-col sm="12">
            <b-container fluid>
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Nº Adesão</th>
                    <th>Cronograma</th>
                    <th>Política Pública</th>
                    <th>UF</th>
                    <th>Município</th>
                    <th>Instituição</th>
                    <th>Classificação</th>
                    <th>Esfera</th>
                    <th>Situação</th>
                  </tr>
                </thead>
                <tbody v-if="instituicoesHabilitadas.data.length">
                  <tr v-for="item in instituicoesHabilitadas.data" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.cronograma.numero }}</td>
                    <td>{{ item.cronograma.programa.nome }}</td>
                    <td>{{ item.instituicao.estado.sigla }}</td>
                    <td>{{ item.instituicao.cidade.cidade }}</td>
                    <td>{{ item.instituicao.cnpj }} - {{ item.instituicao.razao_social }}</td>
                    <td>{{ item.classificacao }}</td>
                    <td>{{ item.instituicao.esfera
                      ? esferaOptions.find(opt => opt.value === item.instituicao.esfera).text
                      : '' }}</td>
                    <td>{{ item.convocado ? 'Convocado' : 'Pendente de convocação' }}</td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td colspan="9" class="text-center">Nenhuma instituição habilitada encontrada</td>
                  </tr>
                </tbody>
              </table>
              <b-row>
                <b-col cols="4">
                  <pagination tabindex="0" v-show="instituicoesHabilitadas.data.length"
                    :config="{
                      total: this.instituicoesHabilitadas.total,
                      last_page: this.instituicoesHabilitadas.last_page,
                      current_page: this.instituicoesHabilitadas.current_page
                    }"
                    :totalPorPagina="length"
                    :filtros="filtros"
                    :origin="'Instituicoes'"
                    @changePage="changePage"></pagination>
                </b-col>
              </b-row>
            </b-container>
          </b-col>
        </b-row>
      </b-card>
    </b-overlay>
  </span>
</template>

<script>
import { esferaOptions } from '@/constants/solicitacao'
import Pagination from '@/components/shared/Pagination'

export default {
  name: 'InstituicoesHabilitadas',
  components: {
    Pagination
  },
  data () {
    return {
      overlay: false,
      length: 20,
      resource: 'adesao/instituicoes-habilitadas',
      filtros: {
        page: 1,
        programa_id: null,
        numero_cronograma: null,
        estado_id: null,
        cidade_id: null,
        cnpj: null,
        esfera: null,
        statusConvocacao: null,
        id: null
      },
      instituicoesHabilitadas: {
        data: [],
        current_page: 1,
        per_page: 1,
        total: 0,
        last_page: null
      },
      esferaOptions
    }
  },
  mounted () {
    this.$store.dispatch('buscarEstados')
    this.$store.dispatch('getProgramas')
    this.$store.dispatch('getCronogramas')
    this.$store.dispatch('getInstituicoes')
  },
  methods: {
    carregarInstituicoesHabilitadas () {
      if (!this.filtros.programa_id || !this.filtros.numero_cronograma) {
        return
      }

      this.overlay = true
      this.$http.get('api/adesao/instituicoes-habilitadas', { params: { ...this.filtros, length: this.length } })
        .then(response => {
          this.instituicoesHabilitadas = response.data.data.data
        })
        .catch(error => {
          this.erro('Erro ao buscar instituições habilitadas')
          console.error(error)
        })
        .finally(() => {
          this.overlay = false
        })
    },

    limpar () {
      Object.keys(this.filtros).forEach(key => {
        this.filtros[key] = null
      })

      this.carregarInstituicoesHabilitadas()
    },

    exportarDados () {
      let queryString = Object.entries(this.filtros)
        .filter(([key, value]) => key !== 'page' && value)
        .map(([key, value]) => `${key}=${value || ''}`)
        .join('&')

      this.overlay = true

      this.$http.get(`api/adesao/instituicoes-habilitadas/export/excel?${queryString}`, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'instituicoes-habilitadas.xlsx'
          link.click()
        }

        this.overlay = false
      }).catch(err => {
        console.log(err)
        this.overlay = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },

    erro (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Erro',
        variant: 'danger',
        solid: true
      })
    },

    changePage (page) {
      this.filtros.page = page
      this.carregarInstituicoesHabilitadas()
    }
  },

  computed: {
    estados () {
      return this.$store.state.estado.listaEstados
    },
    cidades () {
      return this.$store.state.cidade.listaCidadesPorEstado
    },
    programas () {
      return this.$store.state.programa.lista.filter(programa => programa.ativo === true)
    },
    cronogramas () {
      if (this.filtros.programa_id) {
        return this.$store.state.cronograma.lista
          .filter(cronograma => cronograma.publicacao === 'PU' && cronograma.programa_id === this.filtros.programa_id)
      }

      return []
    },
    instituicoes () {
      return this.$store.state.instituicao.lista.filter(instituicao => instituicao.ativo === true)
    }
  },

  watch: {
    'filtros.estado_id': function () {
      if (this.filtros.estado_id) {
        this.$store.dispatch('buscarCidadesPorEstado', this.filtros.estado_id)
      } else {
        this.filtros.cidade_id = null
        this.$store.state.cidade.listaCidadesPorEstado = []
      }
    },
    'filtros.programa_id': function () {
      if (this.cronogramas.length === 1) {
        this.filtros.numero_cronograma = this.cronogramas.find(cronograma => cronograma.programa_id === this.filtros.programa_id).numero
      } else {
        this.filtros.numero_cronograma = null
      }
    }
  }
}
</script>
