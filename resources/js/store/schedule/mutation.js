import type from './type'

const mutations = {
  [type.SET_ALL_AIRCRAFT_FLIGHTS] (state, data) {
    state.aircraft_flights = data.aircraft_flights;
  },
};
export default mutations;