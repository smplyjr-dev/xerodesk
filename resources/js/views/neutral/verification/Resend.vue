<template>
  <div class="page-wrapper">
    <div class="row page-row">
      <div class="col-md-6 page-form">
        <div class="text-center mb-4">
          <router-link to="/"><img class="mb-4" loading="lazy" :src="`${$APP_URL}/images/logo-large.png`" height="50px" alt="FiliPay Logo" /></router-link>
          <h6 class="font-weight-normal mb-4">Resend Verification Link</h6>
        </div>

        <form @submit.prevent="submitResend()">
          <div class="form-group" v-show="!$isEmpty($route.query.referrer)">
            <form-alert variant="warning">
              <p>{{ $route.query.referrer_message }}</p>
            </form-alert>
          </div>
          <div class="form-group" v-show="!$isEmpty(resendMessage)">
            <form-alert variant="success">
              <p v-for="message in resendMessage" :key="message" v-html="message"></p>
            </form-alert>
          </div>
          <div class="form-group" v-show="!$isEmpty(resendError)">
            <form-alert variant="warning">
              <p v-for="error in resendError" :key="error" v-html="error"></p>
            </form-alert>
          </div>
          <div class="form-group">
            <input type="email" class="form-control form-control-lg text-sm" placeholder="Email address" v-model="email" />
          </div>
          <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary btn-block btn-lg text-sm" :disabled="isLoading">
              <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Submit</span>
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
  name: "Resend",
  metaInfo: () => ({ title: "Dashboard" }),
  middleware: "guest",
  data: () => ({
    isLoading: false,
    resendError: [],
    resendMessage: [],
    email: ""
  }),
  methods: {
    resetForm() {
      this.email = "";
    },

    async submitResend() {
      this.isLoading = true;
      this.resendError = [];
      this.resendMessage = [];

      try {
        let { data } = await axios.post("/email/resend", {
          email: this.email
        });

        this.resetForm();
        this.resendMessage = data.status;
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.resendError.push(message);
            });
          }
        }
      }

      this.isLoading = false;
    }
  },
  created() {
    if (this.$route.query.email) {
      this.email = this.$route.query.email;
    }
  }
};
</script>
