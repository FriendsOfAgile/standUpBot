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
    },
    updateConfig (state, config) {
      let index = state.standupConfigs.findIndex( item => item.id === config.id);
      //delete config['@context']; // уточнить почему этого нет в других вызовах
      state.standupConfigs[index] = config;
    },
    deleteConfig (state, id) {
      let index = state.standupConfigs.findIndex( item => item.id === id);
      state.standupConfigs.splice(index, 1);
    }
  },
  actions: {
    GET_STANDUP_CONFIGS ({ commit }) {
      return axios.get('/api/configs')
        .then( (response) => {
          commit('standUpConfigs', response.data['hydra:member']);
        })
        .catch( (error) => {
          console.log(error);
        })
    },
    UPDATE_STANDUP_CONFIG ({ commit }, configData) {
      return axios.put(`/api/configs/${configData.id}`, {
        name: configData.name,
        messageBefore: configData.messageBefore,
        messageAfter: configData.messageAfter,
        questions: configData.questions,
        members: configData.members
      }).then( (response) => {
        commit('updateConfig', response.data);
      }).catch( (error) => {
      })
    },
    DELETE_STANDUP_CONFIG({ commit }, id) {
      return axios.delete(`/api/configs/${id}`).then( () => {
        commit('deleteConfig', id);
      })
    }
  },
  modules: {
  }
})
