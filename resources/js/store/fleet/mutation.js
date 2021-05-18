import type from './type'

const mutations = {
  [type.SET_ALL_AIRCRAFTS] (state, data) {
    state.aircrafts = data.aircrafts;
  },
  [type.SET_AIRCRAFT] (state, data) {
    state.aircraft = data.aircraft;
  },
};
export default mutations;