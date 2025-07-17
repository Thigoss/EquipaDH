<template>
  <span>
    <b-overlay :show="overlay" rounded="sm">
      <b-card no-body>
        <b-card-header>
          <b-row>
            <b-col md="9">
              <h3><strong>Bens e Equipamentos</strong></h3>
            </b-col>
            <b-col md="3">
              <b-button block variant="primary" @click="cadastrar">
                <em class="fa fa-plus"></em>&nbsp;&nbsp;Novo
              </b-button>
            </b-col>
          </b-row>
        </b-card-header>

        <b-card-body>
          <b-row>
            <b-col md="4" sm="12">
              <b-form-group label="Objeto:">
                <b-form-input v-model="filtros.nome" placeholder="Digite o nome do objeto" />
              </b-form-group>
            </b-col>
            <b-col md="4" sm="12">
              <b-form-group label="Categoria:">
                <b-form-select v-model="filtros.categoria">
                  <option :value="null">Selecione</option>
                  <option value="Equipamentos de TIC">Equipamentos de TIC</option>
                  <option value="Eletrodomésticos">Eletrodomésticos</option>
                  <option value="Eletrônicos">Eletrônicos</option>
                  <option value="Mobiliários">Mobiliários</option>
                  <option value="Veículos terrestres">Veículos terrestres</option>
                </b-form-select>
              </b-form-group>
            </b-col>
     
           <b-col md="4" sm="12">
              <b-form-group label="Situação:">
                <b-form-select v-model="filtros.situacao" class="mb-3">
                  <option :value="null">Selecione</option>
                  <option v-for="item in situacoes" :key="item.id" :value="item.value">{{ item.text }}</option>
                </b-form-select>
              </b-form-group>
            </b-col>
          </b-row>

          <b-row>
            <b-col sm="2">
              <b-button block @click="limpar">
                <em class="fa fa-eraser"></em>&nbsp;Limpar filtros
              </b-button>
            </b-col>
            <b-col sm="2">
              <b-button block variant="primary" @click="pesquisar">
                <em class="fa fa-search"></em>&nbsp;Pesquisar
              </b-button>
            </b-col>
            <b-col sm="2">
              <b-button block variant="primary" @click="exportarDados" >
                <em class="fa fa-file-export"></em>&nbsp;&nbsp;<span>Exportar Dados</span>
              </b-button>
            </b-col>
          </b-row>
        </b-card-body>


        <b-container fluid>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>Categoria</th>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>Situação</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody v-if="itens.length">
                <tr v-for="item in itens" :key="item.id">
                  <td>{{ item.categoria }}</td>
                  <td>{{ item.nome }}</td>
                  <td>{{ item.descricao }}</td>
                  <td>
                    <b-badge :variant="item.situacao ? 'success' : 'danger'">
                      {{ item.situacao ? 'Ativo' : 'Inativo' }}
                    </b-badge>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-secondary" title="Editar" @click="editar(item.id)">
                      <em class="fa fa-edit"></em>
                    </button>
                    <button v-if="item.situacao" class="btn btn-sm btn-secondary action"
                          title="Desativar" @click="alterarSituacao(item.id, false)">
                          <em class="fa fa-toggle-off"></em>
                        </button>
                        <button v-if="!item.situacao" class="btn btn-sm btn-secondary action"
                          title="Ativar" @click="alterarSituacao(item.id, true)">
                          <em class="fa fa-toggle-on"></em>
                        </button>
                    <button class="btn btn-sm btn-secondary" title="Excluir" @click="excluir(item.id)">
                      <em class="fa fa-trash"></em>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="5">Nenhum bem encontrado</td>
                </tr>
              </tbody>
            </table>

            <!-- Paginação -->
            <b-row class="mt-3">
              <b-col>
                <b-pagination
                  v-model="paginaAtual"
                  :total-rows="totalItens"
                  :per-page="filtros.perPage"
                  align="center"
                  @input="pesquisar"
                />
              </b-col>              
            </b-row>
            
            
          </div>
        </b-container>
      </b-card>
    </b-overlay>
  </span>
</template>

<script>
export default {
  name: 'BensEquipamentos',
  data () {
    return {
      overlay: false,
      filtros: {
        nome: '',
        categoria: '',
        situacao: null,
        perPage: 10
      },
            paginaAtual: 1,
      totalItens: 0,
      situacoes: [
        { value: true, text: 'Ativo' },
        { value: false, text: 'Inativo' }
      ],
      categorias: [
        'Equipamentos de TIC',
        'Eletrodomésticos',
        'Eletrônicos',
        'Mobiliários',
        'Veículos terrestres'
      ],
      itens: []
    }
  },
  created () {
    this.pesquisar()
  },
  methods: {
    cadastrar () {
      this.$router.push('/bens-equipamentos/cadastrar')
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    editar (id) {
      this.$router.push(`/bens-equipamentos/${id}/editar`)
    },
    alterarSituacao (id, situacao) {
  const msg = situacao
    ? 'Confirma a ativação do registro selecionado?'
    : 'Confirma a inativação do registro selecionado?'

            this.$bvModal.msgBoxConfirm(msg, {
              okTitle: 'Confirmar',
              cancelTitle: 'Cancelar'
            }).then(confirmado => {
              if (confirmado) {
                const url = `api/bens-equipamentos/${id}/${situacao ? 'ativo' : 'inativo'}`
                this.overlay = true
                this.$http.put(url)
                  .then(response => {
                    this.overlay = false
                    this.sucesso(response.data.msg || 'Situação atualizada com sucesso!')
                    this.pesquisar()
                  })
                  .catch(error => {
                    this.overlay = false
                    this.erro(
            (error.response && error.response.data && error.response.data.msg) ||
            'Erro ao atualizar situação.'
          )
      })
    }
  })
},
    excluir (id) {
      if (!confirm('Deseja realmente excluir este bem?')) return
      this.overlay = true
      this.$http.delete(`api/bens-equipamentos/${id}`)
        .then(() => {
          this.sucesso('Bem removido com sucesso!')
          this.pesquisar()
        })
        .catch(() => {
          this.erro('Erro ao excluir o bem.')
          this.overlay = false
        })
    },
    pesquisar () {
      this.overlay = true
      const params = {
        ...this.filtros,
        page: this.paginaAtual,
        per_page: this.filtros.perPage
      }
      this.$http.get('api/bens-equipamentos', { params })
      .then(response => {
       const dados = response.data.data.data

        if (Array.isArray(dados)) {
          this.itens = dados
          this.totalItens = dados.length
        } else {
          this.itens = dados.data || []
          this.totalItens = dados.total || 0
        }

        this.overlay = false
      })
        .catch(() => {
          this.erro('Erro ao carregar dados dos bens.')
          this.overlay = false
        })
    },
    limpar () {
      this.filtros = {
        nome: '',
        categoria: '',
        situacao: null,
        perPage: 10
      }
      this.paginaAtual = 1
      this.pesquisar()
    },
    sucesso (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Sucesso',
        variant: 'success',
        autoHideDelay: 4000
      })
    },
    erro (mensagem) {
      this.$bvToast.toast(mensagem, {
        title: 'Erro',
        variant: 'danger',
        autoHideDelay: 4000
      })
    },
    exportarDados () {
      let queryString = Object.entries(this.filtros)
        .filter(([key, value]) => key !== 'per_page' && value)
        .map(([key, value]) => `${key}=${value || ''}`)
        .join('&')

      this.overlay = true

      this.$http.get(`api/bens-equipamentos/export/excel?${queryString}`, { responseType: 'blob' }).then(response => {
        if (response.ok) {
          let blob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
          let link = document.createElement('a')
          link.href = window.URL.createObjectURL(blob)
          link.download = 'bens-equipamentos.xlsx'
          link.click()
        }

        this.overlay = false
      }).catch(err => {
        console.log(err)
        this.overlay = false
        this.erro('Erro ao exportar arquivo, tente filtrar os registros! Se persistir contate o administrador!!')
      })
    },
  }
}
</script>

<style scoped>
.table-responsive {
  overflow-x: auto;
}
</style>