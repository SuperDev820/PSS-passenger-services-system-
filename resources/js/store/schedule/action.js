import ApiService from "@/api/api.service";
import type from './type';

const actions = {
    getAircraftFlights(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/schedule/aircraft_flights")
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_ALL_AIRCRAFT_FLIGHTS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    saveAircraftFlight(context, data) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/schedule/save", data)
                .then((data) => {
                    resolve(data);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
};

export default actions;