import Vue from 'vue';
import Vuex from 'vuex';
import authModule from './auth';
import userModule from './user';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth: authModule,
    user: userModule,
  },
});