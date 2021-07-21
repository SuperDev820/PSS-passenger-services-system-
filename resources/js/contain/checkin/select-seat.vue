<template>
  <div>
    <!-- Header -->
    <div class="header bg-gradient-success py-6 py-lg-7 pt-lg-8">
      <div>
        <div class="header-body mb-6">
          <b-row class="justify-content-center">
            <b-col lg="8" md="9" sm="10" cols="10">
              <h2 class="text-white">Select / Change Seating</h2>
            </b-col>
          </b-row>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
             xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="mt--8 pb-5">
      <b-row class="justify-content-center mx-0">
        <b-col lg="8" md="9" sm="10" cols="10" class="px-0">
          <b-card-group>
            <card body-classes="px-md-4 px-lg-5"
                  class="border-0">
              <div class="table-responsive mt-2 mb-4">
                <base-table class="table thead-light"
                            :data="flightTableData">
                  <template slot="columns">
                    <th class="text-center" scope="col">Passenger Name</th>
                    <th class="text-center" scope="col">Seat</th>
                    <th class="text-center" scope="col">Status</th>
                  </template>
                  <template slot-scope="{row}">
                    <td class="text-center">{{row.passenger.first_name+' '+row.passenger.last_name}}</td>
                    <td class="text-center">{{row.seat}}</td>
                    <td class="text-center" v-if="row.status=='CHECKED'">{{row.status}}</td>
                  </template>
                </base-table>
              </div>  
              <div class="mb-4" style="font-weight: 500;font-size: 1rem;color: black;">
                <div class="">
                  <span><i class="far fa-dot-circle" style="font-size: 8px;margin-right: 8px;"></i> Select passenger and click on the map below for the preferred seat</span>
                </div>
                <div class="">
                  <span><i class="far fa-dot-circle" style="font-size: 8px;margin-right: 8px;"></i> To finish, click continue to accept seating</span>
                </div>
              </div>
              <div class="plane">
                <div class="cockpit">
                </div>
                <div class="exit exit--front fuselage">
                </div>
                <ol class="cabin fuselage">
                  <li class="row" v-for="i in 20" :key="i">
                    <ol class="seats" type="A">
                      <li class="seat">
                        <input type="checkbox" v-model="seats[i+'A']" :id="i+'A'" />
                        <label :for="i+'A'">{{i}}A</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seats[i+'B']" :id="i+'B'" />
                        <label :for="i+'B'">{{i}}B</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seats[i+'C']" :id="i+'C'" />
                        <label :for="i+'C'">{{i}}C</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seats[i+'D']" :id="i+'D'" />
                        <label :for="i+'D'">{{i}}D</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seats[i+'E']" :id="i+'E'" />
                        <label :for="i+'E'">{{i}}E</label>
                      </li>
                    </ol>
                  </li>
                </ol>
                <div class="exit exit--back fuselage">
                </div>
              </div>
              <div class="mb-5">
                <ul class="seat-config d-flex justify-content-center">
                  <li>
                    <span class="seat available-seat"></span>
                    <span class="description">Seat Available</span>
                  </li>
                  <li>
                    <span class="seat reserved-seat"></span>
                    <span class="description">Your Seat</span>
                  </li>
                  <li>
                    <span class="seat disabled-seat"></span>
                    <span class="description">Not Available</span>
                  </li>
                </ul>
              </div>
              <div class="text-right">
                <router-link to="/your-flight" class="btn base-button btn-secondary btn-sm">GO BACK</router-link>
                <base-button size="sm" type="warning" @click="continuePage()">CONTINUE</base-button>
              </div>
            </card>
          </b-card-group>
        </b-col>
      </b-row>
    </div>
  </div>
</template>
<script>
  import BaseTable from '@/components/BaseTable';
  import BaseButton from '@/components/BaseButton.vue';

  import {mapActions, mapGetters} from 'vuex'

  export default {
    page: {
      title: "Select Seat",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      BaseTable,
      BaseButton,
    },
    data() {
      return {
        flightTableData: [],
        seats: [],
      }
    },
    watch: {
      passengerInfo: function() {
        this.flightTableData.push(this.passengerInfo);
        this.getFlightPassengers(this.passengerInfo.aircraft_flight_id);
      },
      flightPassengers: function() {
        this.seats = [];
        var that = this;
        this.flightPassengers.forEach(function(item, index) {
          if ((item.seat != null) && (item.seat != '')) {
            if (item.id == that.passengerInfo.id) {
              that.seats[item.seat] = true
            } else {
              document.getElementById(item.seat).disabled = true
            }
          }
        });
      },
    },
    computed: {
      ...mapGetters([
        'passengerInfo',
        'currentPassenger',
        'flightPassengers',
      ]),
    },
    mounted() {
      this.initCheckin(this.currentPassenger.book_reference);
    },
    methods: {
      ...mapActions([
        'initCheckin',
        'getFlightPassengers',
        'passengerSeatSave',
      ]),

      continuePage() {
        let passenger_seats = [];
        for (const [key, value] of Object.entries(this.seats)) {
          if (value == true) {
            passenger_seats.push(key)
          }
        }
        if (passenger_seats.length == 1) {
          this.passengerSeatSave({
              passengerId: this.passengerInfo.passenger_id,
              flightId: this.passengerInfo.aircraft_flight_id,
              seat: passenger_seats[0],
              statusFlag: true,
            })
            .then((res) => {
              // this.$notify({
              //     message: 'Successfully Saved',
              //     timeout: 5000,
              //     icon: 'ni ni-bell-55',
              //     type: 'success'
              //   });
              this.$router.push({name: "Confirm"});
            })
            .catch((error) => {
            })
        } else {
          // console.log(passenger_seats)
          this.$notify({
              message: 'You can select only one seat.',
              timeout: 5000,
              icon: 'ni ni-bell-55',
              type: 'warning'
            });
        }
      },
    },
  };
</script>
<style lang="scss">
  .seat-config {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .seat-config li {
    display: flex;
    align-items: center;
  }
  .seat-config .seat {
    display: block;
    position: relative;
    width: 30px;
    height: 30px;
    padding: 4px 0;
    border-radius: 5px;
  }
  .seat-config .seat.available-seat {
    background: #6acc6a;
  }
  .seat-config .seat.reserved-seat {
    background: #ff844a;
  }
  .seat-config .seat.disabled-seat {
    background: #dddddd;
  }
  .seat-config .seat:before {
    content: "";
    position: absolute;
    width: 75%;
    height: 80%;
    top: 1px;
    left: 50%;
    transform: translate(-50%, 0%);
    background: rgba(255, 255, 255, 0.4);
    border-radius: 3px;
  }
  .seat-config .description {
    padding-left: 15px;
    padding-right: 15px;
  }
</style>
