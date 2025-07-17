const state = {
  filters: {},
  page: {}
}

const mutations = {
  setFilter (state, { page, filter }) {
    state.filters[page] = filter
  },
  setPageFilters (state, { page, number }) {
    state.page[page] = number
  }
}

const getters = {
  getPageFilter: state => page => {
    return state.filters[page]
  },
  getPage: state => page => {
    return state.page[page]
  }
}

const actions = {
  updateFilter (context, { page, filter }) {
    context.commit('setFilter', { page, filter })
  },
  updatePage (context, { page, number }) {
    context.commit('setPageFilters', { page, number })
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
