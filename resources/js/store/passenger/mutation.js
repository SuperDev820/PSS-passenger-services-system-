import type from './type'

const mutations = {
  [type.SET_ALL_PASSENGERS] (state, data) {
    state.passengers = data.passengers;
  },
  [type.SET_PASSENGER] (state, data) {
    state.passenger = data.passenger;
  },
};
export default mutations;