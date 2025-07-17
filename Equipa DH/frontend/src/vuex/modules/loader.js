const state = {
  loader: false
}

const mutations = {
  setLoader (state, data) {
    state.lodaer = data
    console.log(state.lodaer)
  }
}

const getters = {
  getLoader: state => state.loader
}

const actions = {
  setLoading (context, data) {
    context.commit('setLoader', data)
  }
}

export default {
  state,
  mutations,
  getters,
  actions
}
