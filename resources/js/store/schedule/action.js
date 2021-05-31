import ApiService from "@/api/api.service";
import type from './type';

const actions = {
    getAircraftFlights(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/flights")
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_ALL_AIRCRAFT_FLIGHTS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
};

export default actions;