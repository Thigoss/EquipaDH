import SiteTemplate from '@/templates/site-interno/SiteTemplate'
import Login from '@/pages/auth/Login'
import LoginRedirect from '@/pages/auth/LoginRedirect'
import HomeExterna from '@/pages/site/externa/Home'
import HomeInterna from '@/pages/site/interna/Home'
import MeuPerfil from '@/pages/site/interna/perfil/MeuPerfil'

import SolicitacaoExterna from '@/pages/auth/SolicitacaoExterna'
import Apresentacao from '@/pages/site/externa/Apresentacao'
import ProgramaExterno from '@/pages/site/externa/ProgramaExterno'
import CronogramaExterno from '@/pages/site/externa/CronogramaExterno'

import Usuario from '@/pages/site/interna/administrador/usuarios/Usuarios'
import UsuarioForm from '@/pages/site/interna/administrador/usuarios/UsuarioForm'

import Perfis from '@/pages/site/interna/administrador/perfil/Perfis'
import PerfisForm from '@/pages/site/interna/administrador/perfil/PerfisForm'

import MinhasSolicitacoes from '@/pages/site/interna/solicitacao/MinhasSolicitacoes'
import Solicitacao from '@/pages/site/interna/solicitacao/Solicitacao'
import SolicitacaoForm from '@/pages/site/interna/solicitacao/SolicitacaoForm'

import Programa from '@/pages/site/interna/programa/Programa'
import ProgramaForm from '@/pages/site/interna/programa/ProgramaForm'

import Unidade from '@/pages/site/interna/unidade/Unidade'
import UnidadeForm from '@/pages/site/interna/unidade/UnidadeForm'

import Instituicao from '@/pages/site/interna/instituicao/Instituicao'
import InstituicaoForm from '@/pages/site/interna/instituicao/InstituicaoForm'

import Cronograma from '@/pages/site/interna/cronograma/Cronograma'
import CronogramaForm from '@/pages/site/interna/cronograma/CronogramaForm'
import Habilitados from '@/pages/site/interna/cronograma/Habilitados'
import Classificados from '@/pages/site/interna/cronograma/Classificados'
import Convocados from '@/pages/site/interna/cronograma/Convocados'

import ArquivosClassificacaoEstadual from '@/pages/site/interna/cronograma/arquivosClassificacao/ArquivosClassificacaoEstadual'
import ArquivoClassificacaoEstadualForm from '@/pages/site/interna/cronograma/arquivosClassificacao/ArquivoClassificacaoEstadualForm'
import ArquivosClassificacaoMunicipal from '@/pages/site/interna/cronograma/arquivosClassificacao/ArquivosClassificacaoMunicipal'
import ArquivoClassificacaoMunicipalForm from '@/pages/site/interna/cronograma/arquivosClassificacao/ArquivoClassificacaoMunicipalForm'

import ConfiguracaoGeralForm from '@/pages/site/interna/configuracaoGeral/ConfiguracaoGeralForm'

import Adesao from '@/pages/site/interna/adesao/Adesao'
import MinhasAdesoes from '@/pages/site/interna/adesao/MinhasAdesoes'
import AcompanharAdesoes from '@/pages/site/interna/adesao/AcompanharAdesoes'
import AdesaoForm from '@/pages/site/interna/adesao/AdesaoForm'
import AdesaoEditar from '@/pages/site/interna/adesao/AdesaoEditar'
import AdesaoAvaliar from '@/pages/site/interna/adesao/AdesaoAvaliar'
import AdesaoAcompanhar from '@/pages/site/interna/adesao/AdesaoAcompanhar'
import AdesaoAvaliarRecursoAdesao from '@/pages/site/interna/adesao/AdesaoAvaliarRecursoAdesao'
import AdesaoAvaliarRecursoClassificacao from '@/pages/site/interna/adesao/AdesaoAvaliarRecursoClassificacao'
import AdesaoAnalisarConvocacao from '@/pages/site/interna/adesao/AdesaoAvaliarConvocacao'
import InstituicoesHabilitadas from '@/pages/site/interna/adesao/InstituicoesHabilitadas'

import BensEquipamentos from '@/pages/site/interna/bensEquipamentos/BensEquipamentos'
import BensEquipamentoForm from '@/pages/site/interna/bensEquipamentos/BensEquipamentoForm'

const routes = [
  {
    path: '/home',
    // redirect: '/',
    name: 'Página Inicial',
    component: SiteTemplate,
    children: [
      {
        path: '/home',
        component: HomeInterna
      },

      // Usuários
      {
        path: '/usuarios',
        name: 'Usuários',
        component: Usuario
      },
      {
        path: '/usuario/cadastrar',
        name: 'Cadastrar do Usuário',
        component: UsuarioForm
      },
      {
        path: '/usuario/:id/editar',
        name: 'Edição do Usuário',
        component: UsuarioForm
      },
      {
        path: '/usuario/:id/:visualizar',
        name: 'Visualizar Usuário',
        component: UsuarioForm
      },
      {
        path: '/perfil',
        name: 'Meu Perfil',
        component: MeuPerfil
      },

      // Perfis
      {
        path: '/perfis',
        name: 'Perfis',
        component: Perfis
      },
      {
        path: '/perfil/cadastrar',
        name: 'Cadastro do Perfil',
        component: PerfisForm
      },
      {
        path: '/perfil/:id/editar',
        name: 'Edição do Perfil',
        component: PerfisForm
      },
      {
        path: '/perfil/:id/:visualizar',
        name: 'Visualizar Perfil',
        component: PerfisForm
      },

      // Solicitações
      {
        path: '/minhas-solicitacoes',
        name: 'Minhas Solicitações',
        component: MinhasSolicitacoes
      },
      {
        path: '/solicitacoes',
        name: 'Solicitações',
        component: Solicitacao
      },
      {
        path: '/solicitacao/cadastrar',
        name: 'Cadastrar Solicitação',
        component: SolicitacaoForm
      },
      {
        path: '/solicitacao/:id/editar',
        name: 'Editar Solicitação',
        component: SolicitacaoForm
      },
      {
        path: '/solicitacao/:id/:visualizar',
        name: 'Visualizar Solicitação',
        component: SolicitacaoForm
      },

      // Instituição
      {
        path: '/instituicoes',
        name: 'Instituições',
        component: Instituicao
      },
      {
        path: '/instituicao/cadastrar',
        name: 'Cadastrar Instituição',
        component: InstituicaoForm
      },
      {
        path: '/instituicao/:id/editar',
        name: 'Editar Instituição',
        component: InstituicaoForm
      },
      {
        path: '/instituicao/:id/:visualizar',
        name: 'Visualizar Instituição',
        component: InstituicaoForm
      },

      // Unidade
      {
        path: '/unidades',
        name: 'Unidades',
        component: Unidade
      },
      {
        path: '/unidade/cadastrar',
        name: 'Cadastrar Unidade',
        component: UnidadeForm
      },
      {
        path: '/unidade/:id/editar',
        name: 'Editar Unidade',
        component: UnidadeForm
      },
      {
        path: '/unidade/:id/:visualizar',
        name: 'Visualizar Unidade',
        component: UnidadeForm
      },

      // Programas
      {
        path: '/programas',
        name: 'Programas',
        component: Programa
      },
      {
        path: '/programa/cadastrar',
        name: 'Cadastrar Programa',
        component: ProgramaForm
      },
      {
        path: '/programa/:id/editar',
        name: 'Editar Programa',
        component: ProgramaForm
      },
      {
        path: '/programa/:id/:visualizar',
        name: 'Visualizar Programa',
        component: ProgramaForm
      },

      // Cronogramas
      {
        path: '/cronograma',
        name: 'Cronogramas',
        component: Cronograma
      },
      {
        path: '/cronograma/cadastrar',
        name: 'Cadastrar Cronograma',
        component: CronogramaForm
      },
      {
        path: '/cronograma/:id/editar',
        name: 'Editar Cronograma',
        component: CronogramaForm
      },
      {
        path: '/cronograma/:id/:visualizar',
        name: 'Visualizar Cronograma',
        component: CronogramaForm
      },
      {
        path: '/cronograma/:id/habilitados/lista',
        name: 'Habilitados',
        component: Habilitados
      },
      {
        path: '/cronograma/:id/classificados/lista',
        name: 'Classificados',
        component: Classificados
      },
      {
        path: '/cronograma/:id/convocados/lista',
        name: 'Convocados',
        component: Convocados
      },
      // Cronograma arquivo estadual
      {
        path: '/arquivos-classificacao-estadual/:cronograma',
        name: 'Arquivos de Classificação Estadual/Distrital',
        component: ArquivosClassificacaoEstadual
      },
      {
        path: '/arquivo-classificacao-estadual/:cronograma/cadastrar',
        name: 'Cadastrar Arquivo de Classificação Estadual/Distrital',
        component: ArquivoClassificacaoEstadualForm
      },
      // Cronograma arquivo municipaç
      {
        path: '/arquivos-classificacao-municipal/:cronograma',
        name: 'Arquivos de Classificação Municipal',
        component: ArquivosClassificacaoMunicipal
      },
      {
        path: '/arquivo-classificacao-municipal/:cronograma/cadastrar',
        name: 'Cadastrar Arquivo de Classificação Municipal',
        component: ArquivoClassificacaoMunicipalForm
      },

      // Adesões
      {
        path: '/adesao',
        name: 'Adesões',
        component: Adesao
      },
      {
        path: '/minhas-adesoes',
        name: 'Minhas Adesões',
        component: MinhasAdesoes
      },
      {
        path: '/acompanhar-cronogramas',
        name: 'Acompanhar Cronogramas',
        component: AcompanharAdesoes
      },
      {
        path: '/adesao/cadastrar/:id',
        name: 'Cadastrar Adesão',
        component: AdesaoForm
      },
      {
        path: '/adesao/:id/editar',
        name: 'Editar Adesão',
        component: AdesaoEditar
      },
      {
        path: '/adesao/:id/avaliar',
        name: 'Avaliar Adesão',
        component: AdesaoAvaliar
      },
      {
        path: '/adesao-acompanhar/:id/:cronograma',
        name: 'Acompanhar Cronograma',
        component: AdesaoAcompanhar
      },
      {
        path: '/adesao-recurso-habilitacao/:id',
        name: 'Analisar Recurso de Habilitação',
        component: AdesaoAvaliarRecursoAdesao
      },
      {
        path: '/adesao-recurso-classificacao/:id',
        name: 'Analisar Recurso de Classificação',
        component: AdesaoAvaliarRecursoClassificacao
      },
      {
        path: '/adesao-convocacao/:id',
        name: 'Analisar Documento de Convocação',
        component: AdesaoAnalisarConvocacao
      },
      {
        path: '/instituicoes-habilitadas',
        name: 'Instituições Habilitadas',
        component: InstituicoesHabilitadas
      },

      // Configuração Geral
      {
        path: '/configuracao-geral',
        name: 'Configuração Geral',
        component: ConfiguracaoGeralForm
      },
      // Bens e equipamentos
      {
        path: '/bens-equipamentos',
        name: 'Bens e quipamentos',
        component: BensEquipamentos,
        meta: {
          breadcrumb: ['Página Inicial', 'BensEquipamentos']
        }
      },
      {
        path: '/bens-equipamentos/cadastrar',
        name: 'Cadastro de Bens e Equipamentos',
        component: BensEquipamentoForm,
        meta: {
          breadcrumb: ['Página Inicial', 'CadastroBensEquipamentos']
        }
      },
      {
        path: '/bens-equipamentos/:id/editar',
        name: 'Editar Bens e Equipamentos',
        component: BensEquipamentoForm,
        meta: {
          breadcrumb: ['Página Inicial', 'Editar BensEquipamentos']
        }
      }

    ]
  },
  {
    path: '/',
    component: Login
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/logout',
    component: Login
  },
  {
    path: '/external-redirect',
    name: 'Área Externa',
    component: LoginRedirect
  },
  {
    path: '/solicitacao-credenciamento',
    component: SolicitacaoExterna
  },
  {
    path: '/apresentacao',
    component: Apresentacao
  },
  {
    path: '/programa-externo/:id',
    component: ProgramaExterno
  },
  {
    path: '/cronograma-externo/:id',
    component: CronogramaExterno
  }
]

export default routes
