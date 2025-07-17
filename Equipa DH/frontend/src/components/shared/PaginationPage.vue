<template>
  <div class="overflow-auto">
    <nav>
      <ul class="pagination">
        <li class="page-item" v-if="currentPage > 1">
          <a class="page-link" aria-label="Previous" v-on:click.prevent="navigate(currentPage - 1)">
            <span aria-hidden="true">«</span>
          </a>
        </li>
        <li class="page-link">
          {{ currentPage }} de {{ lastPage }}
        </li>
        <li v-if="currentPage < lastPage">
          <a class="page-link" aria-label="Next" v-on:click.prevent="navigate(currentPage + 1)">
            <span aria-hidden="true">»</span>
          </a>
        </li>
        <li>
          <b-form-input type="number" class="navegar-pagina" v-on:keyup.enter="navigatePage" v-model="pagina" />
        </li>
      </ul>
      <p>Exibindo página {{ active }} de {{ total }}, total de {{ totalRegistros }} registros.</p>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'PaginationPage',
  props: [
    'origin',
    'resource',
    'totalPorPagina',
    'filtros',
    'list'
  ],
  created () {
    if (this.origin) {
      this.pagina = this.$store.getters.getPage(this.origin)
      this.active = this.$store.getters.getPage(this.origin)
    }

    if (this.filtros.modal) {
      this.$store.dispatch('getRegistrosSearchModal', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros, list: this.list, page: this.active })
    } else {
      this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros, list: this.list, page: this.active })
    }
  },
  data: function () {
    return {
      active: 1,
      pagina: null
    }
  },
  computed: {
    registros () {
      if (this.filtros.modal) {
        return this.$store.state.pagination.getListModal
      } else {
        if (this.list) {
          let result = this.$store.state.pagination.lists[this.list]
          return result !== undefined ? result : []
        } else {
          return this.$store.state.pagination.getList
        }
      }
    },
    totalRegistros () {
      return this.registros.total || 0
    },
    total () {
      return this.registros.last_page || 0
    },
    currentPage () {
      return this.registros.current_page
    },
    lastPage () {
      return this.registros.last_page
    }
  },
  methods: {
    navigatePage () {
      if (this.pagina > this.total || this.pagina <= 0) {
        this.mensagem('Página não existe.')
        this.pagina = null
        return
      }
      this.active = this.pagina
      this.$emit('filtros', this.filtros)

      if (this.origin) {
        this.$store.dispatch('updatePage', { page: this.origin, number: this.active })
      }

      let config = {
        resource: this.resource,
        limit: this.totalPorPagina,
        page: this.pagina,
        filtros: this.filtros,
        list: this.list
      }

      if (this.filtros.modal) {
        this.$store.dispatch('getRegistrosSearchModal', config)
      } else {
        this.$store.dispatch('getRegistrosSearch', config)
      }
    },
    mensagem (m) {
      this.$bvToast.toast(m, {
        title: 'Erro',
        autoHideDelay: 3000,
        appendToast: true,
        variant: 'danger'
      })
    },
    navigate (n) {
      this.active = n
      this.$emit('filtros', this.filtros)

      if (this.origin) {
        this.$store.dispatch('updatePage', { page: this.origin, number: this.active })
      }

      let config = {
        resource: this.resource,
        limit: this.totalPorPagina,
        page: n,
        filtros: this.filtros,
        list: this.list
      }

      if (this.filtros.modal) {
        this.$store.dispatch('getRegistrosSearchModal', config)
      } else {
        this.$store.dispatch('getRegistrosSearch', config)
      }
    }
  }
}
</script>
<style scoped>
.navegar-pagina {
  border: 1px solid #95a5a66e;
  width: 35%;
  border-radius: 0rem;
}
</style>
