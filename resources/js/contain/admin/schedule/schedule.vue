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
  const w = today.getDay();
  const yearAndMonthAndDate = `${y}-0${m + 1}-${d}`;
  if (m > 9) {
    yearAndMonthAndDate = `${y}-${m + 1}-${d}`;
  }
  export default {
    name: 'calendar',
    components: {
      Modal,
      FullCalendar,
      BaseButton
    },
    data() {
      return {
        calendarOptions: {
          eventClick: function(info) {
              console.log(info)
          },
          height: 1000,
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
          events: [{
            resourceId: "1",
            title: 'Event 1',
            start: '2021-05-27T20:00:00',
            end: '2021-05-28T02:00:00'
          }
          ],
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
        this.initFlights();
      },
      flights: function() {
        var that = this;
        this.flights.forEach(function(item, index) {
          console.log(w)
          if ((w+1) == item.operation_days) {
            let temp = {}
            temp.resourceId = item.aircraft_id
            temp.title = item.airline_code +" "+ item.flight_number +", "+ item.origin_airport_code +"-"+ item.destination_airport_code
            temp.start = yearAndMonthAndDate +"T"+ item.departure_time
            temp.end = yearAndMonthAndDate +"T"+ item.arrival_time
            temp.className = that.eventColors[index%8]
            that.calendarOptions.events.push(temp)
          }
        })
      },
    },
    computed: {
      ...mapGetters([
        'aircrafts',
        'flights',
      ]),
    },
    mounted() {
      this.initAircrafts();
    },
    methods: {
      ...mapActions([
        'initAircrafts',
        'initFlights'
      ]),

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
      }
    }
  };
</script>
<style lang="scss">
  @import '~@fullcalendar/daygrid/main.css';
  @import '~@fullcalendar/timegrid/main.css';
  @import "~@/assets/sass/core/vendors/fullcalendar";
</style>