<template>
  <div>
    <base-header class="pb-6">
      <b-row class="align-items-center py-4">
        <b-col lg="6" cols="7">
          <h6 class="h2 text-white d-inline-block mb-0">Add Flight</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <route-breadcrumb/>
          </nav>
        </b-col>
      </b-row>
    </base-header>

    <b-container fluid class="mt--6">
      <b-row>
        <b-col lg="12">
          <div class="card-wrapper">
            <!-- Input groups -->
            <card>
              <!-- Card header -->
              <h3 slot="header" class="mb-0">Add Flight</h3>
              <!-- Card body -->
              <base-alert v-if="isError" dismissible type="danger">
                <strong>Failed!</strong> {{error}}
              </base-alert>
              <validation-observer v-slot="{handleSubmit}" ref="formValidator">
                <b-form role="form" class="row" @submit.prevent="handleSubmit(onSubmit)">
                  <div class="col-8 offset-2">
                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Airline"
                                placeholder="Airline"
                                name="Airline"
                                :rules="{required: true}"
                                v-model="model.airline">
                    </base-input>

                    <base-input label="Aircraft Registration">
                      <el-select v-model="model.aircraft"
                                 filterable
                                 placeholder="Aircraft Registration"
                                 :rules="{required: true}">
                        <el-option v-for="option in aircraftOptions"
                                   :key="option"
                                   :label="option"
                                   :value="option">
                        </el-option>
                      </el-select>
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Origin Airport"
                                placeholder="Origin Airport"
                                name="OriginAirport"
                                :rules="{required: true}"
                                v-model="model.origin_airport_name">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Origin Airport IATA Code"
                                placeholder="Origin Airport IATA Code"
                                name="OriginAirportIATACode"
                                :rules="{required: true}"
                                v-model="model.origin_airport_code">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Destination Airport"
                                placeholder="Destination Airport"
                                name="DestinationAirport"
                                :rules="{required: true}"
                                v-model="model.destination_airport_name">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Destination Airport IATA Code"
                                placeholder="Destination Airport IATA Code"
                                name="DestinationAirportIATACode"
                                :rules="{required: true}"
                                v-model="model.destination_airport_code">
                    </base-input>

                    <base-input prepend-icon="fas fa-calendar" label="Departure Time" name="DepartureTime" :rules="{required: true}">
                      <flat-picker slot-scope="{focus, blur}"
                                    @on-open="focus"
                                    @on-close="blur"
                                    :config="configs.dateTimePicker"
                                    class="form-control datepicker"
                                    v-model="model.departure_time">
                      </flat-picker>
                    </base-input>

                    <base-input prepend-icon="fas fa-calendar" label="Arrival Time" name="ArrivalTime" :rules="{required: true}">
                      <flat-picker slot-scope="{focus, blur}"
                                    @on-open="focus"
                                    @on-close="blur"
                                    :config="configs.dateTimePicker"
                                    class="form-control datepicker"
                                    v-model="model.arrival_time">
                      </flat-picker>
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="fas fa-globe-americas"
                                label="Flight Time"
                                placeholder="Flight Time"
                                name="FlightTime"
                                type="number"
                                step="0.1"
                                min="0"
                                :rules="{required: true}"
                                v-model="model.flight_time">
                    </base-input>
                  </div>
                  <div class="d-flex justify-content-between col-12 mt-4">
                    <router-link :to="{name: 'Flights'}" class="btn btn-secondary">Cancel</router-link>
                    <b-button type="submit" variant="primary">Create</b-button>
                  </div>
                </b-form>
              </validation-observer>
            </card>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
  import { Select, Option } from 'element-ui'
  import flatPicker from "vue-flatpickr-component";
  import "flatpickr/dist/flatpickr.css";

  import {mapActions, mapGetters} from 'vuex'

  export default {
    page: {
      title: "Create Flight",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      [Select.name]: Select,
      [Option.name]: Option,
      flatPicker,
    },
    data() {
      return {
        configs: {
          dateTimePicker: {
            enableTime: true,
            dateFormat: 'Y-m-d H:i'
          },
        },
        model: {
          airline: '',
          aircraft: '',
          origin_airport_name: '',
          origin_airport_code: '',
          destination_airport_name: '',
          destination_airport_code: '',
          departure_time: '',
          arrival_time: '',
          flight_time: '',
        },
        error: null,
        isError: false,
      }
    },
    mounted() {
      this.getAircraftOptions();
    },
    computed: {
      ...mapGetters([
        'aircraftOptions',
      ]),
    },
    methods: {
      ...mapActions([
        'createFlight',
        'getAircraftOptions',
      ]),

      onSubmit() {
        console.log(this.model.departure_time)
        // this will be called only after form is valid. You can do an api call here to register Flights
        // Reset the error if it existed.
        this.error = null;
        return (
          this.createFlight({
              airline: this.model.airline,
              aircraft: this.model.aircraft,
              origin_airport_name: this.model.origin_airport_name,
              origin_airport_code: this.model.origin_airport_code,
              destination_airport_name: this.model.destination_airport_name,
              destination_airport_code: this.model.destination_airport_code,
              departure_time: this.model.departure_time,
              arrival_time: this.model.arrival_time,
              flight_time: this.model.flight_time,
            })
            .then((res) => {
              this.isError = false;
              this.$notify({
                message: 'Successfully Created',
                timeout: 5000,
                icon: 'ni ni-bell-55',
                type: 'success'
              });
              this.$router.push(
                { name: "Flights" }
              );
            })
            .catch((error) => {
              this.error = error ? error : "";
              this.isError = true;
            })
        );
      }
    }
  }
</script>
