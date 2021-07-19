<template>
  <div>
    <!-- Header -->
    <div class="header bg-gradient-success py-6 py-lg-6 pt-lg-7">
      <b-container>
        <div class="header-body text-center mb-7">
          <b-row class="justify-content-center">
            <b-col xl="5" lg="6" md="8" class="px-5">
              <h1 class="text-white">Welcome To Web Checkin!</h1>
            </b-col>
          </b-row>
        </div>
      </b-container>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1"
             xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <b-container class="mt--8 pb-6">
      <b-row class="justify-content-center">
        <b-col lg="5" md="7">
          <b-card no-body class="bg-secondary border-0 mb-0">
            <b-card-body class="px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Or sign in with credentials</small>
              </div>
              <base-alert v-if="isAuthError" dismissible type="danger">
                <strong>Failed!</strong> {{authError}}
              </base-alert>
              <validation-observer v-slot="{handleSubmit}" ref="formValidator">
                <b-form role="form" @submit.prevent="handleSubmit(onSubmit)">
                  <base-input alternative
                              label="Last Name"
                              class="mb-3"
                              name="LastName"
                              :rules="{required: true}"
                              v-model="model.last_name">
                  </base-input>

                  <base-input alternative
                              label="Reference Reservation"
                              class="mb-3"
                              name="Reference"
                              :rules="{required: true, min: 6, max: 6}"
                              v-model="model.reference">
                  </base-input>

                  <div class="text-center">
                    <b-button type="submit" variant="primary" class="mt-4" :disabled="isSubmitting">
                      <i class="fa fa-spinner fa-spin" v-if="isSubmitting"></i> Check In
                    </b-button>
                  </div>
                </b-form>
              </validation-observer>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
  import {mapActions, mapGetters} from 'vuex'

  export default {
    page: {
      title: "Login",
      meta: [{ name: "description", content: "" }]
    },
    data() {
      return {
        model: {
          last_name: '',
          reference: '',
        },
        authError: null,
        isAuthError: false,
        isSubmitting: false,
      };
    },
    computed: {
      ...mapGetters([
        'currentPassenger',
      ]),
    },
    methods: {
      onSubmit() {
        // this will be called only after form is valid. You can do api call here to login
        // Reset the authError if it existed.
        this.authError = null;
        this.isSubmitting = true;
        return (
          this.$store
            .dispatch("login", {
              last_name: this.model.last_name,
              reference: this.model.reference
            })
            .then((res) => {
              // console.log(this.currentPassenger)
              if (this.currentPassenger.role == 'Passenger') {
                this.$router.push({name: "SelectFlight"});
              } else {
                this.$router.push({name: "Login"});
              }
              this.isAuthError = false;
              this.isSubmitting = false;
            })
            .catch(error => {
              // console.log(error)
              this.authError = error ? error : "";
              this.isAuthError = true;
              this.isSubmitting = false;
            })
        );
      }
    }
  };
</script>
