import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {},
    standupConfigs: [],
    workspaceMembers: []
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
    },
    getWorkspaceMembers: state => {
      return state.workspaceMembers;
    }
  },
  mutations: {
    standUpConfigs (state, configs) {
      state.standupConfigs = configs;
    },
    saveNewConfig (state, config) {
      state.standupConfigs.push(config);
    },
    updateConfig (state, config) {
      let index = state.standupConfigs.findIndex( item => item.id === config.id);
      //delete config['@context']; // уточнить почему этого нет в других вызовах
      state.standupConfigs[index] = config;
    },
    deleteConfig (state, id) {
      let index = state.standupConfigs.findIndex( item => item.id === id);
      state.standupConfigs.splice(index, 1);
    },
    updateWorkspaceMembers(state, members) {
      state.workspaceMembers = members;
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
    GET_WORKSPACE_MEMBERS({ commit }, id) {
      return axios.get(`/api/configs/${id}/members`)
        .then( (response) => {
          commit('updateWorkspaceMembers', response.data);
        })
        .catch( (error) => {
          console.log(error);
        })
    },
    SAVE_NEW_STANDUP_CONFIG ({ commit }, configData) {
      console.log(configData);
      return axios.post('/api/configs', {
        name: configData.name,
        messageBefore: configData.messageBefore,
        messageAfter: configData.messageAfter,
        questions: configData.questions,
        members: configData.members
      }).then( (response) => {
        commit('saveNewConfig', response.data);
      }).catch( (error) => {
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
        console.log(error)
      })
    },
    DELETE_STANDUP_CONFIG({ commit }, id) {
      return axios.delete(`/api/configs/${id}`).then( () => {
        commit('deleteConfig', id);
      }).catch( (error) => {
        console.log(error);
      })
    }
  },
  modules: {
  }
})
