<template>
  <div>
    <!-- Header -->
    <div class="header bg-gradient-success py-6 py-lg-7 pt-lg-8">
      <div>
        <div class="header-body mb-6">
          <b-row class="justify-content-center">
            <b-col lg="8" md="9" sm="10" cols="10">
              <h2 class="text-white">Declaration and Confirmation</h2>
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
              <div class="mb-4">
                <p class="mb-0">Before you confirm itinerary, please ensure you are aware of our <span>Dangerous Goods Policy</span>, as well as the <span>Conditions of Carriage</span></p>
                <p class="mb-0">
                  We will not carry, and You must not include in Your hand or checked baggage or otherwise attempt to bring on board, goods or items which are prohibited 
                  by the applicable laws, regulations or orders of any state to be flown from, to or over; goods or items likely to endanger the aircraft or persons or 
                  property on board the aircraft, or items that may become dangerous or are dangerous, such as those specified in the International Civil Aviation 
                  Organization (ICAO) Technical Instructions for the Safe Transport of Dangerous Goods by Air, or the International Air Transport Association (IATA) 
                  Dangerous Goods Regulations or the Our policies or regulations. Further information is available on request.
                </p>
                <p class="mb-0">
                  Without limiting the above, the following types of restricted articles are examples of articles which must not be carried in Your baggage. If You have any doubt 
                  about what You may carry please request more information from Us before you travel.
                </p>
                <ul>
                  <li>
                    Compressed gases e.g. refrigerants, flammable and poisonous such as butane and aqualung cylinders.
                  </li>
                  <li>
                    Corrosives e.g. acids, alkaline, mercury and wet cell batteries.
                  </li>
                  <li>
                    Explosives, munitions, fireworks and flares.
                  </li>
                  <li>
                    Flammable liquids and solids e.g. lighter or heating fuel, matches, paints and articles easily ignited.
                  </li>
                  <li>
                    Infectious substances e.g. live virus substances.
                  </li>
                  <li>
                    Radioactive materials and all poisons e.g. insecticides and weed killers.
                  </li>
                  <li>
                    Medicines and toiletries in limited quantities and alcoholic beverages may be carried in checked or cabin baggage (maximum 2 litres or 2 kg).
                  </li>
                  <li>
                    Firearms and weapons of any type such as blades, knives, sharp items are not permitted in the cabin but may be allowed by the Carrier as checked baggage under certain circumstances.
                  </li>
                  <li>
                    Live animals except as allowed under certain circumstances.
                  </li>
                </ul>
                <p class="mb-0">
                  You must not tender dangerous goods or potentially dangerous goods for Carriage as baggage and must indemnify Us and hold Us harmless 
                  for any loss, claim or damage suffered by Us or third parties caused by your carriage of such goods.
                </p>
                <p class="mb-0">
                  We reserve the right to determine what items are unsuitable for Carriage because they are dangerous, unsafe of because of their weight, size, shape or character, or which are fragile or perishable.
                </p>
                <p class="mb-0">
                  We may do what whatever we consider appropriate in dealing with such goods which includes destroying, abandoning and/or retaining as evidence any such goods. 
                  Your attention is drawn to signage throughout Departure terminals for more information. 
                  Please direct any enquiries for further assistance to Us.
                </p>
                <p class="mb-0">
                  I / We confirm that we are not carrying any dangerous goods on board, that I / We will not have any sharp objects, firearms weapons 
                  on my / our person(s) or in my / our carry on baggage whilst in the aircraft and that I / We have read and understood the <span>Conditions Of Carriage</span> for my / our ticket(s)
                </p>
                <p class="mb-0">
                  I have not presented with any symptoms of covid-19 such as fever, cough, sore throat, shortness of breath in the past 14 days.
                </p>
                <p class="mb-0">
                  I am not currently suspected (being tested) or have been confirmed as a COVID-19 case in the past 14 days?
                </p>
                <p class="mb-0">
                  I have not been in contact with someone that is a suspected (being tested) or confirmed as a COVID-19 case in the past 14 days
                </p>
                <p class="mb-0">
                  I am aware of the current Australian Government regulations to wear a mask within airports and on board the aircraft and am willing to follow this directive.
                </p>
                <p class="mb-0">
                  I have not travelled to, from, or through a Covid-19 hotspot in the past 14 days.
                </p>
              </div>
              <div>
                <div class="table-responsive mt-2 mb-4">
                  <base-table class="table thead-light"
                              :data="flightTableData">
                    <template slot="columns">
                      <th class="text-center" scope="col">Flight No.</th>
                      <th class="text-center" scope="col">Departure Date Time</th>
                      <th class="text-center" scope="col">Origin</th>
                      <th class="text-center" scope="col">Destination</th>
                    </template>
                    <template slot-scope="{row}">
                      <td class="text-center">{{row.aircraft_flight.flight.airline_code+' '+row.aircraft_flight.flight.flight_number}}</td>
                      <td class="text-center">{{row.aircraft_flight.date+' '+row.aircraft_flight.departure_time}}</td>
                      <td class="text-center">
                        {{row.aircraft_flight.flight.origin_airport_name}}<br/>
                      </td>
                      <td class="text-center">
                        {{row.aircraft_flight.flight.destination_airport_name}}<br/>
                      </td>
                    </template>
                  </base-table>
                </div>
                <div class="table-responsive mb-4">
                  <base-table class="table thead-light"
                              :data="passengerTableData">
                    <template slot="columns">
                      <th class="text-center" scope="col">Passenger</th>
                      <th class="text-center" scope="col">Passenger Type</th>
                      <th class="text-center" scope="col">Seat</th>
                    </template>
                    <template slot-scope="{row}">
                      <td class="text-center">{{row.passenger.first_name+' '+row.passenger.last_name}}</td>
                      <td class="text-center">{{row.passenger_type}}</td>
                      <td class="text-center">{{row.seat}}</td>
                    </template>
                  </base-table>
                </div>
              </div>
              <div class="text-center">
                <p class="mb-0">
                  I / We confirm that the itinerary and seating arrangement(s) are correct for check-in
                </p>
              </div>
              <div class="text-right">
                <router-link to="/select-seat" class="btn base-button btn-secondary btn-sm">GO BACK</router-link>
                <base-button size="sm" type="warning" @click="continuePage()">CONFIRM CHECK IN</base-button>
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
  import { Select, Option } from 'element-ui';

  import {mapActions, mapGetters} from 'vuex'

  export default {
    page: {
      title: "Select Flight",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      BaseTable,
      BaseButton,
      [Select.name]: Select,
      [Option.name]: Option,
    },
    data() {
      return {
        flightTableData: [],
        passengerTableData: [],
      }
    },
    watch: {
      passengerInfo: function() {
        this.flightTableData.push(this.passengerInfo);
        this.passengerTableData.push(this.passengerInfo);
      },
    },
    computed: {
      ...mapGetters([
        'passengerInfo',
        'currentPassenger',
      ]),
    },
    mounted() {
      this.initCheckin(this.currentPassenger.book_reference);
    },
    methods: {
      ...mapActions([
        'initCheckin',
      ]),

      continuePage() {
        this.$router.push({name: "BoardingPass"});
      },
    },
  };
</script>
<style lang="scss">
</style>
