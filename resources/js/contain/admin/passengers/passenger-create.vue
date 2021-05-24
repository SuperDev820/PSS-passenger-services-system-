<template>
  <div>
    <base-header class="pb-6">
      <b-row class="align-items-center py-4">
        <b-col lg="6" cols="7">
          <h6 class="h2 text-white d-inline-block mb-0">Add Passenger</h6>
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
              <h3 slot="header" class="mb-0">Add Passenger</h3>
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

                    <base-input prepend-icon="fas fa-calendar" label="Birthday" name="Birthday" :rules="{required: true}">
                      <flat-picker slot-scope="{focus, blur}"
                                    @on-open="focus"
                                    @on-close="blur"
                                    class="form-control datepicker"
                                    v-model="model.birthday">
                      </flat-picker>
                    </base-input>

                    <base-input prepend-icon="fas fa-phone"
                                label="Phone Number"
                                placeholder="Phone"
                                name="Phone"
                                type="tel"
                                :rules="{required: true}"
                                v-model="model.phone">
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
                                prepend-icon="fas fa-globe-americas"
                                label="Company"
                                placeholder="Company"
                                name="Company"
                                :rules="{required: true}"
                                v-model="model.company">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="fas fa-clone"
                                label="Roster"
                                placeholder="Roster"
                                name="Roster"
                                v-model="model.roster">
                    </base-input>

                    <base-input alternative
                                class="mb-3"
                                prepend-icon="ni ni-lock-circle-open"
                                label="Password"
                                placeholder="password"
                                type="password"
                                name="Password"
                                :rules="{required: true, min: 6}"
                                v-model="model.password">
                    </base-input>
                  </div>
                  <div class="d-flex justify-content-between col-12 mt-4">
                    <router-link :to="{name: 'Passengers'}" class="btn btn-secondary">Cancel</router-link>
                    <b-button type="submit" variant="primary">Create</b-button>
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
      title: "Create Passenger",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      [Select.name]: Select,
      [Option.name]: Option,
      flatPicker,
    },
    data() {
      return {
        roleOptions: [
          {
            label: 'Admin',
            value: 'Admin'
          },
          {
            label: 'Passenger',
            value: 'Passenger'
          },
        ],
        model: {
          first_name: '',
          last_name: '',
          phone: '',
          company: '',
          birthday: '',
          roster: '',
          email: '',
          password: '',
        },
        error: null,
        isError: false,
      }
    },
    methods: {
      ...mapActions([
        'createPassenger',
      ]),

      onSubmit() {
        console.log(this.model.range)
        // this will be called only after form is valid. You can do an api call here to register passengers
        // Reset the error if it existed.
        this.error = null;
        return (
          this.createPassenger({
              first_name: this.model.first_name,
              last_name: this.model.last_name,
              phone: this.model.phone,
              company: this.model.company,
              birthday: this.model.birthday,
              roster: this.model.roster,
              email: this.model.email,
              password: this.model.password,
              password_confirmation: this.model.password
            })
            .then((res) => {
              this.isError = false;
              this.$notify({
                message: 'Successfully Created',
                timeout: 5000,
                icon: 'ni ni-bell-55',
                type: 'success'
              });
              this.$router.push(
                { name: "Passengers" }
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
