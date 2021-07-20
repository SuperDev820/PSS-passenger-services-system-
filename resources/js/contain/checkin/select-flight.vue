<template>
  <div>
    <!-- Header -->
    <div class="header bg-gradient-success py-7 py-lg-8 pt-lg-9">
      <div>
        <div class="header-body text-center mb-7">
          <b-row class="justify-content-center">
            <b-col xl="5" lg="6" md="8" class="px-5">
              <h1 class="text-white">Select Flight for Check-in</h1>
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
            <card body-classes="px-md-3 px-lg-4"
                  class="border-0 text-center">
              <div class="table-responsive">
                <base-table class="table table-dark"
                            :data="flightTableData">
                  <template slot="columns">
                    <th class="text-center" scope="col">Flight No.</th>
                    <th class="text-center" scope="col">Date</th>
                    <th class="text-center" scope="col">Dep</th>
                    <th class="text-center" scope="col">Arr</th>
                    <th class="text-center" scope="col">Status</th>
                  </template>
                  <template slot-scope="{row}">
                    <td class="text-center">{{row.aircraftFlight.flight.airline_code}}</td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </template>
                </base-table>
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
  export default {
    page: {
      title: "Select Flight",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      BaseTable
    },
    data() {
      return {
        flightTableData: [],
      }
    },
    watch: {
      aircraftFlight: function() {
        this.flightTableData = [];
        this.flightTableData.push(this.aircraftFlight);
      },
    },
    computed: {
      ...mapGetters([
        'flightPassengers',
      ]),
    },
    mounted() {
      this.getFlightPassengers(this.$route.params.flightId);
      this.initPassengers();
    },
    methods: {
      ...mapActions([
        'getFlightPassengers',
      ]),

      paginationChanged(page) {
        this.pagination.currentPage = page
      },
    },
  };
</script>
<style></style>
