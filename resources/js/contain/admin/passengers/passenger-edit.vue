<template>
  <div>
    <base-header class="pb-6">
      <b-row class="align-items-center py-4">
        <b-col lg="6" cols="7">
          <h6 class="h2 text-white d-inline-block mb-0">Edit User</h6>
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
              <h3 slot="header" class="mb-0">Edit User</h3>
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
                                label="Name"
                                placeholder="Name"
                                name="Name"
                                :rules="{required: true}"
                                v-model="model.name">
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

                    <base-input label="Role">
                      <el-select v-model="model.role"
                                :rules="{required: true}"
                                 multiple
                                 filterable
                                 placeholder="Roles">
                        <el-option v-for="option in roleOptions"
                                   :key="option.label"
                                   :label="option.label"
                                   :value="option.value">
                        </el-option>
                      </el-select>
                    </base-input>
                  </div>
                  <div class="d-flex justify-content-between col-12 mt-4">
                    <router-link :to="{name: 'AdminUsers'}" class="btn btn-secondary">Cancel</router-link>
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
  import {mapActions, mapGetters} from 'vuex'

  export default {
    page: {
      title: "Edit User",
      meta: [{ name: "description", content: "" }]
    },
    components: {
      [Select.name]: Select,
      [Option.name]: Option,
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
          name: '',
          email: '',
          password: '',
          role: [],
        },
        error: null,
        isError: false,
      }
    },
    mounted() {
      this.getUserById(this.$route.params.userId);
    },
    watch: {
      user: function () {
        this.model.name = this.user.name;
        this.model.email = this.user.email;
        this.model.role = this.user.roleNames;
      },
    },
    computed: {
      ...mapGetters([
        'user'
      ]),
    },
    methods: {
      ...mapActions([
        'getUserById',
        'updateUser',
      ]),

      onSubmit() {
        console.log(this.model.name)
        // this will be called only after form is valid. You can do an api call here to register users
        // Reset the error if it existed.
        this.error = null;
        return (
          this.updateUser({
              id: this.$route.params.userId,
              name: this.model.name,
              email: this.model.email,
              password: this.model.password,
              password_confirmation: this.model.password,
              roles: this.model.role,
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
                { name: "AdminUsers" }
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
