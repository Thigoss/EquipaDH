import Cidade from '@/vuex/modules/cidade'
import Deficiencia from '@/vuex/modules/deficiencia'
import Escolaridade from '@/vuex/modules/escolaridade'
import Estado from '@/vuex/modules/estado'
import Loader from '@/vuex/modules/loader'
import OrientacaoSexual from '@/vuex/modules/orientacaoSexual'
import Pagination from '@/vuex/modules/pagination'
import Usuario from '@/vuex/modules/usuario'
import Perfil from '@/vuex/modules/perfil'
import Permissao from '@/vuex/modules/permissao'
import Raca from '@/vuex/modules/raca'
import Sexo from '@/vuex/modules/sexo'
import Token from '@/vuex/modules/token'
import Instituicao from '@/vuex/modules/instituicao'
import Unidade from '@/vuex/modules/unidade'
import Programa from '@/vuex/modules/programa'
import Filters from '@/vuex/modules/filters'
import Cronograma from '@/vuex/modules/cronograma'

export default {
  modules: {
    cidade: Cidade,
    deficiencia: Deficiencia,
    escolaridade: Escolaridade,
    estado: Estado,
    loader: Loader,
    orientacaoSexual: OrientacaoSexual,
    pagination: Pagination,
    perfil: Perfil,
    permissao: Permissao,
    raca: Raca,
    sexo: Sexo,
    token: Token,
    usuario: Usuario,
    instituicao: Instituicao,
    unidade: Unidade,
    programa: Programa,
    filters: Filters,
    cronograma: Cronograma
  }
}
