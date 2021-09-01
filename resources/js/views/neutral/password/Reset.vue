<template>
  <div class="row h-100">
    <div class="col-lg-6 page-wrapper">
      <div class="page-form">
        <div class="mb-4">
          <!-- <img loading="lazy" :src="`${$APP_URL}/images/logo.png`" height="50px" alt="FiliPay Logo" /> -->
          <h3>Xerodesk</h3>
        </div>

        <h4>Lost your Password?</h4>
        <h6 class="font-weight-light mb-4">Create and store a new one!</h6>

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
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <InlineSvg name="template/mdi-account-outline.svg" color="#c9c8c8" size="1rem" />
                </span>
              </div>
              <input type="text" class="form-control" placeholder="Email Address" v-model="email" />
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
              <span v-else>Reset Password</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-6 page-background" style="background-image: url('/images/template/bg-request.jpg')"></div>
  </div>
</template>

<script>
export default {
  layout: "Neutral",
  name: "Reset",
  metaInfo: () => ({
    title: "Reset Password"
  }),
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

            error.forEach(message => {
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
