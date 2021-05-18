import ApiService from "@/api/api.service";
import type from './type';

const actions = {
    initPassengers(context) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/passengers")
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_ALL_PASSENGERS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    getPassengerById(context, passengerId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.get("api/v1/admin/passenger/" + passengerId)
                .then(({data}) => {
                    console.log(data);
                    context.commit(type.SET_PASSENGER, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
    createPassenger(context, passengerInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.post("api/v1/admin/passenger/create", passengerInfo)
                .then((data) => {
                    resolve(data);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    updatePassenger(context, passengerInfo) {
        ApiService.setHeader();
        return new Promise((resolve, reject) => {
            ApiService.put("api/v1/admin/passenger/update", passengerInfo)
                .then((data) => {
                    resolve(data);
                })
                .catch(({response, status}) => {
                    console.log(response);
                    reject(response);
                });
        });
    },
    deletePassenger(context, passengerId) {
        ApiService.setHeader();
        return new Promise((resolve) =>{
            ApiService.delete("api/v1/admin/passenger/delete/" + passengerId)
                .then(({data}) => {
                    context.commit(type.SET_ALL_PASSENGERS, data)
                })
                .catch(({ response }) => {
                    // context.commit(type.AUTH_LOGOUT);
                });
        });
    },
};

export default actions;