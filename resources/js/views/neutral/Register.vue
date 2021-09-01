<template>
  <div class="row h-100">
    <div class="col-lg-6 page-wrapper">
      <div class="page-form">
        <div class="mb-4">
          <img loading="lazy" :src="`${$APP_URL}/images/logo.png`" height="50px" alt="FiliPay Logo" />
        </div>

        <h4>Hi there!</h4>
        <h6 class="font-weight-light mb-4">Ready to create your account?</h6>

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
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <InlineSvg name="template/mdi-card-account-details-outline.svg" color="#c9c8c8" size="1rem" />
                  </span>
                </div>
                <input type="text" class="form-control" placeholder="First Name" v-model="first_name" />
              </div>
            </div>
            <div class="col">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <InlineSvg name="template/mdi-card-account-details-outline.svg" color="#c9c8c8" size="1rem" />
                  </span>
                </div>
                <input type="text" class="form-control" placeholder="Last Name" v-model="last_name" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-account-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Username" v-model="username" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-at.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Email" v-model="email" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-lock-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input type="password" class="form-control" placeholder="Password" v-model="password" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-lock-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input type="password" class="form-control" placeholder="Confirm Password" v-model="password_confirmation" />
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block text-uppercase text-sm rounded-0 py-2" :disabled="isLoading">
              <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Submit</span>
            </button>
          </div>
          <div class="form-group text-center">
            <span>Already have an account? <router-link to="/">Sign in</router-link></span>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-6 page-background" style="background-image: url('/images/template/bg-register.jpg')"></div>
  </div>
</template>

<script>
export default {
  layout: "Neutral",
  name: "Register",
  metaInfo: () => ({
    title: "Register"
  }),
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

            error.forEach(message => {
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
