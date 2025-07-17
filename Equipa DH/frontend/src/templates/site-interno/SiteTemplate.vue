/* eslint-disable eqeqeq */

<template>
  <div class="app">
    <AppHeader fixed>
      <SidebarToggler class="d-lg-none" display="md" mobile />
      <b-link class="navbar-brand" to="/home">
        <h3><strong>EquipaDH+</strong></h3>
      </b-link>
      <SidebarToggler class="d-md-down-none" display="lg" />
      <b-navbar-nav class="ml-auto">
        <b-nav-item class="d-md-down-none mr-5 pt-2">
          <label v-if="instituicao && instituicao !== undefined" class="mr-5">
            <strong>Instituição:</strong> {{ instituicao }}
          </label>
          <label v-if="contexto.unidade" class="mr-5">
            <strong>Unidade:</strong> {{ contexto.unidade.nome }}
          </label>
          <label>
            <strong>Perfil:</strong> {{ contexto.perfil ? contexto.perfil.nome : '-' }}
          </label>
        </b-nav-item>
        <b-nav-item v-if="contextos.length > 1">
          <b-button v-b-modal.modal-contextos title="Trocar Contexto" variant="secondary" size="sm">
            <i class="fa fa-exchange"></i>
          </b-button>
        </b-nav-item>
        <DefaultHeaderDropdownAccnt />
      </b-navbar-nav>
    </AppHeader>
    <div class="app-body">
      <AppSidebar fixed>
        <SidebarHeader />
        <SidebarForm />
        <SidebarNav :navItems="nav"></SidebarNav>
        <SidebarFooter />
        <SidebarMinimizer style="z-index: 999;" />
      </AppSidebar>
      <main class="main">
        <Breadcrumb :list="list" />
        <div class="container-fluid">
          <b-overlay :show="loader">
            <router-view></router-view>
          </b-overlay>
        </div>
      </main>
    </div>
    <TheFooter>
      <div>
        <span class="ml-1">EquipaDH+</span>
      </div>
    </TheFooter>

    <!-- Modal de contextos -->
    <b-modal size="xl" id="modal-contextos" title="Trocar Contexto do Usuário" ok-title="Trocar Contexto"
      cancel-title="Cancelar" hide-header-close no-close-on-backdrop no-close-on-esc @hidden="limparModal"
      :ok-disabled="disabled" :cancel-disabled="disabled" @ok="trocarContexto">
      <b-row>
        <b-col v-if="contexto.id" sm="12" md="12">
          <h6><strong>Contexto Atual:</strong></h6>
          <span v-if="contexto.unidade_id"><strong>Unidade: </strong>{{ contexto.unidade.nome }}</span> &nbsp;
          <span v-if="contexto.perfil_id"><strong>Perfil: </strong>{{ contexto.perfil.nome }}</span>
        </b-col>
      </b-row>
      <hr v-if="contexto.id" />
      <b-row>
        <b-col sm="12" md="12">
          <h6><strong>Selecionar Contexto:</strong></h6>
          <b-form-group tabindex="0">
            <b-input-group class="mb-3">
              <b-input-group-prepend>
                <b-input-group-text><i class="fa fa-id-card-alt"></i></b-input-group-text>
              </b-input-group-prepend>
              <b-form-select tabindex="0" ref="contextInput" v-model.trim="contextoSwitch" :state="contextState"
                :disabled="disabled">
                <option :value="null" selected> Selecione </option>
                <option v-for="context in contextos" :key="'contextOp' + context.id" :value="context.id" selected>
                  {{ (context.unidade_id ? ('Unidade: ' + context.unidade.nome) : '') + (context.perfil_id ? (' - Perfil: ' + context.perfil.nome) : '') }}
                </option>
              </b-form-select>
              <b-form-invalid-feedback>Deve ser selecionado um contexto para realizar a troca!</b-form-invalid-feedback>
            </b-input-group>
          </b-form-group>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import { Header as AppHeader, SidebarToggler, Sidebar as AppSidebar, SidebarFooter, SidebarForm, SidebarHeader, SidebarMinimizer, SidebarNav, Aside as AppAside, AsideToggler, Footer as TheFooter, Breadcrumb } from '@coreui/vue'
import DefaultAside from '@/templates/containers/DefaultAside'
import DefaultHeaderDropdown from '@/templates/containers/DefaultHeaderDropdown'
import DefaultHeaderDropdownAccnt from '@/templates/containers/DefaultHeaderDropdownAccnt'
import DefaultHeaderDropdownMssgs from '@/templates/containers/DefaultHeaderDropdownMssgs'
import DefaultHeaderDropdownTasks from '@/templates/containers/DefaultHeaderDropdownTasks'
import miniToastr from 'mini-toastr'
import SessionStorage from '@/services/session-storage'

miniToastr.init()

export default {
  name: 'SiteTemplate',
  components: {
    AsideToggler,
    AppHeader,
    AppSidebar,
    AppAside,
    TheFooter,
    Breadcrumb,
    DefaultAside,
    DefaultHeaderDropdown,
    DefaultHeaderDropdownMssgs,
    DefaultHeaderDropdownTasks,
    DefaultHeaderDropdownAccnt,
    SidebarForm,
    SidebarFooter,
    SidebarToggler,
    SidebarHeader,
    SidebarNav,
    SidebarMinimizer
  },
  created () {
    this.$store.dispatch('atualizarPermissoes')
    this.$store.dispatch('atualizarContexto')
  },
  data () {
    return {
      filtro: '',
      url: '',
      modalRestrito: false,
      modalMensagem: '',
      contextoSwitch: null,
      contextState: null,
      disabled: false
    }
  },
  watch: {
    'contexto': function () {
      if (this.contexto.id) {
        this.contextoSwitch = this.contexto.id
      }
    }
  },
  computed: {
    contextos () {
      let contextos = JSON.parse(SessionStorage.get('contextos'))
      return contextos
    },
    contexto () {
      return this.$store.getters.getContextoUser
    },
    instituicao () {
      let instituicao = SessionStorage.get('instituicao_nome')
      return instituicao
    },
    nav () {
      let news = []

      let home = {
        name: 'Página Inicial',
        icon: 'fa fa-home',
        url: '/home'
      }
      news.push(home)

      if (!this.can('solicitacao.index')) {
        let minhasSolicitacoes = {
          name: 'Minhas Solicitações',
          icon: 'fa fa-list',
          url: '/minhas-solicitacoes'
        }
        news.push(minhasSolicitacoes)
      }

      if (this.can('adesao.minhasSolicitacoes')) {
        let minhasAdesoes = {
          name: 'Minhas Adesões',
          icon: 'fa fa-list',
          url: '/minhas-adesoes'
        }
        news.push(minhasAdesoes)

        let acompanharAdesoes = {
          name: 'Acompanhar Cronogramas',
          icon: 'fa fa-list',
          url: '/acompanhar-cronogramas'
        }
        news.push(acompanharAdesoes)
      }

      if (this.can('user.index') || this.can('perfil.index') || this.can('unidade.index') || this.can('instituicao.index') || this.can('solicitacao.index') || this.can('programa.index') || this.can('configuracaoGeral.update')) {
        let sistema = {
          name: 'Configurações do Sistema',
          icon: 'fa fa-cog',
          children: []
        }

        if (this.can('user.index')) {
          sistema.children.push({
            name: 'Gerenciar Usuários',
            url: '/usuarios'
          })
        }

        if (this.can('perfil.index')) {
          sistema.children.push({
            name: 'Gerenciar Perfis',
            url: '/perfis'
          })
        }

        if (this.can('unidade.index')) {
          sistema.children.push({
            name: 'Unidades',
            url: '/unidades'
          })
        }

        if (this.can('instituicao.index')) {
          sistema.children.push({
            name: 'Instituições',
            url: '/instituicoes'
          })
        }

        if (this.can('solicitacao.index')) {
          sistema.children.push({
            name: 'Solicitações de Credenciamento',
            url: '/solicitacoes'
          })
        }

        if (this.can('programa.index')) {
          sistema.children.push({
            name: 'Políticas Públicas',
            url: '/programas'
          })
        }

        if (this.can('configuracaoGeral.update')) {
          sistema.children.push({
            name: 'Configurações Gerais',
            url: '/configuracao-geral'
          })
        }

       // if (this.can('bensEquipamentos.index')) {
          sistema.children.push({
            name: 'Bens e Equipamentos',
            url: '/bens-equipamentos'
          })
       // }

        news.push(sistema)
      }

      if (this.can('cronograma.index') || this.can('adesao.index')) {
        let sistema = {
          name: 'Políticas Públicas',
          icon: 'fa fa-building',
          children: []
        }

        if (this.can('cronograma.index')) {
          sistema.children.push({
            name: 'Gerenciar Cronograma',
            url: '/cronograma'
          })
        }

        if (this.can('adesao.index')) {
          sistema.children.push({
            name: 'Solicitações de Análise de Documentos',
            url: '/adesao'
          })
        }

        news.push(sistema)
      }

      news.push({
        name: 'Instituições Habilitadas',
        url: '/instituicoes-habilitadas'
      })

      return news
    },
    computedNav () {
      return this.nav.filter((item) => console.log(item.name))
    },
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched.filter((route) => route.name || route.meta.label)
    },
    loader () {
      return this.$store.state.loader.loader
    }
  },
  methods: {
    limparModal () {
      if (this.contexto.id) {
        this.contextoSwitch = this.contexto.id
      } else {
        this.contextoSwitch = null
      }

      this.contextState = null
      this.disabled = false
    },
    trocarContexto (bvModalEvt) {
      bvModalEvt.preventDefault()

      if (!this.contextoSwitch) {
        this.contextState = false
        this.$refs.contextInput.$el.focus()
      }

      this.disabled = true
      this.$http.post('api/switch-context/' + this.contextoSwitch).then(response => {
        if (response.body.success) {
          SessionStorage.set('token', response.body.data['token'])
          SessionStorage.set('permissoes', JSON.stringify(response.body.data['permissoes']))
          SessionStorage.set('contextoAtual', JSON.stringify(response.body.data['contextoAtual']))

          this.$bvModal.hide('modal-contextos')

          this.$store.dispatch('atualizarPermissoes')
          this.$store.dispatch('atualizarContexto')

          if (!this.$route.path.includes('home')) {
            this.$router.push('/home')
          }
        }
        this.sucesso(response.body.msg)
        this.disabled = false
      }).catch(e => {
        console.log(e)
        this.disabled = false
        this.erro(e.body.msg)
      })
    },
    can (permissao) {
      return this.$store.getters.validaPermissao(permissao)
    },
    pesquisar () {
      //
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

<style lang="scss">
// CoreUI Icons Set
@import '@coreui/icons/css/coreui-icons.min.css';
/* Import Font Awesome Icons Set */
$fa-font-path: '~font-awesome/fonts/';
@import '~font-awesome/scss/font-awesome.scss';
/* Import Simple Line Icons Set */
$simple-line-font-path: '~simple-line-icons/fonts/';
@import '~simple-line-icons/scss/simple-line-icons.scss';
/* Import Flag Icons Set */
@import 'flag-icon-css/css/flag-icon.min.css';
/* Import Bootstrap Vue Styles */
@import 'bootstrap-vue/dist/bootstrap-vue.css';
// Import Main styles for this application
@import '../../assets/scss/style';

i.nav-icon:not([class*=" "]) {
  display: none !important;
}
</style>
