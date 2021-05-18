<template>
  <div>
    <base-header class="pb-6">
      <b-row class="align-items-center py-4">
        <b-col lg="6" cols="7">
          <h6 class="h2 text-white d-inline-block mb-0">Edit Aircraft</h6>
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
              <h3 slot="header" class="mb-0">Edit Aircraft</h3>
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
                                label="First Name"
                                placeholder="First Name"
                                name="FirstName"
                                :rules="{required: true}"
                                v-model="model.first_name">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-hat-3"
                                label="Last Name"
                                placeholder="Last Name"
                                name="LastName"
                                :rules="{required: true}"
                                v-model="model.last_name">
                    </base-input>

                    <base-input prepend-icon="fas fa-calendar" name="Birthday" :rules="{required: true}">
                      <flat-picker slot-scope="{focus, blur}"
                                    @on-open="focus"
                                    @on-close="blur"
                                    class="form-control datepicker"
                                    v-model="model.birthday">
                      </flat-picker>
                    </base-input>

                    <base-input prepend-icon="fas fa-phone"
                                placeholder="Phone"
                                name="Phone"
                                :rules="{required: true}"
                                v-model="model.phone">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="fas fa-globe-americas"
                                placeholder="Company"
                                name="Company"
                                :rules="{required: true}"
                                v-model="model.company">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-email-83"
                                label="Email"
                                placeholder="Email"
                                name="Email"
                                :rules="{required: true, email: true}"
                                v-model="model.email">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-lock-circle-open"
                                label="Password"
                                placeholder="password"
                                type="password"
                                name="Password"
                                v-model="model.password">
                    </base-input>

                    <base-input label="Status">
                      <el-select v-model="model.status"
                                 filterable
                                 placeholder="Status"
                                 :rules="{required: true}">
                        <el-option v-for="option in statusOptions"
                                   :key="option.label"
                                   :label="option.label"
                                   :value="option.value">
                        </el-option>
                      </el-select>
                    </base-input>
                  </div>
                  <div class="d-flex justify-content-between col-12 mt-4">
                    <router-link :to="{name: 'Aircrafts'}" class="btn btn-secondary">Cancel</router-link>
                    <b-button type="submit" variant="primary">Update</b-button>
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
      title: "Edit Aircraft",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      [Select.name]: Select,
      [Option.name]: Option,
      flatPicker,
    },
    data() {
      return {
        statusOptions: [
          {
            label: 'Active',
            value: 1
          },
          {
            label: 'Deactive',
            value: 0
          },
        ],
        model: {
          first_name: '',
          last_name: '',
          phone: '',
          company: '',
          birthday: '',
          email: '',
          password: '',
          status: 0,
        },
        error: null,
        isError: false,
      }
    },
    mounted() {
      this.getAircraftById(this.$route.params.aircraftId);
    },
    watch: {
      aircraft: function () {
        this.model.first_name = this.aircraft.first_name;
        this.model.last_name = this.aircraft.last_name;
        this.model.phone = this.aircraft.phone;
        this.model.company = this.aircraft.company;
        this.model.birthday = this.aircraft.birthday;
        this.model.email = this.aircraft.email;
        this.model.status = this.aircraft.status;
      },
    },
    computed: {
      ...mapGetters([
        'aircraft'
      ]),
    },
    methods: {
      ...mapActions([
        'getAircraftById',
        'updateAircraft',
      ]),

      onSubmit() {
        console.log(this.model.name)
        // this will be called only after form is valid. You can do an api call here to register aircrafts
        // Reset the error if it existed.
        this.error = null;
        return (
          this.updateAircraft({
              id: this.$route.params.aircraftId,
              first_name: this.model.first_name,
              last_name: this.model.last_name,
              phone: this.model.phone,
              company: this.model.company,
              birthday: this.model.birthday,
              email: this.model.email,
              password: this.model.password,
              password_confirmation: this.model.password,
              status: this.model.status,
            })
            .then((res) => {
              this.isError = false;
              this.$notify({
                message: 'Successfully Updated',
                timeout: 5000,
                icon: 'ni ni-bell-55',
                type: 'success'
              });
              this.$router.push(
                { name: "Aircrafts" }
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
