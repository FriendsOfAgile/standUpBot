import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {}
  },
  getters: {
    getUserData: state => {
      return state.user;
    }
  },
  mutations: {
  },
  actions: {
  },
  modules: {
  }
})
