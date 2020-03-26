import Vue from 'vue'
import Vuex from 'vuex'
import PassportApiToken from './modules/token';
import User from './modules/user';

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    PassportApiToken,
    User
  }
})
