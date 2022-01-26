<template>
  <div class="page-wrapper">
    <div class="row page-row">
      <div class="col-md-6 page-form">
        <div class="text-center mb-4">
          <router-link to="/"><img class="mb-4" loading="lazy" :src="`${$APP_URL}/images/logo-large.png`" height="50px" alt="FiliPay Logo" /></router-link>
          <h6 class="font-weight-normal mb-4">Ready to create your account?</h6>
        </div>

        <form @submit.prevent="submitRegistration()">
          <div class="form-group" v-show="!$isEmpty(registrationMessage)">
            <form-alert variant="success">
              <p v-for="message in registrationMessage" :key="message" v-html="message"></p>
            </form-alert>
          </div>
          <div class="form-group" v-show="!$isEmpty(registrationError)">
            <form-alert variant="warning">
              <p v-for="error in registrationError" :key="error" v-html="error"></p>
            </form-alert>
          </div>
          <div class="form-group row">
            <div class="col">
              <input type="text" class="form-control form-control-lg text-sm" placeholder="First Name" v-model="first_name" />
            </div>
            <div class="col">
              <input type="text" class="form-control form-control-lg text-sm" placeholder="Last Name" v-model="last_name" />
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control form-control-lg text-sm" placeholder="Username" v-model="username" />
          </div>
          <div class="form-group">
            <input type="text" class="form-control form-control-lg text-sm" placeholder="Email" v-model="email" />
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg text-sm" placeholder="Password" v-model="password" />
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg text-sm" placeholder="Confirm Password" v-model="password_confirmation" />
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-lg text-sm" :disabled="isLoading">
              <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Sign up</span>
            </button>
          </div>
          <div class="form-group text-center mb-0">
            <span>Already have an account? <router-link to="/">Sign in</router-link></span>
          </div>
        </form>
      </div>
      <div class="col-md-6 d-none d-md-block">
        <img loading="lazy" class="object-contain py-5 px-2 w-100 h-100" src="/images/template/bg-login.png" alt="Page Background" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "GuestAuth",
  name: "Register",
  metaInfo: () => ({ title: "Register" }),
  middleware: "guest",
  data: () => ({
    isLoading: false,
    registrationError: [],
    registrationMessage: [],
    first_name: "",
    last_name: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: ""
  }),
  methods: {
    async submitRegistration() {
      this.isLoading = true;
      this.registrationError = [];
      this.registrationMessage = [];

      try {
        let { data } = await axios.post("/register", {
          first_name: this.first_name,
          last_name: this.last_name,
          username: this.username,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        this.first_name = "";
        this.last_name = "";
        this.email = "";
        this.username = "";
        this.password = "";
        this.password_confirmation = "";

        this.registrationMessage = data.status;
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.registrationError.push(message);
            });
          }
        }
      }

      this.isLoading = false;
    }
  }
};
</script>
