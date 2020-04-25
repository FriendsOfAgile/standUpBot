import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {},
    standupConfigs: []
  },
  getters: {
    getUserData: state => {
      return state.user;
    }
  },
  mutations: {
    userConfigs (state, configs) {
      state.standupConfigs = configs;
    }
  },
  actions: {
    getStandUpConfigs ({ commit } , configs) {
      commit('userConfigs', configs)
    }
  },
  modules: {
  }
})
