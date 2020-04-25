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
      console.log('configs ', state.standupConfigs);
    }
  },
  actions: {
    getStandUpConfigs ({ commit }) {
      return axios.get('/api/configs')
        .then(function (response) {
          commit('userConfigs', response.data);
        })
        .catch(function (error) {
          console.log(error);
        })
    }
  },
  modules: {
  }
})
