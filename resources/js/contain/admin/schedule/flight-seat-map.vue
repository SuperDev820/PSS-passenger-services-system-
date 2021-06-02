<template>    
  <div class="content">
    <base-header class="pb-6">
      <b-row align-v="center" class="py-4">
        <b-col cols="7" lg="6">
          <h6 class="h2 text-white d-inline-block mb-0">Aircraft Seat Map</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <route-bread-crumb></route-bread-crumb>
          </nav>
        </b-col>
        <b-col lg="6" cols="5" class="text-right">
          <router-link :to="{name: 'Schedule'}" class="btn btn-neutral btn-sm">
            <i class="ni ni-curved-prev"></i>Go Back
          </router-link>
        </b-col>
      </b-row>
    </base-header>
    <b-container fluid class="mt--6">
      <div>
        <card class="no-border-card" body-classes="px-0 pb-1" footer-classes="pb-2">
          <template slot="header">
            <h3 class="mb-0">Seat Map</h3>
          </template>
          <div class="row">
            <b-col cols="12" class="mb-5">
              <h4 class="px-4 mb-3">Flight Info</h4>
              <el-table class="table-responsive align-items-center table-flush"
                        header-row-class-name="thead-light"
                        :data="flightTableData">
                <el-table-column label="Flight No."
                             prop="airline"
                             min-width="140px">
                  <div slot-scope="{row}">
                    {{row.flight.airline_code + row.flight.flight_number}}
                  </div>
                </el-table-column>
                <el-table-column label="Aircraft"
                              prop="aircraft"
                              min-width="120px">
                  <div slot-scope="{row}">
                    {{row.aircraft.registration}}
                  </div>
                </el-table-column>
                <el-table-column label="From"
                              prop="origin_airport_name"
                              min-width="120px">
                  <div slot-scope="{row}">
                    {{row.flight.origin_airport_name}}
                  </div>
                </el-table-column>
                <el-table-column label="To"
                              prop="destination_airport_name"
                              min-width="120px">
                  <div slot-scope="{row}">
                    {{row.flight.destination_airport_name}}
                  </div>
                </el-table-column>
                <el-table-column label="Departure"
                              prop="departure_time"
                              min-width="120px">
                  <div slot-scope="{row}">
                    {{row.date + 'T' + row.departure_time}}
                  </div>
                </el-table-column>
                <el-table-column label="Arrival"
                              prop="arrival_time"
                              min-width="120px">
                  <div slot-scope="{row}">
                    {{row.date + 'T' + row.arrival_time}}
                  </div>
                </el-table-column>
                <el-table-column label="Duration"
                              prop="flight_time"
                              min-width="100px">
                  <div slot-scope="{row}">
                    {{row.flight_time}}
                  </div>
                </el-table-column>
                <el-table-column label="Type"
                              prop="type"
                              min-width="100px">
                  <div slot-scope="{row}">
                    <span class="text-primary" v-if="row.flight.type == 'REGULAR'">REGULAR</span>
                    <span class="text-warning" v-else>CHARTER</span>
                  </div>
                </el-table-column>
              </el-table>
            </b-col>
            <b-col cols="6" class="">
              <h4 class="px-4 mb-3">Passenger Table</h4>
              <b-col cols="12" class="d-flex justify-content-center justify-content-sm-between flex-wrap"
              >
                <el-select
                  class="select-primary pagination-select"
                  v-model="pagination.perPage"
                  placeholder="Per page"
                >
                  <el-option
                    class="select-primary"
                    v-for="item in pagination.perPageOptions"
                    :key="item"
                    :label="item"
                    :value="item"
                  >
                  </el-option>
                </el-select>

                <div>
                  <base-input v-model="searchQuery"
                              prepend-icon="fas fa-search"
                              placeholder="Search...">
                  </base-input>
                </div>
              </b-col>
              <el-table :data="queriedData"
                        row-key="id"
                        header-row-class-name="thead-light"
                        @sort-change="sortChange">
                <el-table-column label="First Name"
                              prop="first_name"
                              min-width="140px"
                              sortable>
                  <div slot-scope="{row}">
                    {{row.passenger.first_name}}
                  </div>
                </el-table-column>
                <el-table-column label="Last Name"
                              prop="last_name"
                              min-width="140px"
                              sortable>
                  <div slot-scope="{row}">
                    {{row.passenger.last_name}}
                  </div>
                </el-table-column>
                <el-table-column label="Seat"
                              prop="seat"
                              min-width="120px"
                              sortable>
                </el-table-column>
                <el-table-column label="Reference"
                              prop="book_reference"
                              min-width="140px">
                </el-table-column>
              </el-table>
              <b-col cols="12"
                class="d-flex justify-content-center justify-content-sm-between flex-wrap"
              >
                <div class="">
                  <p class="card-category" v-if="total != 0">
                    Showing {{ from + 1 }} to {{ to }} of {{ total }} entries

                    <span v-if="selectedRows.length">
                      &nbsp; &nbsp; {{selectedRows.length}} rows selected
                    </span>
                  </p>
                </div>
                <base-pagination
                  class="pagination-no-border"
                  :current="pagination.currentPage"
                  :per-page="pagination.perPage"
                  :total="total"
                  @change="paginationChanged($event)"
                >
                </base-pagination>
              </b-col>
            </b-col>
            <b-col cols="6" class="">
              <div class="plane">
                <div class="cockpit">
                  <h1>Seat Selection</h1>
                </div>
                <div class="exit exit--front fuselage">
                </div>
                <ol class="cabin fuselage">
                  <li class="row" v-for="i in 20" :key="i">
                    <ol class="seats" type="A">
                      <li class="seat">
                        <input type="checkbox" v-model="seat[i+'A']" :id="i+'A'" />
                        <label :for="i+'A'">{{i}}A</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seat[i+'B']" :id="i+'B'" @change="handleSeat" />
                        <label :for="i+'B'">{{i}}B</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seat[i+'C']" :id="i+'C'" />
                        <label :for="i+'C'">{{i}}C</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seat[i+'D']" :id="i+'D'" />
                        <label :for="i+'D'">{{i}}D</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" v-model="seat[i+'E']" :id="i+'E'" />
                        <label :for="i+'E'">{{i}}E</label>
                      </li>
                    </ol>
                  </li>
                </ol>
                <div class="exit exit--back fuselage">
                </div>
              </div>   
            </b-col>
          </div>
        </card>
      </div>
    </b-container>
    </div>
</template>
<script>
import { Table, TableColumn, Select, Option } from 'element-ui';
import RouteBreadCrumb from '@/components/Breadcrumb/RouteBreadcrumb'
import { BasePagination } from '@/components';
import clientPaginationMixin from '@/common/PaginatedTables/clientPaginationMixin'
import swal from 'sweetalert2';

import {mapActions, mapGetters} from 'vuex'

export default {
  page: {
    title: "Seat Map",
    meta: [{ name: "description", content: "" }]
  },
  mixins: [clientPaginationMixin],
  components: {
    BasePagination,
    RouteBreadCrumb,
    [Select.name]: Select,
    [Option.name]: Option,
    [Table.name]: Table,
    [TableColumn.name]: TableColumn
  },
  data() {
    return {
      seat: [],
      propsToSearch: ['first_name', 'last_name'],
      tableData: [],
      flightTableData: [],
      selectedRows: []
    };
  },
  watch: {
    flightPassengers: function() {
      this.tableData = this.flightPassengers;
      this.flightPassengers.forEach(function(item, index) {
        document.getElementById(item.seat).disabled = true
      });
    },
    aircraftFlight: function() {
      this.flightTableData = this.aircraftFlight;
    },
  },
  computed: {
    ...mapGetters([
      'flightPassengers',
      'aircraftFlight',
    ]),
  },
  mounted() {
    this.getFlightPassengers(this.$route.params.flightId);
  },
  methods: {
    ...mapActions([
      'getFlightPassengers',
    ]),

    paginationChanged(page) {
      this.pagination.currentPage = page
    },
    handleSeat() {
      console.log(this.seat)
    }
  }
};
</script>
<style>
  .no-border-card .card-footer{
    border-top: 0;
  }
</style>
