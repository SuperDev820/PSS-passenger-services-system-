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
                  <li class="row" v-for="i in 20" :key="i">
                    <ol class="seats" type="A">
                      <li class="seat">
                        <input type="checkbox" :id="i+'A'" @change="handleSeat" />
                        <label :for="i+'A'">{{i}}A</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" :id="i+'B'" />
                        <label :for="i+'B'">{{i}}B</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" :id="i+'C'" />
                        <label :for="i+'C'">{{i}}C</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" disabled :id="i+'D'" />
                        <label :for="i+'D'">{{i}}D</label>
                      </li>
                      <li class="seat">
                        <input type="checkbox" :id="i+'E'" />
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
      // seats: [[]],
      seat: "",
    };
  },
  watch: {
  },
  computed: {
    ...mapGetters([
    ]),
  },
  mounted() {
  },
  methods: {
    ...mapActions([
    ]),
    handleSeat() {
      console.log(this.seat)
    },
  }
};
</script>
<style>
  .no-border-card .card-footer{
    border-top: 0;
  }
</style>
