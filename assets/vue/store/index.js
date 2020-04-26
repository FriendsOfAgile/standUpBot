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
    },
    getStandUpConfigs: state => {
      return state.standupConfigs;
    },
    getStandUpConfigData: (state) => (id)  => {
      return state.standupConfigs.find(config => config.id === id);
    }
  },
  mutations: {
    standUpConfigs (state, configs) {
      state.standupConfigs = configs;
    }
  },
  actions: {
    GET_STANDUP_CONFIGS ({ commit }) {
      return axios.get('/api/configs')
        .then(function (response) {
          commit('standUpConfigs', response.data['hydra:member']);
        })
        .catch(function (error) {
          console.log(error);
        })
    }
  },
  modules: {
  }
})
