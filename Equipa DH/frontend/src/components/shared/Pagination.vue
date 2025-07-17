<template>
  <nav aria-label="Page navigation example">
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
    </ul>
    <p>Exibindo página {{ active }} de {{ total }}, total de {{ totalRegistros }} registros.</p>
  </nav>
</template>

<script>
import { eventType } from '@/components/shared/eventType'
export default {
  name: 'Pagination',
  props: [
    'resource',
    'totalPorPagina',
    'filtros',
    'list',
    'config'
  ],
  created () {
    if (this.resource) {
      if (this.filtros.modal) {
        this.$store.dispatch('getRegistrosSearchModal', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros, list: this.list })
      } else {
        this.$store.dispatch('getRegistrosSearch', { resource: this.resource, limit: this.totalPorPagina, filtros: this.filtros, list: this.list })
      }
    }
  },
  mounted () {
    // eventType.$on('app', (result) => {
    //   this.filtros = result
    // })
  },
  data: function () {
    return {
      active: 1
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
      if (!this.resource && this.config) {
        return this.config.total || 0
      }

      return this.registros.total || 0
    },
    total () {
      if (!this.resource && this.config) {
        return this.config.last_page || 0
      }

      return this.registros.last_page || 0
    },
    currentPage () {
      if (!this.resource && this.config) {
        return this.config.current_page
      }

      return this.registros.current_page
    },
    lastPage () {
      if (!this.resource && this.config) {
        return this.config.last_page
      }

      return this.registros.last_page
    }
  },
  methods: {
    navigate (n) {
      this.active = n
      this.$emit('filtros', this.filtros)

      let config = {
        resource: this.resource,
        limit: this.totalPorPagina,
        page: n,
        filtros: this.filtros,
        tipoLista: this.tipoLista
      }

      if (!this.resource) {
        this.$emit('changePage', n)
      } else {
        if (this.filtros.modal) {
          this.$store.dispatch('getRegistrosSearchModal', config)
        } else {
          this.$store.dispatch('getRegistrosSearch', config)
        }
      }
    }
  }
}
</script>
