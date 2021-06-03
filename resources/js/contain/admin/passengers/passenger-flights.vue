<template>    
  <div class="content">
    <base-header class="pb-6">
      <b-row align-v="center" class="py-4">
        <b-col cols="7" lg="6">
          <h6 class="h2 text-white d-inline-block mb-0">Flight With Us</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <route-bread-crumb></route-bread-crumb>
          </nav>
        </b-col>
        <b-col lg="6" cols="5" class="text-right">
          <router-link :to="{name: 'Passengers'}" class="btn btn-neutral btn-sm">
            <i class="far fa-hand-point-left"></i> Go Back
          </router-link>
        </b-col>
      </b-row>
    </base-header>
    <b-container fluid class="mt--6">
      <div>
        <card class="no-border-card" body-classes="px-0 pb-1" footer-classes="pb-2">
          <template slot="header">
            <h3 class="mb-0">Flights</h3>
          </template>
          <div class="row">
            <b-col cols="12" class="mb-5">
              <el-table class="table-responsive align-items-center table-flush"
                        header-row-class-name="thead-light"
                        :data="tableData">
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
      propsToSearch: [],
      tableData: [],
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
