<template>
  <div class="page-wrapper">
    <div class="row page-row">
      <div class="col-md-6 page-form">
        <div class="text-center mb-4">
          <router-link to="/"><img class="mb-4" loading="lazy" :src="`${$APP_URL}/images/logo-large.png`" height="50px" alt="FiliPay Logo" /></router-link>
          <h6 class="font-weight-normal mb-4">Create a strong password</h6>
        </div>

        <form @submit.prevent="submitResetPassword()">
          <div class="form-group" v-show="!$isEmpty(passwordMessage)">
            <form-alert variant="success">
              <p v-for="message in passwordMessage" :key="message" v-html="message"></p>
            </form-alert>
          </div>
          <div class="form-group" v-show="!$isEmpty(passwordError)">
            <form-alert variant="warning">
              <p v-for="error in passwordError" :key="error" v-html="error"></p>
            </form-alert>
          </div>
          <div class="form-group">
            <input type="text" class="form-control form-control-lg text-sm" placeholder="Email Address" v-model="email" />
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
              <span v-else>Reset Password</span>
            </button>
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
  name: "Reset",
  metaInfo: () => ({ title: "Reset Password" }),
  data: () => ({
    isLoading: false,
    passwordError: [],
    passwordMessage: [],
    token: "",
    email: "",
    password: "",
    password_confirmation: ""
  }),
  methods: {
    resetForm() {
      this.email = "";
      this.password = "";
      this.password_confirmation = "";
    },

    async submitResetPassword() {
      this.isLoading = true;
      this.passwordError = [];
      this.passwordMessage = [];

      try {
        let { data } = await axios.post("/password/reset", {
          token: this.token,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation
        });

        this.resetForm();
        this.passwordMessage = data.status;
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.passwordError.push(message);
            });
          }
        }
      }

      this.isLoading = false;
    }
  },
  created() {
    this.email = this.$route.query.email;
    this.token = this.$route.params.token;
  }
};
</script>
