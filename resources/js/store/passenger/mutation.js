import type from './type'

const mutations = {
  [type.SET_ALL_PASSENGERS] (state, data) {
    state.passengers = data.passengers;
  },
  [type.SET_PASSENGER] (state, data) {
    state.passenger = data.passenger;
  },
  [type.SET_FLIGHT_PASSENGER] (state, data) {
    state.flight_passenger = data.flight_passenger;
  },
};
export default mutations;