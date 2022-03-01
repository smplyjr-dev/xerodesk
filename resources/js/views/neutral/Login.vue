<template>
  <div class="page-wrapper">
    <div class="row page-row">
      <div class="col-md-6 page-form">
        <div class="text-center mb-4">
          <router-link to="/"><img class="mb-2" loading="lazy" :src="`${$APP_URL}/images/logo-large.png`" height="50px" alt="FiliPay Logo" /></router-link>
          <h6 class="font-weight-normal mb-4">Need an account? <router-link to="/register">Get started here</router-link>!</h6>
        </div>

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
            <input type="text" class="form-control form-control-lg text-sm" placeholder="Username" v-model="username" />
          </div>
          <div class="form-group">
            <input type="password" class="form-control form-control-lg text-sm" placeholder="Password" v-model="password" />
          </div>
          <div class="form-group">
            <div class="d-flex justify-content-between">
              <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" class="custom-control-input" id="customControlAutosizing" v-model="remember" />
                <label class="custom-control-label" for="customControlAutosizing">Remember Me</label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-lg text-sm" :disabled="isLoading">
              <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Sign in</span>
            </button>
          </div>
          <div class="form-group text-center mb-0">
            <router-link class="d-block" to="/password/reset">Forgot <span class="d-none d-sm-inline">your</span> password?</router-link>
            <router-link class="d-block" to="/email/resend">Didn't receive email confirmation?</router-link>
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
          this.$router.push(decoded).catch((err) => {});
        } else {
          this.$router.push("/tickets").catch((err) => {});
        }
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
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
