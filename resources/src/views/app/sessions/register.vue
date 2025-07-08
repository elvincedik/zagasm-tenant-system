<template>
  <div class="auth-layout-wrap" v-if="logo">
    <div class="auth-content">
      <div class="card o-hidden">
        <div class="row">
          <div class="col-md-12">
            <div class="p-4">
              <div class="auth-logo text-center mb-30">
                <img :src="logo" alt="logo" />
              </div>
              <h1 class="mb-3 text-18">Register</h1>

              <validation-observer ref="register_form">
                <b-form @submit.prevent="submitRegister">
                  <!-- Organization Name -->
                  <validation-provider name="Organization Name" rules="required" v-slot="validationContext">
                    <b-form-group label="Organization Name" class="text-12">
                      <b-form-input
                        v-model="organization_name"
                        :state="getValidationState(validationContext)"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <!-- First Name -->
                  <validation-provider name="First Name" rules="required" v-slot="validationContext">
                    <b-form-group label="First Name" class="text-12">
                      <b-form-input
                        v-model="firstname"
                        :state="getValidationState(validationContext)"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <!-- Last Name -->
                  <validation-provider name="Last Name" rules="required" v-slot="validationContext">
                    <b-form-group label="Last Name" class="text-12">
                      <b-form-input
                        v-model="lastname"
                        :state="getValidationState(validationContext)"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <!-- Username -->
                  <validation-provider name="Username" rules="required" v-slot="validationContext">
                    <b-form-group label="Username" class="text-12">
                      <b-form-input
                        v-model="username"
                        :state="getValidationState(validationContext)"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <!-- Email -->
                  <validation-provider name="Email" rules="required|email" v-slot="validationContext">
                    <b-form-group label="Email" class="text-12">
                      <b-form-input
                        v-model="email"
                        :state="getValidationState(validationContext)"
                        type="email"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <!-- Phone (optional) -->
                  <b-form-group label="Phone" class="text-12">
                    <b-form-input
                      v-model="phone"
                      class="form-control-rounded"
                    />
                  </b-form-group>

                  <!-- Password -->
                  <validation-provider name="Password" rules="required|min:6" v-slot="validationContext">
                    <b-form-group label="Password" class="text-12">
                      <b-form-input
                        v-model="password"
                        name="password"
                        type="password"
                        :state="getValidationState(validationContext)"
                        class="form-control-rounded"
                      />
                      <b-form-invalid-feedback>{{ validationContext.errors[0] }}</b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>

                  <b-button
                    type="submit"
                    class="btn-rounded btn-block mt-2"
                    variant="primary"
                    :disabled="loading"
                  >
                    Register
                  </b-button>

                  <div v-if="loading" class="text-center mt-3">
                    <div class="spinner sm spinner-primary"></div>
                  </div>
                </b-form>
              </validation-observer>

              <div class="mt-3 text-center">
                Already have an account?
                <a href="/login" class="text-muted"><u>Login</u></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from "vee-validate";
import { mapGetters } from "vuex";
import NProgress from "nprogress";

export default {
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      organization_name: "",
      firstname: "",
      lastname: "",
      username: "",
      email: "",
      phone: "",
      password: "",
      loading: false,
      logo: null,
    };
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
  },
  mounted() {
    axios
      .get("/api/get-logo-setting")
      .then((res) => {
        this.logo = res.data.logo ? `/images/${res.data.logo}` : "/images/logo.png";
      })
      .catch(() => {
        this.logo = "/images/logo.png";
      });
  },
  methods: {
    submitRegister() {
      this.$refs.register_form.validate().then((valid) => {
        if (!valid) {
          this.makeToast("danger", "Please fill all fields correctly", "Failed");
          return;
        }

        this.loading = true;
        NProgress.start();
        axios
          .post("/register", {
            organization_name: this.organization_name,
            firstname: this.firstname,
            lastname: this.lastname,
            username: this.username,
            email: this.email,
            password: this.password,
            phone: this.phone,
          })
          .then(() => {
            this.makeToast("success", "Registered Successfully!", "Success");
            window.location.href = "/";
          })
          .catch(() => {
            this.makeToast("danger", "Registration failed", "Error");
          })
          .finally(() => {
            this.loading = false;
            NProgress.done();
          });
      });
    },

    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },

    makeToast(variant, msg, title) {
      this.$root.$bvToast.toast(msg, {
        title: title,
        variant: variant,
        solid: true,
      });
    },
  },
};
</script>
