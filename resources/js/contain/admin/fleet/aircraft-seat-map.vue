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
          <router-link :to="{name: 'Aircrafts'}" class="btn btn-neutral btn-sm">
            Go Back
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
          <div>
            <b-col cols="12" class=""
            >
              <div class="plane">
                <div class="cockpit">
                  <h1>Seat Selection</h1>
                </div>
                <div class="exit exit--front fuselage">
                  
                </div>
                <ol class="cabin fuselage">
                  <li class="row row--1">
                    <ol class="seats" type="A">
                      <li class="seat">
                        <input type="checkbox" id="1A" />
                        <label for="1A">1A</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="1B" />
                        <label for="1B">1B</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="1C" />
                        <label for="1C">1C</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" disabled id="1D" />
                        <label for="1D">1D</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="1E" />
                        <label for="1E">1E</label>
                      </li>
                    </ol>
                  </li>
                  <li class="row row--2">
                    <ol class="seats" type="A">
                      <li class="seat">
                        <input type="checkbox" id="2A" />
                        <label for="2A">2A</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="2B" />
                        <label for="2B">2B</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="2C" />
                        <label for="2C">2C</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="2D" />
                        <label for="2D">2D</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" id="2E" />
                        <label for="2E">2E</label>
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
    title: "Aircrafts",
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
      propsToSearch: ['name', 'email'],
      tableColumns: [
      ],
      tableData: [],
      selectedRows: []
    };
  },
  watch: {
    aircrafts: function () {
      this.tableData = this.aircrafts;
    },
  },
  computed: {
    ...mapGetters([
      'aircrafts',
    ]),
  },
  mounted() {
    this.initAircrafts();
  },
  methods: {
    ...mapActions([
      'initAircrafts',
      'deleteAircraft',
    ]),

    paginationChanged(page) {
      this.pagination.currentPage = page
    },
    handleEdit(row) {
      this.$router.push({ name: 'AircraftEdit', params: { aircraftId: row.id }})
    },
    handleSeat(row) {
      this.$router.push({ name: 'AircraftSeatMap', params: { aircraftId: row.id }})
    },
    handleDelete(index, row) {
      swal.fire({
        title: `Are you sure?`,
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-warning',
        cancelButtonClass: 'btn btn-secondary btn-fill',
        icon: 'warning'
      }).then(result => {
        if (result.value) {
          this.deleteAircraft(row.id);
          this.$notify({
            message: 'Successfully Deleted',
            timeout: 5000,
            icon: 'ni ni-bell-55',
            type: 'success'
          });
        }
      });
    },
  }
};
</script>
<style>
  .no-border-card .card-footer{
    border-top: 0;
  }
</style>
