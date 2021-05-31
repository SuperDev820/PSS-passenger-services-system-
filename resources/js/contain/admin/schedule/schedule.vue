<template>
  <div>
    <base-header class="pb-6 content__title content__title--calendar">
      <b-row align-v="center" class="py-4">
        <b-col cols="7" lg="6">
          <h6 class="h2 text-white d-inline-block mb-0">{{$route.name}}</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <route-breadcrumb />
          </nav>
        </b-col>
        <b-col lg="6" class="mt-3 mt-lg-0 text-lg-right" >
          <base-button class="btn btn-neutral btn-sm" @click="onDateClick">
            <i class="fas fa-plus"></i>Add Reservation
          </base-button>
        </b-col>
      </b-row>
    </base-header>
    <b-container fluid class="mt--6">
      <b-row>
        <b-col>
          <!-- Fullcalendar -->
          <b-card no-body class="card-calendar">
            <!-- Card header -->
            <!-- Card body -->
            <b-card-body class="p-0 card-calendar-body">
              <full-calendar :options="calendarOptions"
              >
              </full-calendar>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
      <div>
        <card class="no-border-card" body-classes="px-0 pb-1" footer-classes="pb-2">
          <template slot="header">
            <h3 class="mb-0">Flights table</h3>
          </template>
          <div>
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
              <el-table-column label="Flight No."
                             prop="airline"
                             min-width="140px"
                             sortable>
                <div slot-scope="{row}">
                  {{row.airline_code + row.flight_number}}
                </div>
              </el-table-column>
              <el-table-column label="Aircraft"
                             prop="aircraft"
                             min-width="120px"
                             sortable>
                <div slot-scope="{row}">
                  {{row.aircraft.registration}}
                </div>
              </el-table-column>
              <el-table-column label="From"
                             prop="origin_airport_name"
                             min-width="120px"
                             sortable>
              </el-table-column>
              <el-table-column label="To"
                             prop="destination_airport_name"
                             min-width="140px"
                             sortable>
              </el-table-column>
              <el-table-column label="Departure"
                             prop="departure_time"
                             min-width="120px"
                             sortable>
              </el-table-column>
              <el-table-column label="Arrival"
                             prop="arrival_time"
                             min-width="120px"
                             sortable>
              </el-table-column>
              <el-table-column label="Duration"
                             prop="flight_time"
                             min-width="100px">
              </el-table-column>
              <el-table-column label="Days Of Ope"
                             prop="operation_days"
                             min-width="120px">
              </el-table-column>
              <el-table-column label="Type"
                             prop="type"
                             min-width="100px">
                <div slot-scope="{row}">
                  <span class="text-primary" v-if="row.type == 'REGULAR'">REGULAR</span>
                  <span class="text-warning" v-else>CHARTER</span>
                </div>
              </el-table-column>
              <el-table-column prop="status" label="Status" min-width="100px">
                <div slot-scope="{row}">
                  <badge class="" v-if="row.status == 1" type="success">
                    <span>Active</span>
                  </badge>
                  <badge class="" v-else type="warning">
                    <span>Deactive</span>
                  </badge>
                </div>
              </el-table-column>
              <el-table-column prop="phase" label="Phase" min-width="100px">
                <div slot-scope="{row}">
                  <badge class="" v-if="row.status == 1" type="success">
                    <span>Active</span>
                  </badge>
                  <badge class="" v-else type="warning">
                    <span>Deactive</span>
                  </badge>
                </div>
              </el-table-column>
              <el-table-column min-width="100px" align="right" label="Actions">
                <div slot-scope="{row}" class="d-flex justify-content-center">
                  <base-button
                    @click.native="goToSeatMap(row)"
                    type="primary"
                    size="sm"
                    icon
                  >
                    <i class="text-white ni ni-curved-next"></i>
                  </base-button>
                </div>
              </el-table-column>
            </el-table>
          </div>
          <div
            slot="footer"
            class="col-12 d-flex justify-content-center justify-content-sm-between flex-wrap"
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
          </div>
        </card>
      </div>
    </b-container>
    
    <modal :show.sync="showAddModal" modal-classes="modal-secondary">
      <form class="new-event--form" @submit.prevent="saveEvent">
        <base-input label="Event title"
                    placeholder="Event Title"
                    v-model="model.title"
                    input-classes="form-control-alternative new-event--title">
        </base-input>
        <div class="form-group">
          <label class="form-control-label d-block mb-3">Status color</label>
          <div class="btn-group btn-group-toggle btn-group-colors event-tag">

            <label v-for="color in eventColors"
                   :key="color"
                   class="btn"
                   :class="[color, {'active focused': model.className === color}]">
              <input v-model="model.className" type="radio" name="event-tag" :value="color" autocomplete="off">
            </label>
          </div>
        </div>
        <input type="hidden" class="new-event--start"/>
        <input type="hidden" class="new-event--end"/>
      </form>

      <template slot="footer">
        <b-button type="submit" variant="primary" class="new-event--add" @click="saveEvent">Add event</b-button>
        <b-button type="button" variant="link" class="ml-auto" @click="showAddModal = false">Close</b-button>
      </template>
    </modal>

    <modal :show.sync="showEditModal" modal-classes="modal-secondary">
      <form class="edit-event--form" @submit.prevent="editEvent">
        <base-input label="Event title"
                    placeholder="Event Title"
                    v-model="model.title"
                    input-classes="form-control-alternative new-event--title">
        </base-input>
        <div class="form-group">
          <label class="form-control-label d-block mb-3">Status color</label>
          <div class="btn-group btn-group-toggle btn-group-colors event-tag">

            <label v-for="color in eventColors"
                   :key="color"
                   class="btn"
                   :class="[color, {'active focused': model.className === color}]">
              <input v-model="model.className" type="radio" name="event-tag" :value="color" autocomplete="off">
            </label>
          </div>
        </div>
        <base-input label="Description">
          <textarea v-model="model.description"
                    class="form-control form-control-alternative edit-event--description textarea-autosize"
                    placeholder="Event Desctiption">
          </textarea>
          <i class="form-group--bar"></i>
        </base-input>
        <input type="hidden" class="new-event--start"/>
        <input type="hidden" class="new-event--end"/>
      </form>

      <template slot="footer">
        <base-button native-type="submit" type="primary" class="new-event--add" @click="editEvent">Update</base-button>
        <base-button type="danger" @click="deleteEvent">Delete</base-button>
        <base-button type="link" class="ml-auto" @click="showEditModal = false">Close</base-button>
      </template>
    </modal>
  </div>
</template>
<script>
  import { Table, TableColumn, Select, Option } from 'element-ui';
  import RouteBreadCrumb from '@/components/Breadcrumb/RouteBreadcrumb'
  import { BasePagination } from '@/components';
  import clientPaginationMixin from '@/common/PaginatedTables/clientPaginationMixin'
  import swal from 'sweetalert2';
  import Modal from '@/components/Modal'
  import FullCalendar from '@fullcalendar/vue'
  // import interactionPlugin from '@fullcalendar/interaction';
  import resourceTimelinePlugin from '@fullcalendar/resource-timeline'
  import BaseButton from '../../../components/BaseButton.vue';

  import {mapActions, mapGetters} from 'vuex'

  const today = new Date();
  const y = today.getFullYear();
  const m = today.getMonth();
  const d = today.getDate();
  var w = today.getDay();
  const yearAndMonthAndDate = `${y}-0${m + 1}-${d}`;
  if (m > 9) {
    yearAndMonthAndDate = `${y}-${m + 1}-${d}`;
  }
  if (w == 0) {
    w = 7;
  }
  export default {
    page: {
      title: "Schedule",
      meta: [{ name: "description", content: "" }]
    },
    mixins: [clientPaginationMixin],
    components: {
      BasePagination,
      RouteBreadCrumb,
      [Select.name]: Select,
      [Option.name]: Option,
      [Table.name]: Table,
      [TableColumn.name]: TableColumn,
      Modal,
      FullCalendar,
      BaseButton
    },
    data() {
      return {
        propsToSearch: [],
        tableColumns: [],
        tableData: [],
        selectedRows: [],
        calendarOptions: {
          eventClick: function(info) {
              console.log(info)
          },
          height: 500,
          plugins: [ resourceTimelinePlugin ],
          headerToolbar: {
              left: 'today',
              center: 'title',
              right: 'prev,next'
          },
          initialView: 'resourceTimelineDay',
          scrollTime: '07:00',
          aspectRatio: 1.5,
          slotDuration: '00:15',
          editable: true,
          filterResourcesWithEvents: false,
          resourceAreaWidth: "15%",
          // droppable: true,
          resourceAreaColumns: [
            {
              field: 'title',
              headerContent: 'Resource'
            }
          ],
          resources: [],
          events: [],
        },
        showAddModal: false,
        showEditModal: false,
        model: {
          title: '',
          className: 'bg-default',
          description: 'Nullam id dolor id nibh ultricies vehicula ut id elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
          start: '',
          end: ''
        },
        eventColors: ['bg-info', 'bg-orange', 'bg-red', 'bg-green', 'bg-default', 'bg-blue', 'bg-purple', 'bg-yellow'],
      };
    },
    watch: {
      aircrafts: function () {
        var that = this;
        this.aircrafts.forEach(function(item, index) {
          let temp = {}
          temp.id = item.id
          temp.title = item.registration + "\n" + item.model
          that.calendarOptions.resources.push(temp)
        })
        this.getAircraftFlights();
      },
      aircraftFlights: function() {
        this.tableData = this.aircraftFlights;

        var that = this;
        // this.aircraftFlights.forEach(function(item, index) {
        //   console.log(w)
        //   if (item.operation_days.includes(w)) {
        //     let temp = {}
        //     temp.resourceId = item.aircraft_id
        //     temp.title = item.airline_code + item.flight_number +", "+ item.origin_airport_code +"-"+ item.destination_airport_code
        //     temp.start = yearAndMonthAndDate +"T"+ item.departure_time
        //     temp.end = yearAndMonthAndDate +"T"+ item.arrival_time
        //     temp.className = that.eventColors[index%8]
        //     that.calendarOptions.events.push(temp)
        //   }
        // })
      },
    },
    computed: {
      ...mapGetters([
        'aircrafts',
        'aircraftFlights',
      ]),
    },
    mounted() {
      this.initAircrafts();
    },
    methods: {
      ...mapActions([
        'initAircrafts',
        'getAircraftFlights'
      ]),

      paginationChanged(page) {
        this.pagination.currentPage = page
      },
      goToSeatMap(row) {
        this.$router.push({ name: 'FlightSeatMap', params: { flightId: row.id }})
      },
      handleEdit(row) {
        this.$router.push({ name: 'FlightEdit', params: { flightId: row.id }})
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
            this.deleteFlight(row.id);
            this.$notify({
              message: 'Successfully Deleted',
              timeout: 5000,
              icon: 'ni ni-bell-55',
              type: 'success'
            });
          }
        });
      },
      calendarApi() {
        return this.$refs.fullCalendar.getApi()
      },
      changeView(viewType) {
        this.defaultView = viewType
        this.calendarApi().changeView(viewType)
      },
      next() {
        this.calendarApi().next()
      },
      prev() {
        this.calendarApi().prev()
      },
      onDateClick({ date }) {
        this.showAddModal = true
        this.model.start = date
        this.model.end = date
      },
      onEventClick({ el, event }) {
        this.model = {
          title: event.title,
          className: event.classNames ? event.classNames.join(' '): '',
          start: event.start,
          end: event.end,
          description: 'Nullam id dolor id nibh ultricies vehicula ut id elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.'
        }
        this.showEditModal = true
      },
      saveEvent() {
        if (this.model.title) {
          let event = {
            ...this.model,
            allDay: true
          }
          this.events.push(JSON.parse(JSON.stringify(event)))

          this.model = {
            title: '',
            eventColor: 'bg-danger',
            start: '',
            end: ''
          }
        }
        this.showAddModal = false
      },
      editEvent() {
        let index = this.events.findIndex(e => e.title === this.model.title)
        if (index !== -1) {
          this.events.splice(index, 1, this.model)
        }
        this.showEditModal = false
      },
      deleteEvent() {
        let index = this.events.findIndex(e => e.title === this.model.title)
        if (index !== -1) {
          this.events.splice(index, 1)
        }
        this.showEditModal = false
      },
    }
  };
</script>
<style lang="scss">
  @import '~@fullcalendar/daygrid/main.css';
  @import '~@fullcalendar/timegrid/main.css';
  @import "~@/assets/sass/core/vendors/fullcalendar";
</style>