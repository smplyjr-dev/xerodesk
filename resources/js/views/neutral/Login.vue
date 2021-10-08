<template>
  <div class="row h-100">
    <div class="col-lg-6 page-wrapper">
      <div class="page-form">
        <div class="mb-4">
          <!-- <img loading="lazy" :src="`${$APP_URL}/images/logo.png`" height="50px" alt="FiliPay Logo" /> -->
          <h3>Xerodesk</h3>
        </div>

        <h4>Welcome back!</h4>
        <h6 class="font-weight-light mb-4">Happy to see you again!</h6>

        <form @submit.prevent="submitLogin()">
          <div class="form-group" v-show="!$isEmpty($route.query.referrer)">
            <form-alert :variant="$route.query.referrer_type">
              <p>{{ $route.query.referrer_message }}</p>
            </form-alert>
          </div>
          <div class="form-group" v-show="!$isEmpty(loginError)">
            <form-alert variant="warning">
              <p v-for="error in loginError" :key="error" v-html="error"></p>
            </form-alert>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-account-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input id="uname" type="text" class="form-control" placeholder="Username" v-model="username" />
            </div>
          </div>
          <div class="form-group">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-lock-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input id="pword" type="password" class="form-control" placeholder="Password" v-model="password" />
            </div>
          </div>
          <div class="form-group">
            <div class="d-flex">
              <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" class="custom-control-input" id="customControlAutosizing" />
                <label class="custom-control-label" for="customControlAutosizing">Remember Me</label>
              </div>

              <router-link class="ml-auto" to="/password/reset">Forgot <span class="d-none d-sm-inline">Your</span> Password?</router-link>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block text-uppercase text-sm rounded-0 py-2" :disabled="isLoading">
              <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Submit</span>
            </button>
          </div>
          <!-- <div class="form-group text-center">
            <span>New to Xerodesk? <router-link to="/register">Sign up</router-link></span>
          </div> -->
        </form>
      </div>
    </div>

    <div class="col-lg-6 page-background" style="background-image: url('/images/template/bg-login.jpg')"></div>
  </div>
</template>

<script>
export default {
  layout: "Neutral",
  name: "Login",
  metaInfo: () => ({ title: "Login" }),
  middleware: ["guest"],
  data: () => ({
    isLoading: false,
    loginError: [],
    email: "",
    username: "",
    password: "",
    remember: false
  }),
  methods: {
    resetForm() {
      this.username = "";
      this.password = "";
    },

    async submitLogin() {
      this.isLoading = true;
      this.loginError = [];

      try {
        let { data } = await axios.post("/login", {
          email: this.email,
          username: this.username,
          password: this.password
        });

        this.$store.dispatch("auth/saveToken", {
          token: data.token,
          remember: this.remember
        });

        this.resetForm();

        // Redirect to proper page
        if (this.$route.query.target) {
          let decoded = decodeURIComponent(this.$route.query.target);
          this.$router.push(decoded).catch(err => {});
        } else {
          this.$router.push("/tickets").catch(err => {});
        }
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.loginError.push(message);
            });
          }
        }
      }

      this.isLoading = false;
    }
  },
  watch: {
    username(newVal) {
      this.email = newVal;
    }
  }
};
</script>
